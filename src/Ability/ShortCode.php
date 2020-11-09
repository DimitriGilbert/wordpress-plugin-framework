<?php
namespace Dbuild\WpPlugin\Ability;

trait ShortCode {
  protected $shortCodes = [];
  protected $shortCodePrefix = "";

  public function addShortCode(string $tag, $callback)
  {
    \add_shortcode($tag, $callback);
  }
}