<?php

namespace Core;

use App\Models\User;
use Core\Request\Request;
use Core\Session\Session;
use Core\Session\SessionManager;
use Dotenv\Dotenv;

class Application
{
    public static string $ROOT_DIR;

    public Router $router;

    public Request $request;

    public static User $authUser;

    public function __construct($rootPath = '')
    {
        self::$ROOT_DIR = $rootPath;
        $this->request = new Request();
        $this->router = new Router();
    }

    public function run()
    {
        $this->loadEnvironment();

        $this->startSession();

        $this->getAuthenticatedUser();

        $this->router->reslove($this->request);
    }

    protected function startSession()
    {
        SessionManager::initialize();
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
}