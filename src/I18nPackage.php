<?php

declare(strict_types=1);

namespace PHPdot\I18n;

use PHPdot\Container\ContainerBuilder;
use PHPdot\I18n\Loader\LoaderInterface;
use PHPdot\I18n\Loader\PhpArrayLoader;
use PHPdot\Package\PackageInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

use function PHPdot\Container\scoped;
use function PHPdot\Container\singleton;

final class I18nPackage implements PackageInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            I18nConfig::class => singleton(static fn (): I18nConfig => new I18nConfig()),

            LoaderInterface::class => singleton(static function (ContainerInterface $c): LoaderInterface {
                $config = $c->get(I18nConfig::class);
                assert($config instanceof I18nConfig);

                return new PhpArrayLoader($config->path);
            }),

            ICUValidator::class => singleton(),

            Translator::class => scoped(static function (ContainerInterface $c): Translator {
                $config = $c->get(I18nConfig::class);
                assert($config instanceof I18nConfig);

                $loader = $c->get(LoaderInterface::class);
                assert($loader instanceof LoaderInterface);

                $cache = $c->get(CacheInterface::class);
                assert($cache instanceof CacheInterface);

                return new Translator(
                    loader: $loader,
                    cache: $cache,
                    default: $config->default,
                    supported: $config->supported,
                    ttl: $config->ttl,
                );
            }),
        ]);
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
