<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Manage scripts for a plugin.
 */
trait Script {
  protected $scripts = [];

  public function addScript( string $name, string $src = '', array $deps = array(), $ver = false, bool $in_footer = false )
  {
    $this->scripts[] = [
      'name' => $name,
      'src' => $src,
      'deps' => $deps,
      'ver' => $ver,
      'in_footer' => $in_footer,
    ];
  }

  public function enqueuScripts()
  {
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