<?php

require_once '../framework/config.php';
require_once '../framework/loader.php';
require_once '../framework/controller.php';
require_once '../framework/view.php';
require_once '../framework/clienthandler.php';
require_once '../framework/classloader.php';
require_once '../framework/relation_cycle_detector.php';

function toSeparatedString($array, $separator=', ', $before='', $after='')
{
    $str = "";
    for ($i=0; $i < count($array); $i++) {
        $str .= $array[$i];
        if ($i < count($array)-1) {
            $str .= $separator;
        }
    }

    return $before . $str . $after;
}

/**
*
*/
class Dispatcher
{
    private static $instance;
    private $load;
    private $currentController;

    private function __construct()
    {
        $this->load = new Loader();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }

    private function _cleanArray(&$array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (trim($value) == "") {
                    unset($array[$key]);
                }
            }
        }
    }

    public function url($controller,$action='index', $params=array())
    {
        $url="";
        if ($action == "index" && count($params) == 0) {
            $url="$controller";
        } elseif (count($params)==0) {
            $url="$controller/$action";
        } else {
            $url="$controller/$action";
            foreach ($params as $value) {
                $url .= "/$value";
            }
        }

        return "/".$url;
    }

    public function dispatch()
    {
        // $path = substr($_SERVER['REQUEST_URI'], strpos ($_SERVER['REQUEST_URI'], 'index.php'));
        $path = $_SERVER['QUERY_STRING'];
        $exploded_path = explode('/',$path);
        $this->_cleanArray($exploded_path);

        $params=$_POST;
        $controller="";
        $action="";

        if (count($exploded_path)==0) {
            //redirect to home
            $controller='home';
            $action='index';
        } elseif (count($exploded_path)==1) {
            $controller=$exploded_path[1];
            $action="index";
        } else {
            $controller=$exploded_path[1];
            $action=$exploded_path[2];
            for ($i=3; $i < count($exploded_path); $i++) {
                $params[] = $exploded_path[$i];
            }
        }

        $this->currentController = &$this->load->controller($controller, $action, $params);

        $parStr="";
        $i=0;
        var_dump($params);
        foreach ($params as $key => $value) {
            $parStr .= "'" . $params[$i] . "', ";
            $i++;
        }
        $parStr = substr($parStr, 0, strlen($parStr)-2);

        eval("\$this->currentController->$action($parStr);");

        foreach ($this->currentController->views as $viewkey => $view) {
            eval("\$view->show();");
        }
    }

    public function &getCurrentController()
    {
        return $this->currentController;
    }

}
