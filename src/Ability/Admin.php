<?php
namespace Dbuild\WpPlugin\Ability;

trait Admin {
  protected $pages = [];

  public function addPage(\Dbuild\WpPlugin\AdminPage $page, $parent = null)
  {
    if (!is_null($parent) && method_exists($page, 'setParent')) {
      if (is_int($parent)) {
        if (isset($this->pages[$parent])) {
          $parent = $this->pages[$parent];
        }
        else {
          $parent = null;
        }
      }

      if (!is_null($parent)) {
        $page->setParent($parent);
      }
    }
    $page->addToMenu();
    $this->pages[] = $page;
  }
}