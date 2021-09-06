<?php
namespace Dbuild\WpPlugin\Ability\AdminPage;

/**
 * Admin sub menu Page.
 */
trait Sub {
  protected $parent;

  /**
   * Set the pages parent.
   *
   * @param \Dbuild\WpPlugin\AdminPage $parent
   * @return this
   */
  public function setParent(\Dbuild\WpPlugin\AdminPage $parent) {
    $this->parent = $parent;
    return $this;
  }
  
  /**
   * add sub menu page.
   *
   * @return void
   */
  public function addToMenu() {
    \add_submenu_page(
      $this->parent->menu_slug,
      $this->page_title,
      $this->menu_title,
      $this->capability,
      $this->menu_slug,
      [$this, 'render'],
      $this->position
    );
  }
}