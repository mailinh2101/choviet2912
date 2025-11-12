<?php
/**
 * Ultimate SQL fixer - finds and adds PRIMARY KEY to all tables missing one
 */

$inputFile = __DIR__ . '/choviet29.sql';
$outputFile = __DIR__ . '/choviet29_fixed.sql';

if (!file_exists($inputFile)) {
    die("❌ Error: Input file not found: $inputFile\n");
}

echo "🔧 Reading SQL file...\n";
$content = file_get_contents($inputFile);
$originalSize = strlen($content);
echo "   Original size: " . $originalSize . " bytes\n";

echo "\n🔧 Analyzing tables...\n";

// Find all CREATE TABLE statements and check for PRIMARY KEY
preg_match_all('/CREATE TABLE `(\w+)`\s*\((.*?)\)\s*ENGINE/s', $content, $matches);
$tableCount = count($matches[1]);
echo "   Found $tableCount tables\n";

$tablesWithoutPK = [];
for ($i = 0; $i < $tableCount; $i++) {
    $tableName = $matches[1][$i];
    $tableBody = $matches[2][$i];
    
    // Check if PRIMARY KEY exists in table definition
    if (!preg_match('/PRIMARY KEY/', $tableBody) && !preg_match('/CONSTRAINT.*FOREIGN KEY/', $tableBody)) {
        // Find if there's an auto_increment ID column
        if (preg_match('/`(\w+)`\s+int\(11\)\s+NOT NULL/', $tableBody, $idMatch)) {
            $tablesWithoutPK[] = [
                'table' => $tableName,
                'id_column' => $idMatch[1]
            ];
        }
    }
}

if (!empty($tablesWithoutPK)) {
    echo "\n⚠️  Found " . count($tablesWithoutPK) . " tables without PRIMARY KEY:\n";
    foreach ($tablesWithoutPK as $table) {
        echo "   - {$table['table']} (ID column: {$table['id_column']})\n";
    }
} else {
    echo "\n✅ All tables already have PRIMARY KEY\n";
}

echo "\n🔧 Applying fixes...\n";

// Fix 1: Remove DEFINER clauses
echo "   ✓ Removing DEFINER clauses...\n";
$content = preg_replace('/CREATE\s+DEFINER=`[^`]*`@`[^`]*`\s+/', 'CREATE ', $content);
$content = str_replace('SQL SECURITY DEFINER ', '', $content);

// Fix 2: Add disable checks header
echo "   ✓ Adding disable checks header...\n";
$header = "-- Fixed for DigitalOcean MySQL\n";
$header .= "SET sql_require_primary_key=0;\n"; // Allow creating tables without PK temporarily
$header .= "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;\n";
$header .= "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;\n";
$header .= "SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n";

// Fix 3: Add PRIMARY KEY to tables dynamically
echo "   ✓ Adding PRIMARY KEY to tables without one...\n";
foreach ($tablesWithoutPK as $table) {
    $tableName = $table['table'];
    $idColumn = $table['id_column'];
    
    // Find the CREATE TABLE statement for this table and add PRIMARY KEY
    $pattern = '/(CREATE TABLE `' . preg_quote($tableName) . '`\s*\(.*?`' . preg_quote($idColumn) . '`\s+int\(11\)\s+NOT NULL),/s';
    $replacement = '$1 PRIMARY KEY AUTO_INCREMENT,';
    
    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, $replacement, $content);
        echo "      ✓ Added PRIMARY KEY to $tableName\n";
    }
}

// Fix 4: Fix DEFAULT functions
echo "   ✓ Removing problematic DEFAULT functions...\n";
$content = preg_replace('/DEFAULT\s+(curdate|current_timestamp)\(\)/i', '', $content);
$content = preg_replace('/DEFAULT\s+(CURRENT_DATE|CURRENT_TIMESTAMP)(?!\s+ON UPDATE)/i', '', $content);

// Fix 5: Fix ON UPDATE
echo "   ✓ Fixing ON UPDATE clauses...\n";
$content = str_replace('ON UPDATE current_timestamp()', 'ON UPDATE CURRENT_TIMESTAMP', $content);

// Fix 6: Add restore checks footer
echo "   ✓ Adding restore checks footer...\n";
$footer = "\n-- Restore settings\n";
$footer .= "SET sql_require_primary_key=1;\n";
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

echo "\n✅ Fixed SQL file: $outputFile\n";
echo "\n📝 Ready to import!\n";
