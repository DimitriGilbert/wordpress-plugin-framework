<?php
namespace Dbuild\WpPlugin\Ability\AdminPage;

/**
 * Admin top level Page
 */
trait Top {
  protected $children = [];

  /**
   * Add a child page
   *
   * @param \Dbuild\WpPlugin\AdminPage $child
   * @return this
   */
  public function addChild(\Dbuild\WpPlugin\AdminPage $child) {
    if (method_exists($child, 'setParent')) {
      $child->setParent($this);
    }
    $this->children[] = $child;
    return $this;
  }
  
  /**
   * Add an admin page.
   *
   * @return void
   */
  public function addToMenu() {
    \add_menu_page(
      $this->page_title,
      $this->menu_title,
      $this->capability,
      $this->menu_slug,
      [$this, 'render'],
      $this->icon_url,
      $this->position
    );
    foreach ($this->children as $child) {
      $child->addToMenu();
    }
  }
}