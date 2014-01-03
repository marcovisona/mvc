<?php

class Loader
{
    public function __construct()
    {
    }

    public function view($name, $template='', $data=null)
    {
        $classesDir = Config::classesDir();
        $templates = Config::templates();

        if ($template=='') {
            $file = $classesDir['views'] . $name . '.php';
            $toBeIncluded[] = $file;
        } else {
            $currTemplate = $templates[$template];
            foreach ($currTemplate as $currView) {
                $file = $classesDir['templates'] . $currView . '.php';
                if (strcmp($currView,'!content')==0) {
                    $file = $classesDir['views'] . $name . '.php';
                }
                $toBeIncluded[] = $file;
            }
        }

        $d = Dispatcher::getInstance();
        $c = &$d->getCurrentController();

        $c->$name = new View($toBeIncluded, $data);
        $c->views[] = $c->$name;
    }

    public function jsonView($data)
    {
        $d = Dispatcher::getInstance();
        $c = &$d->getCurrentController();

        $c->jsonView = new View(array(),$data, 'json');
        $c->views[] = $c->jsonView;
    }

    public function model($model)
    {
        require_once '../framework/model.php';

        $cycleDet = RelationCycleDetector::getInstance();
        $cycleDet->resetDetector();

        $cl = ClassLoader::getInstance();
        $className = ucfirst($model) . "Model";
        $cycleDet->addModelAndCheck($className);
        $cl->loadClass($className);
        $loadedModel = new $className();
        $d = Dispatcher::getInstance();
        $c = &$d->getCurrentController();
        $c->$model = $loadedModel;

    }

    public function &controller($controller) {
        $cl = ClassLoader::getInstance();
        $className = ucfirst($controller) . "Controller";
        $cl->loadClass($className);
        $contr = new $className();

        return $contr;
    }

    public function helper($helperName, $v)
    {
        $cl = ClassLoader::getInstance();
        $className = ucfirst($helperName) . "Helper";
        $cl->loadClass($className);
        $loadedHelper = new $className();
        $v->$helperName = $loadedHelper;
    }

}
