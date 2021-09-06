<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Manage styles for a plugin.
 */
trait Style {
  protected $styles = [];

  /**
   * Add a stylesheet to the plugin.
   *
   * @param string $name
   * @param string $src
   * @param array $deps
   * @param boolean $ver
   * @param string $media
   * @return this
   */
  public function addStyle(
    string $name,
    string $src = '',
    array $deps = array(),
    $ver = false,
    string $media = 'all'
  ) {
    $this->styles[] = [
      'name' => $name,
      'src' => $src,
      'deps' => $deps,
      'ver' => $ver,
      'media' => $media,
    ];
    return $this;
  }

  /**
   * Enqueu all added styles.
   *
   * @return void
   */
  public function enqueuStyles() {
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