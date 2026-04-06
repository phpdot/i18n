<?php

declare(strict_types=1);

/**
 * @author Omar Hamdan <omar@phpdot.com>
 * @license MIT
 */

namespace PHPdot\I18n\Loader;

use PHPdot\I18n\I18nConfig;

final class JsonLoader implements LoaderInterface
{
    public function __construct(
        private readonly I18nConfig $config,
    ) {
    }

    /**
     * Load all translations for a language from JSON files.
     *
     * Scans `$basePath/$language/*.json`. Each file must contain a flat JSON object.
     * Keys are prefixed by filename without extension (e.g. `messages.welcome`).
     *
     * @return array<string, string> Flat key => ICU template map
     */
    public function loadAll(string $language): array
    {
        $directory = $this->config->path . '/' . $language;

        if (!is_dir($directory)) {
            return [];
        }

        $translations = [];
        $files = glob($directory . '/*.json');

        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            $prefix = basename($file, '.json');
            $contents = file_get_contents($file);

            if ($contents === false) {
                continue;
            }

            /** @var array<string, string> $entries */
            $entries = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            foreach ($entries as $key => $value) {
                $translations[$prefix . '.' . $key] = $value;
            }
        }

        ksort($translations);

        return $translations;
    }
}
