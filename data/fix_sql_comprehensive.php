<?php
/**
 * Comprehensive SQL fixer for DigitalOcean MySQL compatibility
 */

$inputFile = __DIR__ . '/choviet29.sql';
$outputFile = __DIR__ . '/choviet29_fixed.sql';

if (!file_exists($inputFile)) {
    die("Error: Input file not found: $inputFile\n");
}

echo "🔧 Reading SQL file...\n";
$content = file_get_contents($inputFile);
$originalSize = strlen($content);
echo "   Original size: " . $originalSize . " bytes\n";

echo "\n🔧 Applying fixes...\n";

// Fix 1: Remove DEFINER clauses (DigitalOcean doesn't allow creating procedures with specific DEFINER)
echo "   ✓ Removing DEFINER clauses...\n";
$content = preg_replace('/CREATE\s+DEFINER=`[^`]*`@`[^`]*`\s+/', 'CREATE ', $content);
$content = str_replace('SQL SECURITY DEFINER ', '', $content);
$content = str_replace('SQL SECURITY INVOKER ', '', $content);

// Fix 2: Disable FK and unique checks
echo "   ✓ Adding FK/Unique checks disable...\n";
$header = "-- Fixed for DigitalOcean MySQL\n";
$header .= "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;\n";
$header .= "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;\n";
$header .= "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n";

// Fix 3: Remove problematic DEFAULT functions
echo "   ✓ Removing problematic DEFAULT functions...\n";
$content = str_replace('DEFAULT curdate()', '', $content);
$content = str_replace('DEFAULT current_timestamp()', '', $content);
$content = str_replace('default curdate()', '', $content);
$content = str_replace('default current_timestamp()', '', $content);
$content = str_replace('DEFAULT CURRENT_DATE', '', $content);
$content = str_replace('DEFAULT CURRENT_TIMESTAMP', '', $content);
$content = preg_replace('/\s+DEFAULT\s+(CURRENT_DATE|CURRENT_TIMESTAMP)\s*,/', ',', $content);
$content = preg_replace('/\s+DEFAULT\s+(CURRENT_DATE|CURRENT_TIMESTAMP)\s*\n/', "\n", $content);

// Fix 4: Ensure proper ON UPDATE clauses (uppercase)
echo "   ✓ Fixing ON UPDATE clauses...\n";
$content = str_replace('ON UPDATE current_timestamp()', 'ON UPDATE CURRENT_TIMESTAMP', $content);
$content = str_replace('ON UPDATE CURRENT_TIMESTAMP()', 'ON UPDATE CURRENT_TIMESTAMP', $content);

// Fix 5: Add PRIMARY KEY to transfer_history
echo "   ✓ Adding PRIMARY KEY to tables without one...\n";
// transfer_history
if (strpos($content, 'CREATE TABLE `transfer_history`') !== false) {
    $content = preg_replace(
        '/CREATE TABLE `transfer_history` \(\s*`history_id` int\(11\) NOT NULL,\s*`user_id` int\(11\) NOT NULL,/',
        "CREATE TABLE `transfer_history` (\n  `history_id` int(11) NOT NULL PRIMARY KEY,\n  `user_id` int(11) NOT NULL,",
        $content
    );
}

// Fix 6: Clean up duplicate PRIMARY KEY statements in ALTER
echo "   ✓ Removing duplicate PRIMARY KEY definitions...\n";
$content = preg_replace('/`parent_category_id` int\(11\) NOT NULL PRIMARY KEY,/', '`parent_category_id` int(11) NOT NULL,', $content);

// Fix 7: Remove trailing commas before closing parens/PRIMARY KEY
echo "   ✓ Fixing syntax errors (trailing commas, etc)...\n";
$content = preg_replace('/,\s*\n\s*\n\s*PRIMARY KEY/', "\n  PRIMARY KEY", $content);
$content = preg_replace('/,\s*\n\s*PRIMARY KEY/', "\n  PRIMARY KEY", $content);
$content = preg_replace('/,(\s*)\)/', '$1)', $content); // Remove trailing comma before )

// Fix 8: Add FK/unique checks restore at the end
echo "   ✓ Adding FK/Unique checks restore...\n";
$footer = "\n-- Restore settings\n";
$footer .= "SET SQL_MODE=@OLD_SQL_MODE;\n";
$footer .= "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;\n";
$footer .= "SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;\n";

// Combine
$fixed = $header . $content . $footer;
$newSize = strlen($fixed);

echo "\n✅ All fixes applied!\n";
echo "   New size: " . $newSize . " bytes\n";

// Write output
file_put_contents($outputFile, $fixed);

echo "\n✅ Fixed SQL file created: $outputFile\n";
echo "\n📝 Ready to import into DigitalOcean MySQL!\n";
echo "   Command: mysql -h <host> -u <user> -p <database> < $outputFile\n";
