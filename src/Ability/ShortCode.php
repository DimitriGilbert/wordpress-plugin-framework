<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Shortcode support.
 */
trait ShortCode {
  protected $shortCodes = [];
  protected $shortCodePrefix = "";

  /**
   * add a shortcode.
   *
   * @param string $tag
   * @param callable $callback
   * @return this
   */
  public function addShortCode(string $tag, callable $callback) {
    \add_shortcode($this->shortCodePrefix.$tag, $callback);
    return $this;
  }
}