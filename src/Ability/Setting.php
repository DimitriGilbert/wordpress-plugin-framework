<?php
namespace Dbuild\WpPlugin\Ability;

trait Setting {
  use \Dbuild\WpPlugin\Ability\Option;

  protected $settings = [
    'settings'=>[],
    'sections'=>[]
  ];

  public function addSetting(
    string $name,
    string $title,
    string $type,
    $default,
    string $page = 'general',
    string $section = 'default',
    array $args = []
  ) {
    $this->settings['settings'][$name] = [
      'name' => $name,
      'title' => $title,
      'type' => $type,
      'page' => $page,
      'section' => $section,
      'args' => $args,
    ];
    $this->addOption($name, $default);
  }
  
  public function addSettingSection(
    string $name,
    string $title,
    string $page = 'general',
    string $intro = null
  ) {
    $this->settings['sections'][$name] = [
      'name' => $name,
      'title' => $title,
      'page' => $page,
      'intro' => $intro
    ];
  }
    
  public function settingOnActivation()
  {
    $this->optionOnActivation();
    foreach ($this->settings['sections'] as $section) {
      add_settings_section(
        $section['name'],
        $section['title'],
        [$this, 'displaySettingsSection_'.$section['name']],
        $section['page']
      );
    }
    foreach ($this->settings['settings'] as $setting) {
      add_settings_field(
        $setting['name'],
        $setting['title'],
        [$this, 'displaySetting_'.$setting['name']],
        $setting['page'],
        $setting['section'],
        $setting['args']
      );
    }
  }

  public function settingOnDeactivation()
  {
    $this->optionOndeactivation();
  }

  public function displaySettingsSection(string $name)
  {
    $display = '<p>';
    if (isset($this->settings['sections'][$name])) {
      if (isset($this->settings['sections'][$name]['intro'])) {
        $display .= $this->settings['sections'][$name]['intro'];
      }
      else {
        $display .= $name.' setting for '.$this->name;
      }
    }
    echo $display.'</p>';
  }

  public function displaySetting(string $name)
  {
    $display = '';
    if (isset($this->settings['settings'][$name])) {
      $setting = $this->settings['settings'][$name];
      $display .= '<input type="'.'" />';
    }
    echo $display;
  }
}