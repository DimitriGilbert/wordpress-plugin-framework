<?php
namespace Dbuild\WpPlugin\Ability;

trait Api {
  protected $controllers = [];

  public function addController(\Dbuild\WpPlugin\Controller $controller)
  {
    $this->controllers[$controller->namespace] = $controller;
    return $this;
  }

  public function addEndpoint(string $namespace, string $route, string $method, $callback, array $args = [])
  {
    if (array_key_exists($namespace, $this->controllers)) {
      if (is_string($callback) && method_exists($this->controllers[$namespace], $callback)) {
        $callback = [$this->controllers[$namespace], $callback];
      }
      $this->controllers[$namespace]->addEndpoint($route, $method, $callback, $args);
      return $this;
    }
    throw new \Exception("$namescpace is not an existing controller", 1);
  }

  public function registerControllers()
  {
    foreach ($this->controllers as $controller) {
      $controller->registerEndpoints();
    }
    return $this;
  }
}