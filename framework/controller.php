<?php

require_once 'loader.php';

class Controller
{
    protected $load;
    protected $model;
    protected $uses;
    public $views=array();

    public $controller = "Controller";

    public function __construct()
    {
        $this->load = new Loader();
    }

    public function index()
    {
        echo "<p>Please implement public function index() in your Controller subclass.</p>";
    }

}
