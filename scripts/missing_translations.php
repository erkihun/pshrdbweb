<?php

use Symfony\Component\Finder\Finder;

require __DIR__ . '/../vendor/autoload.php';

function flatten(array $array, string $prefix = ''): array
{
    $result = [];

    foreach ($array as $key => $value) {
        $fullKey = $prefix === '' ? $key : "{$prefix}.{$key}";

        if (is_array($value)) {
            $result = array_merge($result, flatten($value, $fullKey));
            continue;
        }

        $result[$fullKey] = true;
    }

    return $result;
}

function collectKeys(string $locale): array
{
    $finder = new Finder();
    $finder->files()->in(__DIR__ . "/../lang/{$locale}")->name('*.php');

    $keys = [];

    foreach ($finder as $file) {
        /** @var string $path */
        $path = $file->getRealPath();
        $translations = include $path;
        $keys = array_merge($keys, flatten($translations));
    }

    return $keys;
}

$enKeys = collectKeys('en');
$languages = ['am'];

foreach ($languages as $language) {
    $missing = array_diff_key($enKeys, collectKeys($language));

    if ($missing === []) {
        printf("No missing keys for %s\n", $language);
        continue;
    }

    printf("Missing keys for %s:\n", $language);
    foreach ($missing as $key => $_) {
        echo "- {$key}\n";
    }
}
