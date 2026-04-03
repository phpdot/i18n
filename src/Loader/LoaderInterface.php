<?php

declare(strict_types=1);

/**
 * @author Omar Hamdan <omar@phpdot.com>
 * @license MIT
 */

namespace PHPdot\I18n\Loader;

interface LoaderInterface
{
    /**
     * Load all translations for a language.
     *
     * @return array<string, string> Flat key => ICU template map (e.g. 'messages.welcome' => 'Welcome, {name}!')
     */
    public function loadAll(string $language): array;
}
