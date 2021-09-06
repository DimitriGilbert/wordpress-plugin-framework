<?php
namespace Dbuild\WpPlugin;

/**
 * A generic WP_List_Table.
 * @see wp-admin/includes/class-wp-list-table.php
 */
class ListTable extends \WP_List_Table {
  public $columns, $sortable_columns, $_items, $hidden_columns, $primary, $actions;

  function __construct(array $args = []) {
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

  /**
   * Add a column to the table.
   *
   * @param string $key Column key.
   * @param string $display Column display title.
   * @param boolean $sort Is column sortable.
   * @param boolean $hidden Is column hidden.
   * @param boolean $primary Is column primary.
   * @return this
   */
  public function addColumn(
    string $key,
    string $display,
    bool $sort = false,
    bool $hidden = false,
    bool $primary = false
  ) {
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

  /**
   * Add a column to the table.
   *
   * @param array $columns
   * @return this
   */
  public function addColumns(array $columns) {
    foreach ($columns as $key => $display ) {
      $this->addColumn($key, $display);
    }
    return $this;
  }

  /**
   * Add an action to the table.
   *
   * @param string $name Action name, uc_first as display.
   * @param string $link Action link, an id is concatenated at the end to identify target.
   * @return void
   */
  public function addAction(string $name, string $link): void
  {
    $this->actions[$name] = $link;
  }

  /**
   * Get action string for an item
   *
   * @param array $item
   * @return string
   */
  public function get_item_actions(array $item)
  {
    $id = '';
    if (is_array($item) && isset($item['id'])) {
      $id = $item['id'];
    }
    elseif (is_array($item) && isset($item['ID'])) {
      $id = $item['id'];
    }
    elseif (is_a($item, '\Symlink\ORM\Models\BaseModel')) {
      $id = $item->get('ID');
    }
    elseif (property_exists($item, 'id')) {
      $id = $item->id;
    }
    elseif (property_exists($item, 'ID')) {
      $id = $item->ID;
    }
    $actions = [];
    
    foreach ($this->actions as $action => $link) {
      $actions[$action] = "<a href=\"$link$id\">".ucfirst($action)."</a>";
    }

    return $actions;
  }

  /**
   * Get HTML for a item column.
   *
   * @param array $item
   * @param string $column_name
   * @return string
   */
  public function get_item_column(array $item, string $column_name): string
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

  /**
   * Default column value for an item.
   *
   * @param array $item
   * @param string $column_name
   * @return string
   */
  function column_default($item, $column_name) {
    return $this->get_item_column($item, $column_name);
  }

  function get_sortable_columns(): array {
    return $this->sortable_columns;
  }
  
  function get_columns(): array {
    return $this->columns;
  }

  function get_bulk_actions(): array {
    $actions = [
      'delete' => 'Delete'
    ];
    return $actions;
  }

  function column_cb($item): string {
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

  /**
   * Add an item.
   *
   * @param array $item
   * @return this
   */
  public function addItem(array $item)
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

  /**
   * Render the table in a string.
   *
   * @return string
   */
  public function render()
  {
    ob_start();
    $this->display();
    return ob_get_clean();
  }
}
