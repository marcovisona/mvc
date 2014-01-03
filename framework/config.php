<?php

define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);

/**
*
*/
class Config
{

    const SQL_SERVER = "localhost";
    const SQL_USER = "root";
    const SQL_PASSWORD = "root";
    const SQL_DATABASE ="classicmodels";

    private static $_classesDir = array(
        'controllers' => '../controllers/',
        'models'=> '../models/',
        'views'=>'../views/',
        'helpers' => '../framework/helpers/',
        'templates' => '../templates/',
        'base' => '../');

    //per ogni template definisco una lista di viste da caricare nell'ordine
    private static $_templates = array(
        'template1' => array('header', '!content', 'footer'),
        'template2'=> array('header2', '!content', 'footer2'));

    public static function templates()
    {
        return Config::$_templates;
    }

    public static function classesDir()
    {
        return Config::$_classesDir;
    }

    public static function templatesDir()
    {
        $base = str_replace("index.php", "", BASE_URL);

        return $base."templates/";
    }
}
