<?php
namespace Dbuild\WpPlugin\Db;

/**
 * Database table creation on activation and drop on deactivation.
 */
class Table
{
  public $db;
  public $name = null;
  public $fields = [];

  public function __construct(string $name) {
    global $wpdb;
    $this->db = $wpdb;
    $this->name = $this->db->prefix.$name;
  }

  /**
   * Add a field to the table.
   *
   * @param string $name
   * @param string $sqlType
   * @param integer|null $sqlLength
   * @param boolean $nullable
   * @param string|null $more
   * @return this
   */
  public function addField(
    string $name,
    string $sqlType,
    int $sqlLength = null,
    bool $nullable = false,
    string $more = null
  ) {
    $this->fields[$name] = [
      'name' => $name,
      'sqlType' => $sqlType,
      'sqlLength' => $sqlLength,
      'nullable' => $nullable,
      'more' => $more
    ];
    return $this;
  }

  /**
   * Get the field sql definition.
   *
   * @param string $tabs
   * @return string
   */
  public function getFieldsSql($tabs = "\t") {
    $sql = "";
    $max = count($this->fields);
    $x = 0;
    foreach ($this->fields as $field) {
      $x++;
      $sql .= "$tabs`".$field['name']."` ".$field['sqlType'];
      if (!is_null($field['sqlLength'])) {
        $sql .= "(".$field['sqlLength'].")";
      }
      if (!$field['nullable']) {
        $sql .= " NOT";
      }
      $sql .= " NULL";
      if (!is_null($field['more'])) {
        $sql .= " ".$field['more'];
      }
      if ($x<$max) {
        $sql .= ",\n";
      }
    }
    return $sql;
  }

  /**
   * create the table if it doesn't exist.
   *
   * @return void
   */
  public function create() {
    $sql = "CREATE TABLE IF NOT EXISTS $this->name ({$this->getFieldsSql()}) {$this->db->get_charset_collate()};";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  /**
   * drop the database table.
   *
   * @return void
   */
  public function drop() {
    $sql = "DROP TABLE IF EXISTS $this->name;";
    $this->db->query($sql);
  }
}
