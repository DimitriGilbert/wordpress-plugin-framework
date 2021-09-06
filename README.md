# dbuild/wp-plugin-framework A Wordpress Plugin Framework

It's a simple framework aimed at creating wordpress plugins a bit more easily and using an OOP syntax.

## Basics

A plugin, is just a class extending Dbuild\WpPlugin\Plugin. On its own, it doesn't do very much but you can (litterally) use abilities, which are traits, to extends what a plugin can do.
You could say that it's a framework helping you to create plugins by using a plugin like philosophy ;)

## Installation

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
composer require dbuild/wp-plugin-framework
```

## Usage

### "Main" plugin file

For wordpress purpose only

```php
<?php
/*
Plugin Name: my-plugin
Description: A simple plugin for wordpress.
Version: 0.1
Requires PHP: 7.2
License: LGPL
*/

// require autoload
require_once __DIR__.'/vendor/autoload.php';

// get an instance of the plugin
$MyPlugin = \Myplugin\Namespace\Plugin::Init();

// register activation hook
if(method_exists($MyPlugin, 'onActivation')){
  register_activation_hook(__FILE__, [$MyPlugin, 'onActivation']);
}

// register deactivation hook
if(method_exists($MyPlugin, 'onDeactivation')){
  register_deactivation_hook(__FILE__, [$MyPlugin, 'onDeactivation']);
}

```

### The real main plugin file

```php
class MyPlugin extends \Dbuild\WpPlugin\Plugin {
  __construct() {
    parent::__construct(
      // name
      "my-plugin",
      // uri
      "http://my-plugin.com",
      // description
      "A sample plugin for wordpress.",
      // version
      "0.1",
      // wpVersion
      "5.3",
      // phpVersion
      "7.2",
      // author
      "you",
      // authorUri
      "http://you.com",
      // license
      "LGPL",
      // licenseUri
      "",
      // textDomain
      "",
      // domainPath
      ""
    );
  }
}
```

### Using abilities

Abilities are for the most part traits that you declare in your plugin class.

```php
class MyPlugin extends \Dbuild\WpPlugin\Plugin {
  use \Dbuild\WpPlugin\Ability\ShortCode;
  __construct() {
    // ...
    $this->addShortCode('my-plugin-shortcode', function() {echo 'this is my plugin shortcode';});
  }
  // ...
```

The ShortCode ability method addShortCode will add the shortcode according to wordpress recomendation, never will you ever have to browse the codex on the hunt for the correct action to hook in, just use an ability !

### Abilities list

* Activate
  
* Admin
  * ___addPage___ add an admin page
    * \Dbuild\WpPlugin\AdminPage $page
    * $parent = null
* Api
  * ___addController___ add a controller
    * \Dbuild\WpPlugin\Controller $controller
  * ___addEndpoint___ add an endpoint to a controller
    * string $namespace, controller namespace
    * string $route, route within the namespace
    * string $method, HTTP method
    * $callback,
    * array $args = [] endpoints arguments
  * ___registerControllers___ trigger controller registration
* Block
  * ___addBlock___ add a block
    * string $name,
    * string $title = '',
    * string $icon = '',
    * string $category = ''
  * ___Block_init___ trigger block initialisation in wordpress
* Option
  * ___addOption___
    string $name,
    $value,
    bool $autoload = true
  * ___getOption___
    * string $name,
    * $default = false
  * ___setOption___
* Script
  * ___addScript___
    string $name,
    string $src = '',
    array $deps = array(),
    $ver = false,
    bool $in_footer = false
  * ___enqueuScripts___
* Setting
  * ___addSetting___
  * ___addSettingSection___
  * ___displaySettingsSection___
  * ___displaySetting___
  * ___settingField___
  * ___settingInputField___
  * ___settingArrayField___
  * ___settingObjectField___
* ShortCode
  * ___addShortCode___
    * string $tag,
    * $callback
* Style
  * ___addStyle___
    * string $name,
    * string $src = '',
    * array $deps = array(),
    * $ver = false,
    * string $media = 'all'

## Extending

### Creating abilities

You can create your own abilities to make the framework better suited for your needs.

Create an ability once, reuse it in ALL your plugins !

```php
Trait MyAbility {
  public $anAbilityProperty = [];
  public function doSomeAbileStuff() {
    $this->anAbilityProperty[] = 'You decide what you want to make more easy ;)';
  }
}
```

### Special abilities

Some abilities require additionnal Classes to work, or are additional classes that plug in the life cycle of the plugin:

* Dbuild\WpPlugin\Ability\Api uses Dbuild\WpPlugin\Controller to manage endpoints
* Dbuild\WpPlugin\Ability\Admin uses Dbuild\WpPlugin\AdminPage to manage administration pages/subpages
* Database tables are managed using classes extending Dbuild\WpPlugin\Db\Table

If you create an ability that needs some support from an external class, well, you are very free to do so ;)

## Utilities

For now they live here, bits of useful stuff regarding wordpress plugin creation that are neither abilities nor really fits in the plugin by themselves.

### ListTable

A generic version of Wp_List_Table used in the admin side of wordpress to manage articles for example.

```php
$table = new \Dbuild\WpPlugin\ListTable();
$table->addAction("edit", admin_url("admin.php?page=your_edit_page&id="));
$table->addAction("delete", admin_url("admin.php?page=your_delete_page&id="));
$table->addColumn("first", "primary column", false, false, true);
$table->addColumn("secondAndSort", "col 2 sort", true, false, false);
$table->addColumn("hideMe", "hidden col", false, true, false);
$tabble->addItems([
  // items to display, hey heeyyyyy
  // first arg of addColumn is the array key for the value
]);
// capture output for later use :
$render = $table->render();
echo $render;
// or just display as wordpress would do...
$table->display();
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[LGPL](https://choosealicense.com/licenses/lgpl-3.0/)
