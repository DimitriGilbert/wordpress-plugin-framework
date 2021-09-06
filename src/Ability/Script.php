<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Manage scripts for a plugin.
 */
trait Script {
  protected $scripts = [];

  /**
   * Add a frontend script to the plugin.
   *
   * @param string $name
   * @param string $src
   * @param array $deps
   * @param boolean $ver
   * @param boolean $in_footer
   * @return this
   */
  public function addScript(
    string $name,
    string $src = '',
    array $deps = array(),
    $ver = false,
    bool $in_footer = false
  ) {
    $this->scripts[] = [
      'name' => $name,
      'src' => $src,
      'deps' => $deps,
      'ver' => $ver,
      'in_footer' => $in_footer,
    ];
    return $this;
  }

  /**
   * Enqueu all added scripts.
   *
   * @return void
   */
  public function enqueuScripts() {
    foreach ($this->scripts as $script) {
      \wp_enqueue_script(
        $script['name'],
        $script['src'],
        $script['deps'],
        $script['ver'],
        $script['in_footer']
      );
    }
  }
}