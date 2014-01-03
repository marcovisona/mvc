<?php
/**
*
*/
class Classloader
{
    private static $instance;

    private function __construct()
    {
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

    public function loadClass($className)
    {
        $classesDir = Config::classesDir();
        $underscored = $this->camelToUnderscore($className);
        $elements = explode("_", $underscored);
        $classType = $elements[count($elements)-1];

        if ($classesDir[strtolower($classType)."s"] != null) {
            $file= $classesDir[strtolower($classType)."s"] . $underscored . '.php';
        } else {
            $file= $classesDir['base'] . $className . '.php';
        }

        if (file_exists($file)) {
            require_once ($file);
        }

    }

    private function underscoreToCamel($str, $firstUpper=false)
    {
        $camel="";
        foreach (explode('_',$str) as $value) {
             $camel .= ucfirst($value);
         } ;

         return $firstUpper ? $camel : lcfirst($camel);
    }

    private function camelToUnderscore($str)
    {
        $str = lcfirst($str);
        $underscore ="";

        $components = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$str);
        foreach (explode(' ', $components) as $key => $value) {

            $underscore .= lcfirst($value) . "_";
        }
        $underscore = substr($underscore, 0, -1);

        return $underscore;
    }
}
