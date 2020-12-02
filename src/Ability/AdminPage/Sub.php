<?php
namespace Dbuild\WpPlugin\Ability\AdminPage;

/**
 * Admin sub menu Page.
 */
trait Sub {
  protected $parent;

  public function setParent(\Dbuild\WpPlugin\AdminPage $parent)
  {
    $this->parent = $parent;
  }
  
  public function addToMenu()
  {
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