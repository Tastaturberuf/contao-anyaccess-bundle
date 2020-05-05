<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Dca;


use Contao\Config;
use Contao\DataContainer;
use Contao\Date;


$GLOBALS['TL_DCA']['tl_ipaccess'] =
[

    'config' =>
    [
        'dataContainer' => 'Table',
        'sql'           =>
        [
            'keys' =>
            [
                'id' => 'primary',
                'ip' => 'index'
            ]
        ]
    ],

    'list' =>
    [
        'sorting' =>
        [
            'mode'        => 2,
            'fields'      => ['ip'],
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ],
        'label' =>
        [
            'showColumns'    => true,
            'fields'         => ['tstamp', 'ip', 'hostname', 'description'],
            'label_callback' => function($row, $label, DataContainer $dc, $args)
            {
                $args[0] = Date::parse(Config::get('datimFormat'), $args[0]);
                $args[1] = long2ip((int) $args[1]);

                return $args;
            }
        ],
        'global_operations' =>
        [
            'update' =>
            [
                'label' => &$GLOBALS['TL_LANG']['tl_ipaccess']['update'],
                'href'  => 'key=update',
                'class' => 'header_sync'
            ],
            'all' =>
            [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations' =>
        [
            'edit' =>
            [
                'label' => &$GLOBALS['TL_LANG']['tl_user']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy' =>
            [
                'label' => &$GLOBALS['TL_LANG']['tl_user']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' =>
            [
                'label'      => &$GLOBALS['TL_LANG']['tl_user']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' =>
            [
                'label' => &$GLOBALS['TL_LANG']['tl_user']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],

    'palettes' =>
    [
        'default' => 'hostname,ip,description'
    ],

    'fields' =>
    [
        'id' =>
        [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' =>
        [
            'label'   => &$GLOBALS['TL_LANG']['tl_ipaccess']['tstamp'],
            'sorting' => true,
            'flag'    => 12,
            'sql'     => "int(10) unsigned NOT NULL default '0'"
        ],
        'hostname' =>
        [
            'label'     => &$GLOBALS['TL_LANG']['tl_ipaccess']['hostname'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      =>
            [
                'unique'    => true,
                'maxlength' => 64,
                'tl_class'  => 'w50'
            ],
            'sql' => "varchar(64) NOT NULL default ''"
        ],
        'ip' =>
        [
            'label'     => &$GLOBALS['TL_LANG']['tl_ipaccess']['ip'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      =>
            [
                'maxlength' => 15,
                'tl_class'  => 'w50'
            ],
            'load_callback' => [function($value)
            {
                return long2ip((int) $value);
            }],
            'save_callback' => [function($value)
            {
                return ip2long($value);
            }],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'description' =>
        [
            'label'     => &$GLOBALS['TL_LANG']['tl_ipaccess']['description'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      =>
            [
                'maxlength' => 128,
                'tl_class'  => 'clr long'
            ],
            'sql' => "varchar(128) NOT NULL default ''"
        ]
    ]

];
