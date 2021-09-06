<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Kind of useless as apparently WP decided you couldn't register activation anywhere but in the main plugin file...
 */
trait Activate {
  public function onActivation(){
    
  }

  public function onDeactivation() {
    
  }
}