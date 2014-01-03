<?php

class ProductLinesController extends Controller
{
    protected $uses = array('jquery');
    public $controller = "ProductLines";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('productLine');
        $data['title']="Titolo della pagina";
        $data['productLine'] = $this->productLine->fetch();
        $data['modelName'] = 'productLine';

         $this->load->view('vista','template1', $data);

         $params['ciao1']="aa";
         $params['ciao2']="bb";
         $this->vista->attachHandler("a#link", "click", array('controller' => $this->controller, 'action' => 'buttonClickedServer'), new ProductLinesCH(), $params);

    }

    public function add($line, $text)
    {
        $this->load->model('productLine');
        $nr = $this->productLine->newRow(true);
        $nr['productLine'] = $line;
        $nr['textDescription'] = $text;
        $this->productLine->insert($nr);
    }

    public function update($line, $text)
    {
        $this->load->model('productLine');
        $values['textDescription']=$text;
        $this->productLine->update($values,"productLine='$line'");
    }

    public function buttonClickedServer($params='')
    {
        $data['el']='prova';
        $data['el2']='ehi';
        $this->load->jsonView($data);
    }
}

/**
*
*/
class ProductLinesCH extends ClientHandler
{

    public function buttonClickedClientSuccess($result=null)
    {
        return "alert(response);";
    }

    public function buttonClickedClientFailure($result=null)
    {
        return "alert('subclass failure')";
    }
}
