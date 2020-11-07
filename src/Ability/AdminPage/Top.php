<?php
namespace Dbuild\WpPlugin\Ability\AdminPage;

trait Top {
  protected $children = [];

  public function addChild(\Dbuild\WpPlugin\AdminPage $child)
  {
    $child->setParent($this);
    $this->children[] = $child;
  }
  
  public function addToMenu()
  {
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