<?php
namespace Dbuild\WpPlugin\Ability;

trait Install {
  public function install_init(string $file = __FILE__, string $callback = 'onInstall')
  {
    \register_activation_hook($file , array($this, $callback));
  }

  public function onInstall()
  {
    
  }
}