<?php

class CustomersController extends Controller
{
    public $controller = "Customers";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $m = "order";
        $m = "customer";
        $this->load->model($m);
        $data['title']="Customers Orders";
        $data[$m] = $this->$m->fetch();

        $data['modelName'] = $m;

        $this->load->view('vista', 'template1', $data);

    }
}
