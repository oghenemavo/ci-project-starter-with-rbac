<?php

namespace Ecosystem\Authentication\Filters;

use CodeIgniter\HTTP\{RequestInterface, ResponseInterface};
use CodeIgniter\Filters\FilterInterface;
use Ecosystem\Authentication\Libraries\LoginLib;

class AuthorizedUser implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        // Do something here
        $userlib = service('userlib');
        $loginLib = new LoginLib(\Config\Services::request());

        $user = $userlib->get_user(); // get user
        if ($userlib->is_user_signed_in()) {
            if (!isset($user->role_id)) {
                return $loginLib->logout();
            }
        }
        return $user;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do something here
    }

}