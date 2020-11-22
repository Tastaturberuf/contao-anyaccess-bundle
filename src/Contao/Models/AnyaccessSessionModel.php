<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Models;


use Contao\Model;


class AnyaccessSessionModel extends Model
{

    public const TABLE = 'tl_anyaccess_session';

    public static $strTable = self::TABLE;

}
