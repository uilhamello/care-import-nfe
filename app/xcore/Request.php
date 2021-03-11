<?php

namespace Core;

use Core\Env;
use Core\Session;
use App\Controllers;

class Request
{
    private $env;
    private $session;
    private $fullRoute;

    public function __construct()
    {
        $this->session = new Session();
        $this->session->delete('_env_');

        $this->env = new Env();
    }

    public function load()
    {
        $base_controller = "App\Controllers\\";
        $route = $this->env->get('route');

        if ($this->route_exist()) {
            list($class, $method) =  explode('@', ($route[$this->user_identify()][$this->getFullRoute()][$_SERVER['REQUEST_METHOD']]));
            $class = $base_controller . $class;
            if (class_exists($class)) {
                $instance = new $class();
                if (method_exists($instance, $method)) {
                    $instance->$method();
                } else {
                    die("Fatal Error: Method Not Exist");
                }
            } else {
                die("Fatal Eror: Controller Not Exist");
            }
        } else {
            die("Eror 404: Route Not Found");
        }
    }

    public function user_identify()
    {
        $logged = $this->session->logged();
        return ($logged) ? 'auth' : 'anonymous';
    }

    public function route_exist()
    {
        $loggin = $this->user_identify();

        $route = $this->env->get('route');

        $app_root = str_replace('/index.php', '/', str_replace('first/', '', 'first' . $_SERVER['SCRIPT_NAME']));

        $want = array_values(array_filter(explode('/', str_replace($app_root, '', $_SERVER['REQUEST_URI']))));
        if (count($want) < 1) {
            return false;
        }
        $routeRequest = $want[0];
        unset($want[0]);
        $routeGet = array_values($want);

        $routeKeys = array_keys($route[$loggin]);
        $routeValid = true;

        foreach ($routeKeys as $val) {
            $routeName = explode('/', $val);
            if ($routeName[0] != $routeRequest) {
                continue;
            }
            //route
            $fullRoute = $routeName[0];
            unset($routeName[0]);
            //routeParameters recive GET RESQUEST PARAMETER (/{id}) if it exist
            $routeParameters = array_values($routeName);
            if (count($routeParameters) != count($routeGet)) {
                continue;
            }
            for ($i = 0; $i <= count($routeParameters); $i++) {

                if (isset($routeParameters[$i])) {
                    if (strpos($routeParameters[$i], '{') !== false) {
                        preg_match('~{([^{]*)}~i', $routeParameters[$i], $match);
                        $_GET[$match[1]] = $routeGet[$i];
                    } else {
                        if ($routeParameters[$i] != $routeGet[$i]) {
                            $routeValid = false;
                            unset($_GET);
                            continue;
                        }
                    }
                    if (!empty($routeGet[$i])) {
                        $fullRoute .= "/" . $routeParameters[$i];
                    }
                }
            }
            $this->setFullRoute($fullRoute);
            return true;
        }
        return false;
    }

    /**
     * Get the value of fullRoute
     */
    public function getFullRoute()
    {
        return $this->fullRoute;
    }

    /**
     * Set the value of fullRoute
     *
     * @return  self
     */
    public function setFullRoute($fullRoute)
    {
        $this->fullRoute = $fullRoute;

        return $this;
    }
}
