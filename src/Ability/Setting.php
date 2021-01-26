<?php
namespace Dbuild\WpPlugin\Ability;

/**
 * Addon for option to manage them through WP setting interface.
 * @uses \Dbuild\WpPlugin\Ability\Option
 * @todo Add magic call to intercept methods and trigger setting display if needed.
 */
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
    
  public function settingOnActivation() {
    $this->optionOnActivation();
  }

  public function settingOnDeactivation() {
    $this->optionOndeactivation();
  }

  public function displaySettingsSection($section) {
    $name = $section['name'];
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

  public function displaySetting($setting) {
    $name = $setting['inconsistant_wp_fix'];
    $display = '';
    if (isset($this->settings['settings'][$name])) {
      $display .= '<input
        type="'.$this->settings['settings'][$name]['type'].'"
        value="'.$this->getOption($name).'"
        name="'.$name.'"
        id="'.$name.'"
      />';
    }
    echo $display;
  }

  public function Setting_init() {
    foreach ($this->settings['sections'] as $section) {
      add_settings_section(
        $section['name'],
        $section['title'],
        [$this, 'displaySettingsSection'],
        $section['page']
      );
    }
    foreach ($this->settings['settings'] as $setting) {
      $args = $setting['args'] || [];
      $args['inconsistant_wp_fix'] = $setting['name'];
      add_settings_field(
        $setting['name'],
        $setting['title'],
        [$this, 'displaySetting'],
        $setting['page'],
        $setting['section'],
        $args
      );
    }
  }
}