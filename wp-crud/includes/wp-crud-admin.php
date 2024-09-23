<?php
/**
 * The main WP_CRUD_Admin class
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class WP_CRUD_Admin
{
    private $controller;

    public function __construct()
    {
        $this->controller = new WP_CRUD_Controller();
    }

    public function init()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_admin_menu()
    {
        add_menu_page('WP CRUD', 'WP CRUD', 'manage_options', 'wp-crud', [$this->controller, 'display_users'], 'dashicons-admin-users');
    }

    public function enqueue_admin_scripts($hook)
    {
        if ('toplevel_page_wp-crud' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-crud-admin-style', WP_CRUD_URL . 'assets/css/wp-crud-admin.css', array(), WP_CRUD_VERSION);
    }
}