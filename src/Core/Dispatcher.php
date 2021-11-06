<?php

namespace App\Core;

use App\Core\Interfaces\IRequest;
use App\Core\Interfaces\IRoutes;

class Dispatcher
{
  private $routeList;
  private IRequest $currentRequest;
  public function __construct(IRoutes $routeCollection, IRequest $request)
  {
    $this->routeList = $routeCollection->getRoutes();
    $this->currentRequest = $request;
    $this->dispatch();
  }
  private function dispatch()
  {
    /* Guardamos el método de la petición */
    $method = $this->currentRequest->getMethod();
    $route = $this->currentRequest->getRoute();

    /* Ahora el routeList contiene [method][route] */
    if (isset($this->routeList[$method][$route])) {
      $controllerClass = "App\\Controllers\\" . $this->routeList[$method][$route]["controller"];
      $controller =  new $controllerClass;
      $action = $this->routeList[$method][$route]["action"];

      /* Si llega petición POST obtiene el body */
      /* y lo pasa como primer parámetro despues resto */
      /* para peticiones PUT u otras deberia crear elfeif */
      if ($method == "POST") {
        $body = $this->currentRequest->getPostBody();
        $controller->$action($body, ...$this->currentRequest->getParams());
        /* sino pasa los parametros normalmente */
      } else $controller->$action(...$this->currentRequest->getParams());
    } else {
      echo "ruta no existe";
    }
  }
}
