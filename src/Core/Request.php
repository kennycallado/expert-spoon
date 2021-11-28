<?php

namespace App\Core;

use App\Core\Interfaces\IRequest;

class Request implements IRequest
{
  private $route;
  private $method;
  private $uri;

  function __construct()
  {
    /* limpia último carácter de la uri si tiene */
    $this->uri = rtrim($_SERVER["REQUEST_URI"], "/");
    /* asegura verbos en mayúscula */
    $this->method =  strtoupper($_SERVER["REQUEST_METHOD"]);

    $rawRouteElements = explode("/", $this->uri);
    $this->route = "/" . $rawRouteElements[1];
  }

  public function getRoute()
  {
    return $this->route;
  }

  public function getMethod()
  {
    return $this->method;
  }

  public function getUri()
  {
    return $this->uri;
  }

  public function getPostBody()
  {
    return $_POST;
  }
}
