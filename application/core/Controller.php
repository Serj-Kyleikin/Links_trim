<?php

namespace application\core;

class Controller {

    public $path = '\application\core\Model';

    public function __construct() {

        $route = explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0];

        if($route == '') {

            $view = new View;
            $view->rendering();

        } else new $this->path($route);
    }
}