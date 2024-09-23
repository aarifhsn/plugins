<?php

/**
 * Model class for handling CRUD operations
 *
 * This class interacts with the WordPress database using the $wpdb object to
 * perform CRUD (Create, Read, Update, Delete) operations for custom users.
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

    /**
     * Constructor to initialize the table name
     * 
     * @since 1.0.0
     */
    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'crud_custom_users';
    }

    /**
     * Create the custom table for storing users.
     *
     * This method runs when the plugin is activated to create the database table
     * for storing custom user data.
     * 
     * @since 1.0.0
     */
    public function wp_crud_create_table()
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
        dbDelta($sql); // dbDelta function is used to create/update database tables.
    }

    /**
     * Drop the custom table for users.
     *
     * This method is called when the plugin is deactivated to remove the table.
     * 
     * @since 1.0.0
     */
    public function drop_table()
    {
        global $wpdb;

        // Ensure the table name is safe
        $table_name = $this->table_name;

        // Check if the table exists before dropping it
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name) {
            $wpdb->query("DROP TABLE IF EXISTS {$table_name}");
        }
    }

    /**
     * Retrieve all users from the table.
     *
     * @param string $orderby The column to order by (default 'id').
     * @param string $order The order direction (default 'desc').
     * @return array|null List of users or null if no users found.
     * 
     * @since 1.0.0
     */
    public function get_users($orderby = 'id', $order = 'DESC')
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$this->table_name} ORDER BY {$orderby} {$order}");
        return $wpdb->get_results($query);
    }

    /**
     * Retrieve a single user by ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return object|null The user data or null if no user found.
     * 
     * @since 1.0.0
     */
    public function get_user($id)
    {
        global $wpdb;
        // Use a prepared statement to safely retrieve a user by ID.
        $query = $wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $id);
        return $wpdb->get_row($query);
    }

    /**
     * Add a new user to the table.
     *
     * @param string $name The name of the user.
     * @param string $email The email of the user.
     * 
     * @since 1.0.0
     */
    public function add_user($name, $email)
    {
        global $wpdb;
        // Insert a new row into the table with the provided name and email.
        $wpdb->insert($this->table_name, ['name' => $name, 'email' => $email]);
    }

    /**
     * Update an existing user's data.
     *
     * @param int $id The ID of the user to update.
     * @param string $name The new name of the user.
     * @param string $email The new email of the user.
     * 
     * @since 1.0.0
     */
    public function update_user($id, $name, $email)
    {
        global $wpdb;
        $wpdb->update($this->table_name, ['name' => $name, 'email' => $email], ['id' => $id]);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id The ID of the user to delete.
     * 
     * @since 1.0.0
     */
    public function delete_user($id)
    {
        global $wpdb;
        // Delete the user from the table based on the ID.
        $wpdb->delete($this->table_name, ['id' => $id]);
    }
}
