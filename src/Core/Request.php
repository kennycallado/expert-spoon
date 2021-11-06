<?php

namespace App\Core;

use App\Core\Interfaces\IRequest;

class Request implements IRequest
{
  private $route;
  private $params;
  /* nueva propiedad */
  private $method;
  function __construct()
  {
    $rawRoute = $_SERVER["REQUEST_URI"];
    $rawRouteElements = explode("/", $rawRoute);
    /* guarda el método de la petición */
    $this->method =  $_SERVER["REQUEST_METHOD"];
    $this->route = "/" . $rawRouteElements[1];
    $this->params = array_slice($rawRouteElements, 2);
  }

  public function getRoute()
  {
    return $this->route;
  }

  public function getParams()
  {
    return $this->params;
  }

  /* Nuevos métodos */
  /* devuelve el metodo de la petición */
  public function getMethod()
  {
    return $this->method;
  }

  /* devuelve el contenido de post */
  public function getPostBody()
  {
    return $_POST;
  }
}
