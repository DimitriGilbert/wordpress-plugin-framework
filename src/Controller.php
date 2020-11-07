<?php
namespace Dbuild\WpPlugin;

class Controller
{
  public $namespace = null;
  public $endpoints = [];

  public function __construct(string $namespace)
  {
    $this->namespace = $namespace;
  }

  public function addEndpoint(string $route, string $method, $callback, array $args = [])
  {
    $args['method'] = $method;
    $args['callback'] = $callback;
    $this->endpoints[] = [
      'route'=>$route,
      'args' => $args
    ];
  }

  public function registerEndpoints()
  {
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

