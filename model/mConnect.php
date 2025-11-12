<?php
// Ensure .env is loaded before attempting to get DB config
if (!getenv('DB_HOST')) {
    $bootstrapPath = __DIR__ . '/../config/bootstrap.php';
    if (file_exists($bootstrapPath)) {
        require_once $bootstrapPath;
    }
}

class Connect {
    public function connect() {
        // Thiết lập múi giờ PHP cho toàn ứng dụng
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
        }

        // Read DB config from environment variables (require DigitalOcean .env, no fallbacks)
        $dbHost = getenv('DB_HOST');
        $dbPort = getenv('DB_PORT');
        $dbUser = getenv('DB_USER');
        $dbPass = getenv('DB_PASS');
        $dbName = getenv('DB_NAME');
        
        // Require all variables to be set (fail fast if .env not loaded)
        if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
            $err = "Missing DB environment variables. Check .env file is loaded: DB_HOST=" . ($dbHost ?: 'MISSING') . ", DB_USER=" . ($dbUser ?: 'MISSING') . ", DB_NAME=" . ($dbName ?: 'MISSING');
            error_log($err);
            echo $err;
            exit();
        }
        
        // Log which DB we're connecting to (helpful for debugging)
        error_log("[mConnect] Connecting to: host=$dbHost, port=$dbPort, user=$dbUser, db=$dbName");

        // If a port is specified and the host is 'localhost', force TCP by using 127.0.0.1
        // This avoids issues where mysqli attempts a Unix socket that doesn't exist
        $connectHost = $dbHost;
        if ($dbHost === 'localhost' && !empty($dbPort)) {
            $connectHost = '127.0.0.1';
        }

        // Use mysqli_init + mysqli_real_connect so we can set a connection timeout and
        // avoid long blocking when the DB is unreachable.
        $mysqli = mysqli_init();
        // Set low connect timeout (seconds)
        if (defined('MYSQLI_OPT_CONNECT_TIMEOUT')) {
            mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        }

        // Optional: set SSL mode if DB_SSLMODE=REQUIRED (handled later if needed)

        $connected = false;
        try {
            if (!empty($dbPort)) {
                $connected = @mysqli_real_connect($mysqli, $connectHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
            } else {
                $connected = @mysqli_real_connect($mysqli, $connectHost, $dbUser, $dbPass, $dbName);
            }
        } catch (Throwable $e) {
            error_log("Database Connection Throwable: " . $e->getMessage());
        }

        if ($connected) {
            $con = $mysqli;
        } else {
            $err = mysqli_connect_error();
            error_log("Database Connection Error: " . $err);
            echo "Lỗi kết nối cơ sở dữ liệu: " . $err;
            exit();
        }

        if (!$con) {
            // Use error_log instead of echo in production; keep echo for backward compatibility
            error_log("Database Connection Error: " . mysqli_connect_error());
            echo "Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error();
            exit();
        } else {
            mysqli_query($con, "SET NAMES 'utf8'");
            // Thiết lập múi giờ cho phiên MySQL để NOW()/TIMESTAMP đồng bộ +07:00
            @mysqli_query($con, "SET time_zone = '+07:00'");
            return $con;
        }
    }
}
?>

