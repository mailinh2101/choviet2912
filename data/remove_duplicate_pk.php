<?php
/**
 * Remove duplicate PRIMARY KEY definitions (from ALTER TABLE since we added them in CREATE TABLE)
 */

$file = __DIR__ . '/choviet29_fixed.sql';
$content = file_get_contents($file);

echo "🔧 Removing duplicate PRIMARY KEY definitions...\n";

// Remove lines like:
//   ADD PRIMARY KEY (`id`),
// From ALTER TABLE statements
$content = preg_replace('/\s*ADD PRIMARY KEY \(`\w+`\),?\n/', '', $content);

file_put_contents($file, $content);

echo "✅ Done! Removed duplicate PRIMARY KEY definitions from ALTER TABLE statements.\n";
