<?php

/**
*
*/
class RelationCycleDetector
{

    private static $instance;
    private $load;
    private $cycleDetectMask = array();

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

    public function resetDetector()
    {
        $this->cycleDetectMask= array();
    }

    public function addModelAndCheck($modelName)
    {
        if (!isset($this->cycleDetectMask[$modelName])) {
            $this->cycleDetectMask[$modelName] = 0;
        }
        $this->cycleDetectMask[$modelName]++;

        // echo "modelName $modelName";
        // var_dump($this->cycleDetectMask);

        if ($this->cycleDetectMask[$modelName]>1) {
            return true;
        }

        return false;
    }
}
