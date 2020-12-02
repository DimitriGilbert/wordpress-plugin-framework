<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Wordpress Option for the plugin.
 */
trait Option {
  protected $options = [];

  public function addOption(string $name, $value, bool $autoload = true)
  {
    $this->options[$name] = [
      'name' => $name,
      'value' => $value,
      'autoload' => $autoload,
      'loaded' => false
    ];
  }

  public function getOption(string $name, $default = false)
  {
    if (!array_key_exists($name, $this->options)) {
      return $default;
    }
    if ($this->options[$name]['loaded']) {
      return $this->options[$name]['value'];
    }
    $this->options[$name]['value'] = get_option($name, $default);
    return $this->options[$name]['value'];
  }

  public function setOption(string $name, $value)
  {
    if (!array_key_exists($name, $this->options)) {
      return false;
    }
    $this->options[$name]['value'] = $value;
    return update_option($name, $value);
  }

  public function optionOnActivation()
  {
    foreach ($this->options as $option) {
      add_option(
        $option['name'],
        $option['value'],
        '',
        $option['autoload']
      );
    }
  }

  public function optionOnDeactivation()
  {
    foreach ($this->options as $option) {
      delete_option(
        $option['name']
      );
    }
  }
}