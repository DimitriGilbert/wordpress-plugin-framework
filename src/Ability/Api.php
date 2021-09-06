<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Extends the wordpress REST API.
 */
trait Api {
  protected $controllers = [];

  /**
   * Add a controller.
   *
   * @param \Dbuild\WpPlugin\Controller $controller
   * @return this
   */
  public function addController(\Dbuild\WpPlugin\Controller $controller) {
    $this->controllers[$controller->namespace] = $controller;
    return $this;
  }

  /**
   * Add endpoint to controller.
   *
   * @param string $namespace
   * @param string $route
   * @param string $method
   * @param callable  $callback
   * @param array $args
   * @return this
   */
  public function addEndpoint(
    string $namespace,
    string $route,
    string $method,
    callable $callback,
    array $args = []
  ) {
    if (array_key_exists($namespace, $this->controllers)) {
      // some magic here to see if the cb is a string and a method with that name exists on the controller
      // that way you don't have to write [$controller, 'callback_method'] just 'callback_method' as $callback value ;)
      if (is_string($callback) && method_exists($this->controllers[$namespace], $callback)) {
        $callback = [$this->controllers[$namespace], $callback];
      }
      $this->controllers[$namespace]->addEndpoint($route, $method, $callback, $args);
      return $this;
    }
    throw new \Exception("$namespace is not an existing controller", 1);
  }

  /**
   * Register all controllers.
   *
   * @return this
   */
  public function registerControllers() {
    foreach ($this->controllers as $controller) {
      $controller->registerEndpoints();
    }
    return $this;
  }
}