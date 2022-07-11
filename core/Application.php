<?php

namespace Core;

use App\Models\User;
use Core\Error\Error as Error;
use Core\Error\ErrorInterface;
use Core\Request\Request;
use Core\Request\RequestInterface;
use Core\Session\Session;
use Core\Session\SessionInterface;
use Core\Session\SessionManager;
use Dotenv\Dotenv;

class Application
{
    public static string $ROOT_DIR;

    public Router $router;

    public RequestInterface $request;

    public SessionInterface $session;

    public ErrorInterface $error;

    public static User $authUser;

    public function __construct($rootPath = '')
    {
        self::$ROOT_DIR = $rootPath;
        $this->router = new Router();
    }

    public function run()
    {
        $this->loadEnvironment();

        $this->startSession();

        $this->loadRequestClass();

        $this->loadErrorClass();

        $this->loadHelpers();

        $this->getAuthenticatedUser();

        $this->router->reslove($this->request);
    }

    protected function startSession()
    {
        $this->session = SessionManager::initialize();
    }

    protected function loadEnvironment()
    {
        $dotenv = Dotenv::createImmutable(self::$ROOT_DIR);
        $dotenv->load();
    }

    protected function getAuthenticatedUser()
    {
        if(Session::has('_user_id')) {
            self::$authUser = User::where('id', Session::get('_user_id'))->first();
        } else {
            self::$authUser = new User();
        }
    }

    protected function loadRequestClass()
    {
        $this->request = new Request();

        $this->request->clearOldInputs();
    }

    protected function loadErrorClass()
    {
        $this->error = new Error();
        $this->error->clear();
    }

    protected function loadHelpers()
    {
        foreach (glob(self::$ROOT_DIR . '/core/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}