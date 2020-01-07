<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Dca;


$GLOBALS['TL_DCA']['tl_anyaccess_session'] =
[

    'config' =>
    [
        'sql' =>
        [
            'keys' =>
            [
                'id'      => 'primary',
                'session' => 'unique'
            ]
        ]
    ],


    'fields' =>
    [
        'id' =>
        [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' =>
        [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'session' =>
        [
            'sql' => "varchar(128) NOT NULL default ''"
        ],
        'ip' =>
        [
            'sql' => "varchar(64) NOT NULL default ''"
        ],
        'useragent' =>
        [
            'sql' => "varchar(255) NOT NULL default ''"
        ]
    ]

];
