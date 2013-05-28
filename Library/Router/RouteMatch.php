<<<<<<< HEAD
<?php

namespace ezRPG\Library\Router;
=======
<?php
namespace Library\Router;
>>>>>>> 4b834a18650eae41cddb6f27bf0182d9e5bdcbd9

class RouteMatch implements RouteMatchInterface
{
    protected $params = array();
    
    public function __construct(array $params)
    {
        $this->params = $params;
    }
    
    public function getParam($name, $default = null)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return $default;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 4b834a18650eae41cddb6f27bf0182d9e5bdcbd9
