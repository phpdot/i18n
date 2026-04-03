<?php

declare(strict_types=1);

/**
 * @author Omar Hamdan <omar@phpdot.com>
 * @license MIT
 */

namespace PHPdot\I18n\Loader;

final class PhpArrayLoader implements LoaderInterface
{
    /**
     * @param string $basePath Base directory containing language subdirectories
     */
    public function __construct(
        private readonly string $basePath,
    ) {}

    /**
     * Load all translations for a language from PHP array files.
     *
     * Scans `$basePath/$language/*.php`. Each file must return `array<string, string>`.
     * Keys are prefixed by filename without extension (e.g. `messages.welcome`).
     *
     * @return array<string, string> Flat key => ICU template map
     */
    public function loadAll(string $language): array
    {
        $directory = $this->basePath . '/' . $language;

        if (!is_dir($directory)) {
            return [];
        }

        $translations = [];
        $files = glob($directory . '/*.php');

        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            $prefix = basename($file, '.php');

            /** @var array<string, string> $entries */
            $entries = require $file;

            foreach ($entries as $key => $value) {
                $translations[$prefix . '.' . $key] = $value;
            }
        }

        ksort($translations);

        return $translations;
    }
}
