<?php
    /**
    *
    */
    abstract class ClientHandler
    {
        abstract public function buttonClickedClientSuccess($result=null);

        abstract public function buttonClickedClientFailure($result=null);

    }
