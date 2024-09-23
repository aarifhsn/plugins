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

/**
 * Class WP_CRUD_Admin
 * 
 * This class handles adding the plugin's admin menu, enqueuing admin scripts,
 * and initializing any admin-related features.
 */

class WP_CRUD_Admin
{
    private $controller;

    /**
     * Constructor method.
     * Initializes the controller and sets up hooks.
     */
    public function __construct()
    {
        $this->controller = new WP_CRUD_Controller();
    }

    /**
     * Initialize the admin functionalities by setting up necessary actions.
     */
    public function init()
    {
        // Hook for adding the admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Hook for enqueuing admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Adds a new menu item in the WordPress admin dashboard for WP CRUD.
     */
    public function add_admin_menu()
    {
        add_menu_page(__('WP CRUD', 'wp-crud'), __('WP CRUD', 'wp-crud'), 'manage_options', 'wp-crud', [$this->controller, 'wp_crud_display_users'], 'dashicons-admin-users');
    }

    /**
     * Enqueues styles for the admin interface of the WP CRUD plugin.
     *
     * @param string $hook The current admin page hook.
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('toplevel_page_wp-crud' !== $hook) {
            return;
        }

        wp_enqueue_style('wp-crud-admin-style', WP_CRUD_URL . 'assets/css/wp-crud-admin.css', array(), WP_CRUD_VERSION);
    }
}