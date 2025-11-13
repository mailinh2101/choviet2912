<?php
/**
 * Remove duplicate PRIMARY KEY definitions from ALTER TABLE statements
 */

$inputFile = __DIR__ . '/choviet29_fixed.sql';
$outputFile = __DIR__ . '/choviet29_fixed.sql';

if (!file_exists($inputFile)) {
    die("❌ Error: Input file not found\n");
}

echo "🔧 Removing duplicate PRIMARY KEY definitions...\n";

$content = file_get_contents($inputFile);

// Pattern: ALTER TABLE followed by ADD PRIMARY KEY
// We want to remove lines like: "  ADD PRIMARY KEY (`id`),"
// while keeping other ADD KEY lines

// Find all ALTER TABLE blocks and remove the ADD PRIMARY KEY line
$content = preg_replace(
    '/ALTER TABLE `(\w+)`\s+\n\s+ADD PRIMARY KEY \(`\w+`\),/',
    'ALTER TABLE `$1`',
    $content
);

// Also handle cases where there's just one comma after
$content = preg_replace(
    '/\n\s+ADD PRIMARY KEY \(`\w+`\),\n/',
    "\n",
    $content
);

// Handle case where it's the first item (no comma before)
$content = preg_replace(
    '/ADD PRIMARY KEY \(`\w+`\),\s+ADD KEY/',
    'ADD KEY',
    $content
);

file_put_contents($outputFile, $content);

echo "✅ Done! Duplicate PRIMARY KEY definitions removed.\n";
