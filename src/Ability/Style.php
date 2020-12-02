<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Manage styles for a plugin.
 */
trait Style {
  protected $styles = [];

  public function addStyle( string $name, string $src = '', array $deps = array(), $ver = false, string $media = 'all' )
  {
    $this->styles[] = [
      'name' => $name,
      'src' => $src,
      'deps' => $deps,
      'ver' => $ver,
      'media' => $media,
    ];
  }

  public function enqueuStyles()
  {
    foreach ($this->styles as $style) {
      \wp_enqueue_style(
        $style['name'],
        $style['src'],
        $style['deps'],
        $style['ver'],
        $style['media']
      );
    }
  }
}