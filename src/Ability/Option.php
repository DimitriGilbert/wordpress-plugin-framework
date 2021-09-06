<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Wordpress Option for the plugin.
 */
trait Option {
  protected $options = [];

  /**
   * Add an option
   *
   * @param string $name
   * @param $value
   * @param boolean $autoload
   * @return this
   */
  public function addOption(
    string $name,
    $value,
    bool $autoload = true
  ) {
    $this->options[$name] = [
      'name' => $name,
      'value' => $value,
      'autoload' => $autoload,
      'loaded' => false
    ];
    return $this;
  }

  /**
   * Get an option value.
   *
   * @param string $name
   * @param boolean $default
   * @return mixed
   */
  public function getOption(
    string $name,
    $default = false
  ) {
    if (!array_key_exists($name, $this->options)) {
      return $default;
    }
    if ($this->options[$name]['loaded']) {
      return $this->options[$name]['value'];
    }
    $this->options[$name]['value'] = get_option($name, $default);
    return $this->options[$name]['value'];
  }

  /**
   * Set the option value.
   *
   * @param string $name
   * @param mixed $value
   * @return bool
   */
  public function setOption(string $name, $value) {
    if (!array_key_exists($name, $this->options)) {
      return false;
    }
    $this->options[$name]['value'] = $value;
    return update_option($name, $value);
  }

  /**
   * onActivation callback.
   *
   * @return void
   */
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

  /**
   * onDeactivation callback.
   *
   * @return void
   */
  public function optionOnDeactivation()
  {
    foreach ($this->options as $option) {
      delete_option(
        $option['name']
      );
    }
  }
}