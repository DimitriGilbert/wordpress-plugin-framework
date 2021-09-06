<?php
namespace Dbuild\WpPlugin;

/**
 * A Controller to help extend the REST API.
 */
class Controller
{
  public $namespace = null;
  public $endpoints = [];

  public function __construct(string $namespace)
  {
    $this->namespace = $namespace;
  }

  /**
   * Add an endpoint.
   *
   * @param string $route
   * @param string $method HTTP method string.
   * @param callable $callback
   * @param array $args Wordpress register_rest_route args.
   * @return void
   */
  public function addEndpoint(
    string $route,
    string $method,
    $callback,
    array $args = []
  ) {
    $args['methods'] = $method;
    $args['callback'] = $callback;
    $this->endpoints[] = [
      'route'=>$route,
      'args' => $args
    ];
  }

  /**
   * Register existing endpoints on rest_api_init action.
   *
   * @return void
   */
  public function registerEndpoints() {
    add_action(
      'rest_api_init',
      function () {
        foreach ($this->endpoints as $endpoint) {
          \register_rest_route(
            $this->namespace,
            $endpoint['route'],
            $endpoint['args']
          );
        }
      }
    );
  }
}

