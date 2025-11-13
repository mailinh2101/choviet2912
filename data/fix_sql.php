<?php
/**
 * Script to fix SQL dump for phpMyAdmin compatibility
 * Removes SQL SECURITY DEFINER and adds FK checks disable/enable
 */

$inputFile = __DIR__ . '/choviet29_original.sql';
$outputFile = __DIR__ . '/choviet29_fixed.sql';

if (!file_exists($inputFile)) {
    die("Error: Input file not found: $inputFile\n");
}

echo "Reading SQL file...\n";
$content = file_get_contents($inputFile);

echo "Original file size: " . strlen($content) . " bytes\n";

// Fix 1: Remove SQL SECURITY DEFINER from views
$content = preg_replace('/\s+SQL SECURITY DEFINER\s+/', ' ', $content);

// Fix 2: Replace curdate() with CURDATE() and current_timestamp() with CURRENT_TIMESTAMP
$content = str_replace('DEFAULT curdate()', 'DEFAULT CURRENT_DATE', $content);
$content = str_replace('DEFAULT current_timestamp()', 'DEFAULT CURRENT_TIMESTAMP', $content);
$content = str_replace('default curdate()', 'DEFAULT CURRENT_DATE', $content);
$content = str_replace('default current_timestamp()', 'DEFAULT CURRENT_TIMESTAMP', $content);

// Fix 2b: Remove CURRENT_DATE and CURRENT_TIMESTAMP from DEFAULT (MySQL doesn't support as DEFAULT for date/datetime)
// Instead, we'll remove the DEFAULT clause entirely for those columns
$content = preg_replace('/\s+DEFAULT CURRENT_DATE\s*,/', ',', $content);
$content = preg_replace('/\s+DEFAULT CURRENT_TIMESTAMP\s*,/', ',', $content);

// Also handle cases at end of column definition (before closing paren or comment)
$content = preg_replace('/\s+DEFAULT CURRENT_DATE(\s*\n\s*,|\s*\n\s*\))/', '$1', $content);
$content = preg_replace('/\s+DEFAULT CURRENT_TIMESTAMP(\s*\n\s*,|\s*\n\s*\))/', '$1', $content);

// Fix 2c: For ON UPDATE cases, convert to valid MySQL syntax
// current_timestamp() -> CURRENT_TIMESTAMP
$content = str_replace('ON UPDATE current_timestamp()', 'ON UPDATE CURRENT_TIMESTAMP', $content);

// Fix 2d: Add PRIMARY KEY to tables that don't have one
// Find parent_categories table and add PRIMARY KEY
$content = preg_replace(
    '/CREATE TABLE `parent_categories` \(\s*`parent_category_id` int\(11\) NOT NULL,\s*`parent_category_name` varchar\(100\) NOT NULL\s*\)/',
    "CREATE TABLE `parent_categories` (\n  `parent_category_id` int(11) NOT NULL PRIMARY KEY,\n  `parent_category_name` varchar(100) NOT NULL\n)",
    $content
);

// Fix 3: Fix PRIMARY KEY lines - ensure proper comma before it
// Remove trailing comma + newline before PRIMARY KEY and add single comma
$content = preg_replace('/,?\s*\n\s*,?\s*\n\s*PRIMARY KEY/', ",\n  PRIMARY KEY", $content);
// Fix case where there's just one newline
$content = preg_replace('/\s+\n\s*PRIMARY KEY/', ",\n  PRIMARY KEY", $content);

// Fix 4: Add FK/unique checks disable at the start
$header = "-- Fixed SQL dump for phpMyAdmin import\n";
$header .= "-- Automatically processed to remove incompatibilities\n\n";
$header .= "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;\n";
$header .= "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;\n";
$header .= "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n";

// Fix 5: Add FK/unique checks restore at the end
$footer = "\n-- Restore checks\n";
$footer .= "SET SQL_MODE=@OLD_SQL_MODE;\n";
$footer .= "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;\n";
$footer .= "SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;\n";

// Combine
$fixed = $header . $content . $footer;

echo "Fixed file size: " . strlen($fixed) . " bytes\n";

// Write output
file_put_contents($outputFile, $fixed);

echo "✅ Fixed SQL file created: $outputFile\n";
echo "\nYou can now import this file into phpMyAdmin.\n";
