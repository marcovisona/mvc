<?php
/**
 * index.php
 *
 * @package Mvc
 * @author  Marco Visonà <marco@interno27.it>
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../framework/dispatcher.php';

function dump($var)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

$d = Dispatcher::getInstance();

$d->dispatch();
