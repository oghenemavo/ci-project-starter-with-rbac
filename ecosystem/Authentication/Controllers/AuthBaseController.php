<?php

namespace Ecosystem\Authentication\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Services;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class AuthBaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['form', 'text', 'url', 'audit'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		$this->session = Services::session();
		$this->validation = Services::validation();

		// custom libraries
		$this->signupLib = Services::signupLib();
		$this->accountLib = Services::accountLib();
		$this->userlib = Services::userlib();
		$this->roleLib = Services::roleLib();
		$this->rbac = Services::rbac();
		$this->permissionsLib = Services::permissionsLib();
	}

	/**
     * load the goto page
     *
     * @param string $namespace         namespace path e.g. 'Ecosystem\\Authenticate\\Views\\pages\\'
     * @param string $page              filename
     * @return string
     */
    protected function _setPagePath(string $namespace, string $page) 
	{
        return $namespace . strtolower($page);
    }

}