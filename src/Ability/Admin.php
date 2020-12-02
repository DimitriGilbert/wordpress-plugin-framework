<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * I see Administration pages...
 * Where ?
 * ...Everywhere !
 */
trait Admin {
  protected $pages = [];

  /**
   * Add an admin page
   *
   * @param \Dbuild\WpPlugin\AdminPage $page
   * @param \Dbuild\WpPlugin\AdminPage|int $parent Either another admin page or the its index in $this::pages.
   * @return void
   */
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