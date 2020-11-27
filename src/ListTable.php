<?php
namespace Dbuild\WpPlugin;

class ListTable extends \WP_List_Table {
  public $columns, $sortable_columns, $_items, $hidden_columns, $primary, $actions;

  function __construct(array $args = []){
    global $status, $page;
    parent::__construct($args);
    $this->items = [];
    $this->columns = [
      'cb'=> 'Select'
    ];
    $this->sortable_columns = [];
    $this->hidden_columns = [];
    $this->actions = [];
  }

  function no_items() {
    _e( 'No '.$this->_args['plural'].' found, dude.' );
  }

  public function addColumn(string $key, string $display, bool $sort = false, bool $hidden = false, bool $primary = false)
  {
    $this->columns[$key] = $display;
    if ($sort) {
      $this->sortable_columns[$key] = [$key, false];
    }
    if ($hidden) {
      $this->hidden_columns[$key] = [$key, false];
    }
    if ($primary) {
      $this->primary = $key;
    }
    $this->prepare_items();
    return $this;
  }

  public function addColumns(array $columns)
  {
    foreach ($columns as $key => $display ) {
      $this->addColumn($key, $display);
    }
    return $this;
  }

  public function addAction($name, $link)
  {
    $this->actions[$name] = $link;
  }

  public function get_item_actions($item)
  {
    $id = '';
    if (is_array($item) && isset($item[$column_name])) {
      $id = $item['id'];
    }
    elseif (is_a($item, '\Symlink\ORM\Models\BaseModel')) {
      $id = $item->get('id');
    }
    elseif (property_exists($item, 'id')) {
      $id = $item->id;
    }
    $actions = [];
    
    foreach ($this->actions as $action => $link) {
      $actions[$action] = "<a href=\"$link$id\">".ucfirst($action)."</a>";
    }

    return $actions;
  }

  public function get_item_column($item, $column_name)
  {
    $res = '';
    switch( $column_name ) {
      case 'debug':
        $res = print_r( $item, true );
      break;
      default:
        if (is_array($item) && isset($item[$column_name])) {
          $res = $item[$column_name];
        }
        elseif (is_object($item) && property_exists($item, $column_name)) {
          if (is_a($item, '\Symlink\ORM\Models\BaseModel')) {
            $res = $item->get($column_name);
          }
          else {
            $res = $item->$column_name;
          }
        }
    }
    if ($column_name === $this->primary) {
      $res .= $this->row_actions($this->get_item_actions($item));
    }
    return $res;
  }

  function column_default($item, $column_name) {
    return $this->get_item_column($item, $column_name);
  }

  function get_sortable_columns() {
    return $this->sortable_columns;
  }
  
  function get_columns(){
    return $this->columns;
  }

  function get_bulk_actions() {
    $actions = [
      'delete' => 'Delete'
    ];
    return $actions;
  }

  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="%s[]" value="%s" />',
      $this->_args['singular'],
      $this->column_default($item, 'id')
    );
  }

  function prepare_items() {
    $this->_column_headers = [$this->columns, $this->hidden_columns, $this->sortable_columns];
    if (!isset($this->primary) && !empty($this->columns)) {
      $this->primary = array_keys($this->columns)[0];
    }
  }

  public function addItem($item)
  {
    $this->items[] = $item;
    return $this;
  }

  public function addItems(array $items)
  {
    foreach ($items as $item) {
      $this->addItem($item);
    }
    return $this;
  }

  public function get_column_info()
  {
    return [
      $this->columns,
      $this->hidden_columns,
      $this->sortable_columns,
      $this->primary
    ];
  }

  public function render()
  {
    ob_start();
    $this->display();
    return ob_get_clean();
  }
}
