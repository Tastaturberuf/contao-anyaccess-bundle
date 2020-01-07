<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */

declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Manager;


use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Tastaturberuf\AnyAccessBundle\AnyAccessBundle;


class Plugin implements BundlePluginInterface
{

    /**
     * @inheritDoc
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(AnyAccessBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

}