<?php
namespace Dbuild\WpPlugin;

class AdminPage
{
  public $page_title;
  public $menu_title;
  public $capability;
  public $menu_slug;
  public $icon_url;
  public $position;

  public function __construct(
    string $page_title,
    string $menu_title,
    string $capability,
    string $menu_slug,
    string $icon_url = null,
    int $position = null
  ) {
    $this->page_title = $page_title;
    $this->menu_title = $menu_title;
    $this->capability = $capability;
    $this->menu_slug = $menu_slug;
    $this->icon_url = $icon_url;
    $this->position = $position;
  }
}
