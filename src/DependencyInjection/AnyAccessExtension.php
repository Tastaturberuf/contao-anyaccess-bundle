<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */

declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class AnyAccessExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        static $files = [
            'services.yaml'
        ];

        foreach ($files as $file) {
            $loader->load($file);
        }
    }

}
