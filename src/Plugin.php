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
  )
  {
    !is_null($name)?$this->name = $name:null;
    !is_null($uri)?$this->uri = $uri:null;
    !is_null($description)?$this->description = $description:null;
    !is_null($version)?$this->version = $version:null;
    !is_null($wpVersion)?$this->wpVersion = $wpVersion:null;
    !is_null($phpVersion)?$this->phpVersion = $phpVersion:null;
    !is_null($author)?$this->author = $author:null;
    !is_null($authorUri)?$this->authorUri = $authorUri:null;
    !is_null($license)?$this->license = $license:null;
    !is_null($licenseUri)?$this->licenseUri = $licenseUri:null;
    !is_null($textDomain)?$this->textDomain = $textDomain:null;
    !is_null($domainPath)?$this->domainPath = $domainPath:null;
  }

  /**
   * Singleton pattern
   *
   * @return this
   */
  public static function Init()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new static();
    }

    return self::$_instance;
  }

  final public static function __callStatic(string $met, array $args ) 
  {
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
  public function initAbility(string $ability, array $args = null)
  {
    call_user_func_array(array($this, $ability.'_init'), $args);
  }
}
