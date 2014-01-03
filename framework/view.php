<?php

class View
{
    private $includedViews;
    private $viewData;
    private $headCode;
    //allowed values are "" or "json"
    private $format;

    public function __construct($toBeIncluded, $data, $format='')
    {
        $this->includedViews = $toBeIncluded;
        $this->viewData = $data;
        $this->format = $format;
        $this->load = new Loader();

    }

    public function attachHandler($domElement, $event, $serverHandler=array(), $handler=null, $params=array())
    {
        $this->headCode = "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
        <script type='text/javascript'>
        \$(document).ready(function () {

            \$('$domElement').$event(function () {

                $.ajax	({	'url' : 'http://" . BASE_URL . "/" . Dispatcher::url($serverHandler['controller'], $serverHandler['action']) ."',
                            'error' : function () {" .
                                $handler->buttonClickedClientFailure()
                            . " },
                            'success' : function (response) {" .
                                $handler->buttonClickedClientSuccess()
                            . " },
                        });
            });
        });

        </script>";

    }

    public function show()
    {
        $data = $this->viewData;
        if ($this->format=="json") {
            echo json_encode($data);
        } else {

            extract($data);
            foreach ($this->includedViews as $value) {
                include $value;
            }
        }
    }

    public function includeChunk($chunk)
    {
        if ($chunk == "head") {
            echo $this->headCode;
        }
    }
}
