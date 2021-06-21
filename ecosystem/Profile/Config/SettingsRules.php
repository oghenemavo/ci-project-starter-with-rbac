<?php

namespace Ecosystem\Profile\Config;

use Config\Services;

class SettingsRules
{
	/**
     * Check current user password
     *
     * @param string $password
     * @param string $error
     * @return boolean
     */
    public function checkPassword(string $password, string &$error = null): bool {
        $user = service('userlib')->get_user();
        
        if(!password_verify($password, $user->getUserPassword())) {
            $error = 'The is not the current password';
            return false;
        }
        return true;
    }
}
