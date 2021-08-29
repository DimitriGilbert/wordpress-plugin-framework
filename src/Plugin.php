<?php
namespace Dbuild\WpPlugin;

/**
 * Main plugin class.
 */
class Plugin
{
  protected static $_instance = null;
  public $name = "Dbuild Plugin Framework";
  public $uri = "";
  public $description = "";
  public $version = "";
  public $wpVersion = "";
  public $phpVersion = "";
  public $author = "";
  public $authorUri = "";
  public $license = "";
  public $licenseUri = "";
  public $textDomain = "";
  public $domainPath = "";

  protected $styles = [];

  protected function __construct(
    string $name = null,
    string $uri = null,
    string $description = null,
    string $version = null,
    string $wpVersion = null,
    string $phpVersion = null,
    string $author = null,
    string $authorUri = null,
    string $license = null,
    string $licenseUri = null,
    string $textDomain = null,
    string $domainPath = null
  ) {
    $this->name = $name;
    $this->uri = $uri;
    $this->description = $description;
    $this->version = $version;
    $this->wpVersion = $wpVersion;
    $this->phpVersion = $phpVersion;
    $this->author = $author;
    $this->authorUri = $authorUri;
    $this->license = $license;
    $this->licenseUri = $licenseUri;
    $this->textDomain = $textDomain;
    $this->domainPath = $domainPath;
  }

  /**
   * Singleton pattern
   *
   * @return this
   */
  public static function Init() {
    if (is_null(self::$_instance)) {
      self::$_instance = new static();
    }

    return self::$_instance;
  }

  final public static function __callStatic(string $met, array $args ) {
    if (
      method_exists(
        __CLASS__,
        $met
      )
      && !(new \ReflectionMethod(__CLASS__))->isStatic($met)
    ) {
      $instance = self::Init();
      return call_user_func_array(array($instance, $met), $args);
    }
  }

  /**
   * Initialize an ability.
   *
   * @param string $ability Ability name.
   * @param array $args Args for initialisation.
   * @return void
   */
  public function initAbility(string $ability, array $args = null) {
    if (method_exists($this, $ability.'_init')) {
      call_user_func_array([$this, $ability.'_init'], $args);
    }
  }
}
