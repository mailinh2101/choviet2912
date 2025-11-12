# Deploy to DigitalOcean App Platform
# PowerShell script

Write-Host "🚀 Deploying to DigitalOcean App Platform..." -ForegroundColor Cyan
Write-Host ""

# Check if in correct directory
if (!(Test-Path "package.json")) {
    Write-Host "❌ Error: package.json not found. Are you in the project directory?" -ForegroundColor Red
    exit 1
}

# Check Git status
Write-Host "📊 Checking Git status..." -ForegroundColor Yellow
git status --short

Write-Host ""
$continue = Read-Host "Continue with deployment? (y/n)"
if ($continue -ne "y") {
    Write-Host "❌ Deployment cancelled." -ForegroundColor Red
    exit 0
}

# Add all changes
Write-Host ""
Write-Host "📦 Adding changes to Git..." -ForegroundColor Yellow
git add .

# Commit
Write-Host ""
$commitMessage = Read-Host "Enter commit message (or press Enter for default)"
if ([string]::IsNullOrWhiteSpace($commitMessage)) {
    $commitMessage = "Deploy update $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
}

Write-Host "💾 Committing changes..." -ForegroundColor Yellow
git commit -m $commitMessage

# Push
Write-Host ""
Write-Host "⬆️  Pushing to GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ Successfully pushed to GitHub!" -ForegroundColor Green
    Write-Host ""
    Write-Host "⏳ App Platform will auto-deploy in a few moments..." -ForegroundColor Cyan
    Write-Host ""
    Write-Host "📱 Check deployment status at:" -ForegroundColor White
    Write-Host "   https://cloud.digitalocean.com/apps" -ForegroundColor Blue
    Write-Host ""
    Write-Host "🌐 Your app URL:" -ForegroundColor White
    Write-Host "   https://sea-lion-app-oa3de.ondigitalocean.app/" -ForegroundColor Blue
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "❌ Failed to push to GitHub!" -ForegroundColor Red
    Write-Host "Please check your Git configuration and try again." -ForegroundColor Yellow
    exit 1
}
