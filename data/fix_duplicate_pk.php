<?php
/**
 * Remove duplicate PRIMARY KEY from ALTER TABLE statements
 * Since we added PRIMARY KEY in CREATE TABLE, we must remove it from ALTER TABLE
 */

$file = __DIR__ . '/choviet29_fixed.sql';
$content = file_get_contents($file);

echo "🔧 Removing duplicate PRIMARY KEY from ALTER TABLE statements...\n";

// Split by ALTER TABLE statements
$lines = explode("\n", $content);
$result = [];
$inAlterTable = false;

foreach ($lines as $line) {
    // Check if this is an ALTER TABLE line
    if (preg_match('/^ALTER TABLE/', $line)) {
        $inAlterTable = true;
        $result[] = $line;
    }
    // Skip "ADD PRIMARY KEY (`id`)" and similar lines within ALTER TABLE
    elseif ($inAlterTable && preg_match('/^\s*ADD PRIMARY KEY\s*\(\s*`\w+`\s*\)\s*,?\s*$/', $line)) {
        echo "  Removing: $line\n";
        // Skip this line (don't add to result)
        continue;
    }
    // Check if we're exiting ALTER TABLE section (empty line or new comment)
    elseif ($inAlterTable && (trim($line) === '' || preg_match('/^--/', $line))) {
        $inAlterTable = false;
        $result[] = $line;
    }
    // Keep all other lines
    else {
        $result[] = $line;
    }
}

$newContent = implode("\n", $result);
file_put_contents($file, $newContent);

echo "✅ Done! Removed duplicate PRIMARY KEY definitions from ALTER TABLE statements.\n";
echo "📝 File size before: " . strlen($content) . " bytes\n";
echo "📝 File size after: " . strlen($newContent) . " bytes\n";
?>
