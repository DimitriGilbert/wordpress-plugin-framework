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

  /**
   * Add a setting.
   *
   * @param string $name
   * @param string $title
   * @param string $type
   * @param [type] $default
   * @param string $page
   * @param string $section
   * @param array $args
   * @return this
   */
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
    return $this;
  }
  
  /**
   * Add a setting section to a setting page.
   *
   * @param string $name
   * @param string $title
   * @param string $page
   * @param string|null $intro
   * @return this
   */
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
    return $this;
  }

  /**
   * onActivation callback.
   *
   * @return void
   */
  public function settingOnActivation() {
    $this->optionOnActivation();
  }

  /**
   * onDeactivation callback.
   *
   * @return void
   */
  public function settingOnDeactivation() {
    $this->optionOndeactivation();
  }

  /**
   * outputs the settings section.
   *
   * @param $section
   * @return void
   */
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

  /**
   * Outputs a setting field.
   *
   * @param  $setting
   * @return void
   */
  public function displaySetting($setting) {
    $name = $setting['inconsistant_wp_fix'];
    $display = '';
    if (isset($this->settings['settings'][$name])) {
      $display .= $this->settingField($this->settings['settings'][$name]);
      // $display = '<input
      //     type="'.$this->settings['settings'][$name]['type'].'"
      //     value="'.$this->getOption($name).'"
      //     name="'.$name.'"
      //     id="'.$name.'"
      //   />';
    }
    echo $display;
  }

  /**
   * build the setting field.
   *
   * @param array $setting
   * @param $value
   * @return void
   */
  public function settingField(array $setting, $value = null) {
    $display = '';
    switch ($setting['type']) {
      case 'object':
        break;
      case 'boolean':
        $display = $this->settingInputField(
          $setting['name'],
          !is_null($value)?$value:$this->getOption($setting['name']),
          $setting['name'],
          'checkbox'
        );
        break;
      case 'array':
        $display = $this->settingArrayField(
          $setting['name'],
          !is_null($value)?$value:$this->getOption($setting['name'], [])
        );
        break;
      default:
        $display = $this->settingInputField(
          $setting['name'],
          !is_null($value)?$value:$this->getOption($setting['name'])
        );
    }
    return $display;
  }

  /**
   * 
   *
   * @param string $name
   * @param $value
   * @param string|null $id
   * @param string $type
   * @return string
   */
  public function settingInputField(
    string $name,
    $value = null,
    string $id = null,
    string $type = 'text'
  ): string {
    return '<input
      type="'.$type.'"
      value="'.$value.'"
      name="'.$name.'"
      id="'.(!is_null($id)?$id:$name).'"
    />';
  }

  /**
   * 
   *
   * @param string $name
   * @param array $values
   * @return string
   */
  public function settingArrayField(string $name, $values = []) {
    $display = '<div>';
    $i = 0;
    foreach ($values as $setVal) {
      $display .= '<div>'.$this->settingInputField(
        $name.'[]',
        $setVal,
        $name.'_'.$i
      ).'</div>';
      $i++;
    }
    $display .= '<button type="button" onclick="event.target.insertBefore(event.target.parentNode.firstElementChild.cloneNode())">Add One</button></div>';
    return $display;
  }

  /**
   * 
   *
   * @param string $name
   * @param array $data
   * @param array $specs
   * @return string
   */
  public function settingObjectField(string $name, $data = [], $specs = []) {
    $display = '<div>';
    foreach ($data as $key => $val) {
      $type = 'text';
      $fieldName = $name.'_'.$key;

      if (isset($specs[$key])) {
        if (isset($specs[$key]['type'])) {
          $type = $specs[$key]['type'];
        }
        if (isset($specs[$key]['name'])) {
          $fieldName = $specs[$key]['name'];
        }
      }

      $display .= '<div id="'.$fieldName.'_container"><label>'.$key.'</label>'.$this->settingField([
        'type'=>$type,
        'name' => $fieldName
      ], $val).'</div>';
    }
    $display .= '</div>';
    return $display;
  }

  /**
   * Init callback.
   *
   * @return void
   */
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
      $regArgs = [];
      register_setting($setting['page'], $setting['name']);
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