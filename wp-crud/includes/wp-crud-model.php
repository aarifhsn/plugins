<?php

/**
 * Edit user template
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class WP_CRUD_Model
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'crud_custom_users';
    }

    public function create_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function drop_table()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->table_name");
    }

    public function get_users($orderby = 'id', $order = 'desc')
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM $this->table_name ORDER BY $orderby $order");
    }

    public function get_user($id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $id));
    }

    public function add_user($name, $email)
    {
        global $wpdb;
        $wpdb->insert($this->table_name, ['name' => $name, 'email' => $email]);
    }

    public function update_user($id, $name, $email)
    {
        global $wpdb;
        $wpdb->update($this->table_name, ['name' => $name, 'email' => $email], ['id' => $id]);
    }

    public function delete_user($id)
    {
        global $wpdb;
        $wpdb->delete($this->table_name, ['id' => $id]);
    }
}
