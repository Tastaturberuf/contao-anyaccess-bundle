<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\DataContainer;


use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Hook;


class PageDataContainer
{

    /**
     * @Hook("loadDataContainer")
     */
    public function loadDataContainer(string $table): void
    {
        if ('tl_page' !== $table)
        {
            return;
        }

        $GLOBALS['TL_DCA'][$table] = array_replace_recursive(
        [
            'fields' =>
            [
                'anyaccess_enable' =>
                [
                    'exclude'   => true,
                    'inputType' => 'checkbox',
                    'eval'      =>
                    [
                        'submitOnChange' => true,
                        'tl_class'       => 'w50 m12'
                    ],
                    'sql' => "tinyint(1) NOT NULL default '0'"
                ],
                'anyaccess_cookie_support' =>
                [
                    'inputType' => 'checkbox',
                    'eval'      =>
                    [
                        'tl_class' => 'clr w50'
                    ],
                    'sql' => "tinyint(1) NOT NULL default '0'"
                ],
                'anyaccess_cookie_lifetime' =>
                [
                    'inputType' => 'text',
                    'eval'      =>
                    [
                        'mandatory' => true,
                        'tl_class'  => 'w50'
                    ],
                    'sql' => "int(10) unsigned NOT NULL default '86400'"
                ]
            ]
        ], $GLOBALS['TL_DCA'][$table]);

        PaletteManipulator::create()
            ->addLegend('anyaccess_legend', null, 'append', true)
            ->addField(['anyaccess_enable', 'anyaccess_cookie_support', 'anyaccess_cookie_lifetime'], 'anyaccess_legend')
            ->applyToPalette('root', $table)
            ->applyToPalette('rootfallback', $table)
        ;
    }

}
