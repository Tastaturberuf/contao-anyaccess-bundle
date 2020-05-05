<?php // with ♥ and Contao

/**
 * AnyAccessBundle for Contao Open Source CMS
 * @copyright   (c) 2015–2020 Tastaturberuf <tastaturberuf.de>
 * @author      Daniel Jahnsmüller <code@tastaturberuf.de>
 * @license     LGPL-3.0
 */


declare(strict_types=1);


namespace Tastaturberuf\AnyAccessBundle\Contao;


use Contao\Controller;
use Contao\System;
use Tastaturberuf\AnyAccessBundle\Contao\Models\AnyaccessHostModel;


class Anyaccess
{

    public function updateAllIps()
    {
        $objHostnames = AnyaccessHostModel::findAll();

        if ( $objHostnames == null )
        {
            return;
        }

        foreach ( $objHostnames as $objHostname )
        {
            $this->updateIp($objHostname);
        }

        #\System::log("Updated all IPs", __METHOD__, TL_CRON);


        // reload page in backend
        if ( TL_MODE === 'BE' )
        {
            Controller::redirect(Controller::getReferer());
        }
    }


    protected function updateIp(AnyaccessHostModel $objHostname)
    {
        if ( $objHostname->hostname == '' )
        {
            return;
        }

        $strIp = gethostbyname($objHostname->hostname);

        if ( $this->validateIp($strIp) )
        {
            $oldIp = long2ip((int) $objHostname->ip);

            if ( $objHostname->ip != ip2long($strIp) )
            {
                $objHostname->tstamp = time();
                $objHostname->ip     = ip2long($strIp);
                $objHostname->save();

                System::log(sprintf('Update %s (%s &rarr; %s)', $objHostname->hostname, $oldIp, $strIp), __METHOD__, TL_CRON);
            }
        }
        else
        {
            System::log("Can't resolve hostname '{$objHostname->hostname}'", __METHOD__, TL_ERROR);
        }
    }


    /**
     * @param string $strIp
     * @return bool
     */
    protected function validateIp($strIp)
    {
        return (boolean) filter_var($strIp, FILTER_VALIDATE_IP);
    }

}