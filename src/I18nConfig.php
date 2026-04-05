<?php

declare(strict_types=1);

namespace PHPdot\I18n;

final readonly class I18nConfig
{
    /**
     * @param string $default Default language code
     * @param list<string> $supported Supported language codes
     * @param string $path Base path to translation files
     * @param int $ttl Cache TTL in seconds
     */
    public function __construct(
        public string $default = 'en',
        public array $supported = ['en'],
        public string $path = '',
        public int $ttl = 3600,
    ) {
    }
}
