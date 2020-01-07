<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao\Hooks;


use Contao\Config;
use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\Frontend;
use Contao\PageError403;
use Contao\System;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessHostModel;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessSessionModel;


class GlobalIpAccess
{

    function initializeSystem()
    {
        // return if ip access is not enabled or script runs on cli
        if ( !Config::get('enableGlobalIpAccess') || PHP_SAPI === 'cli' )
        {
            return;
        }


        $this->clearDatabase();

        // get remote ip
        $ip = Environment::get('remoteAddr');

        // count ip in database
        $result = AnyaccessHostModel::countBy('ip', ip2long($ip));


        if ( $result )
        {
            if ( Config::get('enableCookieSupport') )
            {
                $this->saveSession();
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
                    ->execute($_COOKIE['anyAccess'], time() - Config::get('cookieSessionTime'));

                if ( $result->numRows )
                {
                    return;
                }
            }
        }

        $this->blockClient();
    }


    protected function clearDatabase()
    {
        Database::getInstance()
            ->prepare("DELETE FROM tl_anyaccess_session WHERE tstamp<?")
            ->execute(time() - Config::get("cookieSessionTime"));
    }


    protected function setCookie()
    {
        Controller::setCookie('anyAccess', session_id(), time() + Config::get('cookieSessionTime'), '/');
    }


    protected function saveSession()
    {
        $this->setCookie();

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
        System::log("Blocked access for '".Environment::get('remoteAddr')."'", __METHOD__, TL_ERROR);

        // show error page
        /** @var PageError403 $objPage */
        $objPage = new $GLOBALS['TL_PTY']['error_403']();
        $objPage->generate(Frontend::getPageIdFromUrl());
    }

}
