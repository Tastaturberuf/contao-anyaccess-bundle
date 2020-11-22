<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\EventListener;


use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Database;
use Contao\Environment;
use Contao\PageModel;
use Contao\System;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessHostModel;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessSessionModel;


/**
 * @Hook("loadPageDetails")
 */
class LoadPageDetailsListener
{

    public function __invoke(array $parentModels, PageModel $page): void
    {
        // return if there is no root page
        if ( 0 === count($parentModels) )
        {
            return;
        }

        $rootPage = end($parentModels);

        // get remote ip
        $ip = Environment::get('remoteAddr');

        // return if ip access is not enabled or script runs on cli or on localhost
        if ( !$rootPage->anyaccess_enable || PHP_SAPI === 'cli' || in_array($ip, ['127.0.0.1', '::1']) )
        {
            return;
        }


        $this->clearDatabase((int) $rootPage->anyaccess_cookie_lifetime);

        // count ip in database
        $result = AnyaccessHostModel::countBy('ip', ip2long($ip));


        if ( $result )
        {
            if ( $rootPage->anyaccess_cookie_support )
            {
                $this->saveSession((int) $rootPage->anyaccess_cookie_lifetime);
            }

            return;
        }
        else
        {
            if ( $_COOKIE['anyAccess'] )
            {
                // look for valid session
                $result = Database::getInstance()
                    ->prepare("SELECT * FROM tl_anyaccess_session WHERE session=? AND tstamp>=?")
                    ->execute($_COOKIE['anyAccess'], time() - $rootPage->anyaccess_cookie_lifetime);

                if ( $result->numRows )
                {
                    return;
                }
            }
        }

        $this->blockClient();
    }


    protected function clearDatabase(int $cookieLifetime): void
    {
        Database::getInstance()
            ->prepare("DELETE FROM tl_anyaccess_session WHERE tstamp<?")
            ->execute(time() - $cookieLifetime);
    }


    protected function setCookie(int $cookieLifetime): void
    {
        Controller::setCookie('anyAccess', session_id(), time() + $cookieLifetime, '/');
    }


    protected function saveSession(int $cookieLifetime): void
    {
        $this->setCookie($cookieLifetime);

        if ( ($model = AnyaccessSessionModel::findOneBy('session', session_id())) === null )
        {
            $model = new AnyaccessSessionModel();
        }

        $model->tstamp    = time();
        $model->session   = session_id();
        $model->ip        = Environment::get('remoteAddr');
        $model->useragent = Environment::get('httpUserAgent');
        $model->save();
    }


    protected function blockClient()
    {
        $msg = sprintf("Blocked access for '%s'", Environment::get('remoteAddr'));

        System::log($msg, __METHOD__, TL_ERROR);

        die($msg);
    }

}
