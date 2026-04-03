<?php

declare(strict_types=1);

/**
 * @author Omar Hamdan <omar@phpdot.com>
 * @license MIT
 */

namespace PHPdot\I18n\Loader;

final class ChainLoader implements LoaderInterface
{
    /**
     * @param list<LoaderInterface> $loaders Ordered list of loaders; last wins for duplicate keys
     */
    public function __construct(
        private readonly array $loaders,
    ) {}

    /**
     * Load all translations by merging results from every loader.
     *
     * Later loaders overwrite earlier ones for the same key.
     *
     * @return array<string, string> Flat key => ICU template map
     */
    public function loadAll(string $language): array
    {
        $translations = [];

        foreach ($this->loaders as $loader) {
            $translations = array_merge($translations, $loader->loadAll($language));
        }

        return $translations;
    }
}
