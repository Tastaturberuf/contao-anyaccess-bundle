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
use Contao\System;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessHostModel;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessSessionModel;


class GlobalIpAccess
{

    function initializeSystem()
    {
        // get remote ip
        $ip = Environment::get('remoteAddr');

        // return if ip access is not enabled or script runs on cli or on localhost
        if ( !Config::get('enableGlobalIpAccess') || PHP_SAPI === 'cli' || in_array($ip, ['127.0.0.1', '::1']) )
        {
            return;
        }


        $this->clearDatabase();

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
        $text = sprintf("Blocked access for '%s'", Environment::get('remoteAddr'));

        System::log($text, __METHOD__, TL_ERROR);

        die($text);
    }

}
