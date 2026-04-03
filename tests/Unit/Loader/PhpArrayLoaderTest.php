<?php

declare(strict_types=1);

namespace PHPdot\I18n\Tests\Unit\Loader;

use PHPdot\I18n\Loader\PhpArrayLoader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhpArrayLoaderTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        $this->basePath = __DIR__ . '/../../Fixtures/lang';
    }

    #[Test]
    public function loadsEnglishMessagesWithPrefixedKeys(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        self::assertArrayHasKey('messages.welcome', $translations);
        self::assertSame('Welcome, {name}!', $translations['messages.welcome']);
        self::assertArrayHasKey('messages.goodbye', $translations);
    }

    #[Test]
    public function loadsEnglishErrorsWithPrefixedKeys(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        self::assertArrayHasKey('errors.not_found', $translations);
        self::assertSame('Page not found', $translations['errors.not_found']);
        self::assertArrayHasKey('errors.forbidden', $translations);
    }

    #[Test]
    public function loadsJsTranslationsWithDottedKeys(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        self::assertArrayHasKey('js.buttons.save', $translations);
        self::assertSame('Save', $translations['js.buttons.save']);
        self::assertArrayHasKey('js.errors.required', $translations);
    }

    #[Test]
    public function mergesMultipleFilesForOneLanguage(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        self::assertArrayHasKey('messages.welcome', $translations);
        self::assertArrayHasKey('errors.not_found', $translations);
        self::assertArrayHasKey('js.buttons.save', $translations);
    }

    #[Test]
    public function loadsArabicTranslations(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('ar');

        self::assertArrayHasKey('messages.welcome', $translations);
        self::assertSame('مرحباً {name}!', $translations['messages.welcome']);
    }

    #[Test]
    public function arabicHasFewerKeysThanEnglish(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $en = $loader->loadAll('en');
        $ar = $loader->loadAll('ar');

        self::assertGreaterThan(count($ar), count($en));
    }

    #[Test]
    public function returnsEmptyForNonExistentLanguage(): void
    {
        $loader = new PhpArrayLoader($this->basePath);

        self::assertSame([], $loader->loadAll('fr'));
    }

    #[Test]
    public function returnsEmptyForNonExistentBasePath(): void
    {
        $loader = new PhpArrayLoader('/non/existent/path');

        self::assertSame([], $loader->loadAll('en'));
    }

    #[Test]
    public function keysAreSorted(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        $keys = array_keys($translations);
        $sorted = $keys;
        sort($sorted);

        self::assertSame($sorted, $keys);
    }

    #[Test]
    public function allValuesAreStrings(): void
    {
        $loader = new PhpArrayLoader($this->basePath);
        $translations = $loader->loadAll('en');

        foreach ($translations as $key => $value) {
            self::assertIsString($value, "Value for key '{$key}' should be string");
        }
    }

    #[Test]
    public function skipsFileThatDoesNotReturnArray(): void
    {
        $loader = new PhpArrayLoader(__DIR__ . '/../../Fixtures/lang_bad');
        $translations = $loader->loadAll('en');

        // valid.php returns array, bad_return.php returns null — should be skipped
        self::assertArrayHasKey('valid.hello', $translations);
        self::assertSame('Hello!', $translations['valid.hello']);
        // No crash from bad_return.php
    }
}
