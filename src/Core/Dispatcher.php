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

  private function action($method, $route, $param = null)
  {
    $controllerClass = "App\\Controllers\\" . $this->routeList[$method][$route]["controller"];
    $controller =  new $controllerClass;
    $action = $this->routeList[$method][$route]["action"];

    /* Si llega petición POST obtiene el body */
    /* y lo pasa como primer parámetro despues resto */
    /* para peticiones PUT u otras deberia crear elfeif */
    if ($method == "POST") {
      $body = $this->currentRequest->getPostBody();
      $controller->$action($body, $param);
      /* sino pasa los parametros normalmente */
    } else $controller->$action($param);
  }

  private function dispatch()
  {
    $method = $this->currentRequest->getMethod();
    $route = $this->currentRequest->getRoute();
    $uri = $this->currentRequest->getUri();

    /* en caso de /:id y cosas así... tengo que manejar al final */
    if ($uri === "/") $this->action($method, $route);

    /* Itera por el array para cotejar */
    $uriArray = array_filter(explode("/", $uri));
    foreach ($this->routeList[$method] as $route => $object) {
      /* la ruta es completa y no tiene varibales */
      if ($uri === $route) {
        $this->action($method, $route);

        return;
      }

      $routeArray = array_filter(explode("/", $route));
      if (count($uriArray) === count($routeArray)) {
        for ($i = 1; $i <= count($routeArray); $i++) {
          if ($routeArray[$i] === $uriArray[$i]) {
            if (isset($routeArray[$i + 1]) && str_contains($routeArray[$i + 1], ":")) {
              $paramName = substr($routeArray[$i + 1], 1);
              $this->action($method, $route, ["$paramName" => $uriArray[$i + 1]]);

              return;
            }
          }
        }
      }
    }
  }
}
