<?php

namespace Ecosystem\Authentication\Config;

use CodeIgniter\Config\BaseService;
use Ecosystem\Authentication\Libraries\{
    UserLib, RoleLib, AccountVerificationLib, AlertLib, MailerLib, CIMailerLib,
    MailTemplateLib,
    Rbac
};

class Services extends BaseService
{
    /**
     * AlertLib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function alertlib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('alertlib');
        }
        return new AlertLib();
    }

    /**
     * MailTemplateLib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function mailTemplateLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('mailTemplateLib');
        }
        return new MailTemplateLib();
    }

    /**
     * Role Base Access Control Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function rbac($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('rbac');
        }
        return new Rbac();
    }

    /**
     * Role Lib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function roleLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('roleLib');
        }
        return new RoleLib();
    }

    /**
     * User Lib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function userlib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('userlib');
        }
        return new UserLib(\Config\Services::request());
    }

    /**
     * Account Verification Lib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function accountLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('accountLib');
        }
        return new AccountVerificationLib();
    }

    /**
     * Mail Dispatch Lib Service
     *
     * @param boolean $getShared
     * @return void
     */
    public static function mailerLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('mailerLib');
        }
        return new MailerLib();
    }

    /**
     * CIMailer Lib Service
     *
     * @param boolean $getShared
     * @return object Ecosystem\Authentication\Libraries\MailerLib 
     */
    public static function ciMailerLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('ciMailerLib');
        }
        return new CIMailerLib();
    }
    
}
