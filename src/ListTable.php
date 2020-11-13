<?php
namespace Dbuild\WpPlugin;

class ListTable extends \WP_List_Table {
  public $columns, $sortable_columns;

  function __construct(array $args = []){
    global $status, $page;
    parent::__construct($args);
    $this->items = [];
    $this->columns = [];
    $this->sortable_columns = [];
  }

  function no_items() {
    _e( 'No '.$this->_args['plural'].' found, dude.' );
  }

  public function addColumn(string $key, string $display, bool $sort = false)
  {
    $this->columns[$key] = $display;
    if ($sort) {
      $this->sortable_columns[$key] = [$key, false];
    }
    return $this;
  }

  public function addColumns(array $columns)
  {
    foreach ($columns as $key => $display ) {
      $this->addColumn($key, $display);
    }
    return $this;
  }

  public function get_item_column($item, $column_name)
  {    
    switch( $column_name ) {
      case 'debug':
        return print_r( $item, true );
      default:
        if (is_array($item) && isset($item[$column_name])) {
          return $item[$column_name];
        }
        elseif (is_object($item) && property_exists($item, $column_name)) {
          return $item->$column_name;
        }
        return "";
    }
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
}
