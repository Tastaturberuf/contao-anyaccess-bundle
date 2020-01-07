<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Dca;


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'enableGlobalIpAccess';

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{anyaccess_legend},enableGlobalIpAccess';

$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['enableGlobalIpAccess'] = 'enableCookieSupport,cookieSessionTime';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['enableGlobalIpAccess'] =
[
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['enableGlobalIpAccess'],
    'inputType' => 'checkbox',
    'eval'      =>
    [
        'submitOnChange' => true,
        'tl_class'       => 'w50 m12'
    ]
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['enableCookieSupport'] =
[
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['enableCookieSupport'],
    'inputType' => 'checkbox',
    'eval'      =>
    [
        'tl_class' => 'clr w50'
    ]
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['cookieSessionTime'] =
[
    'label'     => $GLOBALS['TL_LANG']['tl_settings']['cookieSessionTime'],
    'inputType' => 'text',
    'eval'      =>
    [
        'mandatory' => true,
        'tl_class'  => 'w50'
    ]
];
