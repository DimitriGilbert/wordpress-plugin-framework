<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Addon for option to manage them through WP setting interface.
 * @uses \Dbuild\WpPlugin\Ability\Option
 * @todo Add magic call to intercept methods and trigger setting display if needed.
 */
trait Block {

  public $blocks = [];

  /**
   * Add a Gutenberg block
   *
   * @param string $name
   * @param string $title
   * @param string $icon
   * @param string $category
   * @return this
   */
  public function addBlock(
    string $name,
    string $title = '',
    string $icon = '',
    string $category = ''
  ) {
    $this->blocks[$name] = [
      'name' => $name,
      'title' => $title !== '' ? $title : $name,
      'icon' => $icon !== '' ? $icon : 'dashicons-wordpress',
      'category' => $category !== '' ? $category : 'common',
    ];
    return $this;
  }

  /**
   * Initialize declared blocks.
   *
   * @return this
   */
  public function Block_init() {// automatically load dependencies and version
    $asset_file = include( plugin_dir_path( __FILE__ ) . 'blocks/build/index.asset.php');
    wp_register_script(
      $this->name.'-gutenberg-blocks',
      plugins_url( 'blocks/build/index.js', __FILE__ ),
      $asset_file['dependencies'],
      $asset_file['version']
    );

    wp_register_style(
      $this->name.'-gutenberg-blocks',
      plugins_url( 'blocks/style.css', __FILE__ ),
      array( ),
      filemtime( plugin_dir_path( __FILE__ ) . 'blocks/style.css' )
    );

    foreach ($this->blocks as $block) {
      register_block_type( "{$this->name}/{$block['name']}-gutenberg-block", array(
        'style' => $this->name.'-gutenberg-blocks',
        'editor_script' => $this->name.'-gutenberg-blocks',
      ));
    }
    return $this;
  }
}