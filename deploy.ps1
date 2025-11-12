# Deploy Script cho DigitalOcean
# Usage: .\deploy.ps1 -action deploy

param(
    [string]$action = "help",
    [string]$dropletIp = "",
    [string]$dbPassword = "",
    [string]$domain = ""
)

function Show-Help {
    Write-Host @"
╔════════════════════════════════════════════════════════════════╗
║         Chợ Việt - DigitalOcean Deployment Helper              ║
╚════════════════════════════════════════════════════════════════╝

USAGE:
    .\deploy.ps1 -action <action> [options]

ACTIONS:
    help                 Show this help message
    prepare              Prepare local files for deployment
    ssh-setup            Setup SSH connection
    deploy               Full deployment to DigitalOcean
    backup               Backup database locally
    pull-updates         Pull latest code from GitHub

OPTIONS:
    -dropletIp <IP>      DigitalOcean Droplet IP address
    -dbPassword <pwd>    Database password for choviet_user
    -domain <domain>     Your domain name (e.g., choviet.com)

EXAMPLES:
    # Show help
    .\deploy.ps1 -action help

    # Prepare files locally
    .\deploy.ps1 -action prepare

    # SSH into server
    .\deploy.ps1 -action ssh-setup -dropletIp 192.168.1.100

    # Backup database
    .\deploy.ps1 -action backup

    # Pull latest code
    .\deploy.ps1 -action pull-updates

    # Full deployment (requires all options)
    .\deploy.ps1 -action deploy -dropletIp 192.168.1.100 `
                 -dbPassword "secure_password" -domain "choviet.com"

IMPORTANT NOTES:
    1. Replace credentials in config files before deploying
    2. Ensure SSH key is set up on your machine
    3. Database password should be secure
    4. Domain should be already registered

For more details, see: DEPLOYMENT_GUIDE_VI.md
"@
}

function Prepare-LocalFiles {
    Write-Host "`n🔄 Preparing local files..." -ForegroundColor Cyan
    
    # Check required files
    $requiredFiles = @(
        "composer.json",
        "package.json",
        "config/path_config.php",
        "model/mConnect.php",
        ".htaccess"
    )
    
    foreach ($file in $requiredFiles) {
        if (Test-Path $file) {
            Write-Host "✓ Found: $file" -ForegroundColor Green
        } else {
            Write-Host "✗ Missing: $file" -ForegroundColor Red
        }
    }
    
    # Create necessary directories
    $dirs = @("logs", "chat", "temp")
    foreach ($dir in $dirs) {
        if (-not (Test-Path $dir)) {
            New-Item -ItemType Directory -Path $dir | Out-Null
            Write-Host "✓ Created directory: $dir" -ForegroundColor Green
        }
    }
    
    # Ensure .gitignore exists
    if (-not (Test-Path ".gitignore")) {
        Write-Host "⚠ Creating .gitignore..." -ForegroundColor Yellow
        Add-Content -Path ".gitignore" @"
vendor/
node_modules/
logs/*
!logs/.gitkeep
chat/*.json
config/server_config.js
config/email_config.php
*.log
*.swp
.DS_Store
"@
    }
    
    Write-Host "✓ Local files prepared!" -ForegroundColor Green
}

function Setup-SSH {
    param([string]$ip)
    
    if ([string]::IsNullOrEmpty($ip)) {
        Write-Host "Error: Please provide -dropletIp parameter" -ForegroundColor Red
        return
    }
    
    Write-Host "`n🔐 Setting up SSH connection..." -ForegroundColor Cyan
    Write-Host "IP Address: $ip" -ForegroundColor Yellow
    
    Write-Host "`nAttempting SSH connection (first time will ask for password)..." -ForegroundColor Yellow
    Write-Host "Once connected, you can run commands on the server." -ForegroundColor Yellow
    
    Write-Host "`nAfter connecting, you should:" -ForegroundColor Cyan
    Write-Host "1. Set up SSH key: ssh-keygen"
    Write-Host "2. Copy key: cat ~/.ssh/id_rsa.pub"
    Write-Host "3. Add to authorized_keys"
    
    # Try to SSH
    ssh root@$ip
}

function Backup-Database {
    Write-Host "`n💾 Creating local database backup..." -ForegroundColor Cyan
    
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $backupFile = "backup_choviet29_$timestamp.sql"
    
    # This assumes you have MySQL installed locally
    if (Get-Command mysqldump -ErrorAction SilentlyContinue) {
        mysqldump -u admin -p123456 choviet29 > $backupFile
        Write-Host "✓ Backup created: $backupFile" -ForegroundColor Green
        Write-Host "File size: $(((Get-Item $backupFile).length / 1MB).ToString('F2')) MB" -ForegroundColor Yellow
    } else {
        Write-Host "✗ mysqldump not found. Please install MySQL client tools." -ForegroundColor Red
    }
}

function Pull-Updates {
    Write-Host "`n📥 Pulling latest code from GitHub..." -ForegroundColor Cyan
    
    try {
        git status
        Write-Host "`n📋 Showing status..." -ForegroundColor Yellow
        git status
        
        Write-Host "`n⚠️  Review changes above. Continue? (Y/n)" -ForegroundColor Yellow
        $response = Read-Host
        
        if ($response -eq "Y" -or $response -eq "y") {
            git pull origin main
            Write-Host "✓ Code updated!" -ForegroundColor Green
            
            Write-Host "`n📦 Installing dependencies..." -ForegroundColor Cyan
            composer install
            npm install
            
            Write-Host "✓ Dependencies installed!" -ForegroundColor Green
        }
    } catch {
        Write-Host "Error: $_" -ForegroundColor Red
    }
}

function Full-Deploy {
    param(
        [string]$ip,
        [string]$dbPassword,
        [string]$domain
    )
    
    if ([string]::IsNullOrEmpty($ip) -or [string]::IsNullOrEmpty($dbPassword)) {
        Write-Host "Error: Missing required parameters (-dropletIp, -dbPassword)" -ForegroundColor Red
        return
    }
    
    Write-Host @"
╔════════════════════════════════════════════════════════════════╗
║              🚀 FULL DEPLOYMENT STARTING                        ║
╚════════════════════════════════════════════════════════════════╝

Configuration:
  Droplet IP: $ip
  Database: choviet29
  DB User: choviet_user
  Domain: $domain

⚠️  This will deploy your application to DigitalOcean
    Make sure all credentials are updated in config files!

Continue? (Y/n)
"@
    
    $response = Read-Host
    if ($response -ne "Y" -and $response -ne "y") {
        Write-Host "Deployment cancelled." -ForegroundColor Yellow
        return
    }
    
    Write-Host "`n📋 Deployment Steps:" -ForegroundColor Cyan
    Write-Host "1. Check local files"
    Write-Host "2. Backup database"
    Write-Host "3. Connect to server"
    Write-Host "4. Setup server environment"
    Write-Host "5. Clone repository"
    Write-Host "6. Configure database"
    Write-Host "7. Configure email"
    Write-Host "8. Setup SSL certificate"
    Write-Host "9. Test deployment"
    
    Write-Host "`nFor detailed deployment, please refer to: DEPLOYMENT_GUIDE_VI.md" -ForegroundColor Yellow
    Write-Host "Steps 4-9 must be done manually on the server via SSH." -ForegroundColor Yellow
    
    Write-Host "`nTo connect to your server:" -ForegroundColor Green
    Write-Host "ssh root@$ip" -ForegroundColor Cyan
    
    Write-Host "`nThen follow the deployment guide for step-by-step instructions." -ForegroundColor Yellow
}

# Main logic
switch ($action.ToLower()) {
    "help" { Show-Help }
    "prepare" { Prepare-LocalFiles }
    "ssh-setup" { Setup-SSH -ip $dropletIp }
    "backup" { Backup-Database }
    "pull-updates" { Pull-Updates }
    "deploy" { Full-Deploy -ip $dropletIp -dbPassword $dbPassword -domain $domain }
    default {
        Write-Host "Unknown action: $action" -ForegroundColor Red
        Show-Help
    }
}
