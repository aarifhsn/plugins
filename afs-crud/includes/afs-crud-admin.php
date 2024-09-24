<?php
/**
 * The main AFS_CRUD_Admin class
 *
 * @package AFS_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AFS_CRUD_Admin
 * 
 * This class handles adding the plugin's admin menu, enqueuing admin scripts,
 * and initializing any admin-related features.
 */

class AFS_CRUD_Admin
{
    private $controller;

    /**
     * Constructor method.
     * Initializes the controller and sets up hooks.
     */
    public function __construct()
    {
        $this->controller = new AFS_CRUD_Controller();
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
     * Adds a new menu item in the WordPress admin dashboard for AFS CRUD.
     */
    public function add_admin_menu()
    {
        add_menu_page(__('AFS CRUD', 'afs-crud'), __('AFS CRUD', 'afs-crud'), 'manage_options', 'afs-crud', [$this->controller, 'afs_crud_display_users'], 'dashicons-admin-users');
    }

    /**
     * Enqueues styles for the admin interface of the AFS CRUD plugin.
     *
     * @param string $hook The current admin page hook.
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('toplevel_page_afs-crud' !== $hook) {
            return;
        }

        wp_enqueue_style('afs-crud-admin-style', AFS_CRUD_URL . 'assets/css/afs-crud-admin.css', array(), AFS_CRUD_VERSION);
    }
}