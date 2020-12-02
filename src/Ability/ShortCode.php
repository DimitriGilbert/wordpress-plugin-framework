<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Shortcode support.
 */
trait ShortCode {
  protected $shortCodes = [];
  protected $shortCodePrefix = "";

  public function addShortCode(string $tag, $callback)
  {
    \add_shortcode($shortCodePrefix.$tag, $callback);
  }
}