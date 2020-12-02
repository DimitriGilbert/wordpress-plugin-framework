<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Kind of useless as apparently WP decided you couldn't register activation anywhere but in the main plugin file...
 */
trait Activate {
  public function activate_init(string $file = __FILE__)
  {
    \register_activation_hook($file , array($this, 'onActivation'));
    \register_deactivation_hook($file , array($this, 'onDeactivation'));
  }

  public function onActivation()
  {
    
  }

  public function onDeactivation()
  {
    
  }
}