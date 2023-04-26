<?php

class UbMethodResolver {

    var $map;

    public function __construct() {
        $this->map = $this->buildMap();
    }

    function getMethodHandler($method) {
        $actionName = $this->map[$method] ?? null;
        if (!$actionName)
            return null;

        return $this->getClass($actionName);
    }

    private function buildMap() {
        $jsonData = file_get_contents(CLASSES_PATH . 'Ub/methods.json');
        $res = json_decode($jsonData, true);
        return $res;
    }

    function getClass($pathClass) {
        $classPath = str_replace(".", "/", $pathClass) . ".php";
        $className = str_replace(".", "", $pathClass);
        require_once(CLASSES_PATH . $classPath);
        return new $className();
    }
}