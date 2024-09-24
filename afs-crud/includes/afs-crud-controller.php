<?php

/**
 * AFS_CRUD_Controller class handles user interactions (CRUD operations) and connects
 * the model and view components of the plugin.
 *
 * @package AFS_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AFS_CRUD_Controller
 * 
 * This class controls the CRUD functionality for users, initializes hooks,
 * and manages the data flow between the model and view.
 */
class AFS_CRUD_Controller
{

    private $model;
    private $view;

    /**
     * Constructor method.
     * Initializes the model and view, and registers action hooks for CRUD operations.
     */
    public function __construct()
    {
        $this->model = new AFS_CRUD_Model();
        $this->view = new AFS_CRUD_View();

        // Initialize hooks and actions
        add_action('init', array($this, 'afs_crud_create_table'));
        add_action('admin_post_add_user', array($this, 'add_user'));
        add_action('admin_post_edit_user', array($this, 'edit_user'));
        add_action('admin_post_delete_user', array($this, 'delete_user'));
        add_action('admin_enqueue_scripts', array($this, 'afs_crud_enqueue_scripts'));
    }

    /**
     * Creates the users table when the plugin is activated.
     */
    public function afs_crud_create_table()
    {
        $this->model->afs_crud_create_table();
    }

    /**
     * Display Users.
     * 
     * @since 1.0.0
     * 
     */
    public function afs_crud_display_users()
    {
        // Check nonce for security
        // Check if the action and nonce are set
        if (isset($_GET['afs_crud_nonce_action']) && isset($_GET['afs_crud_nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_GET['afs_crud_nonce']));

            if (!wp_verify_nonce($nonce, 'afs_crud_nonce_action')) {
                wp_die(esc_html_e('Invalid request: Unauthorized action.', 'afs_crud'));
            }
        }

        if (isset($_GET['edit_user'])) {
            $id = intval($_GET['edit_user']);
            $user = $this->model->get_user($id);
            $this->view->edit_user($user);
        } else {
            $orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'id';
            $order = isset($_GET['order']) && sanitize_text_field(wp_unslash($_GET['order'])) === 'desc' ? 'DESC' : 'ASC';
            $order = strtolower($order);

            // Ensure $orderby is a valid column name to prevent SQL injection
            $valid_columns = ['id', 'name', 'email'];
            if (!in_array($orderby, $valid_columns)) {
                $orderby = 'id';
            }

            $users = $this->model->get_users($orderby, $order);
            $this->view->display_users($users, $orderby, $order);
        }
    }

    /**
     * Adds a new user based on form submission data.
     */
    public function add_user()
    {
        // Check for permission
        if (!current_user_can('manage_options')) {
            wp_die(esc_html_e('Unauthorized user', 'afs-crud'));
        }

        // Verify the nonce
        check_admin_referer('afs_crud_nonce_action', 'afs_crud_nonce');

        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['email'])) {
            wp_die(esc_html_e('Name and Email are required fields.', 'afs-crud'));
        }

        // Sanitize user input
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $email = sanitize_email(wp_unslash($_POST['email']));

        // Add user to the database
        $this->model->add_user($name, $email);

        // Redirect with a success message
        wp_redirect(esc_url_raw(admin_url('admin.php?page=afs-crud&message=added')));
        exit;
    }

    /**
     * Edits an existing user based on form submission data.
     */
    public function edit_user()
    {
        // Check for permission
        if (!current_user_can('manage_options')) {
            wp_die(esc_html_e('Unauthorized user', 'afs-crud'));
        }

        // Verify the nonce
        check_admin_referer('afs_crud_nonce_action', 'afs_crud_nonce');

        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['email'])) {
            wp_die(esc_html_e('Name and Email are required fields.', 'afs-crud'));
        }

        // Sanitize user input
        $id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        $email = sanitize_email(wp_unslash($_POST['email']));

        // Update user in the database
        $this->model->update_user($id, $name, $email);

        // Redirect with a success message
        wp_redirect(esc_url_raw(admin_url('admin.php?page=afs-crud&message=updated')));

        exit;
    }

    /**
     * Deletes a user based on the user ID passed via form submission.
     */
    public function delete_user()
    {
        // Check for permission
        if (!current_user_can('manage_options')) {
            wp_die(esc_html_e('Unauthorized user', 'afs-crud'));
        }

        // Verify the nonce
        check_admin_referer('afs_crud_nonce_action', 'afs_crud_nonce');

        // Get and sanitize the user ID
        $id = isset($_POST['id']) ? intval(wp_unslash($_POST['id'])) : 0;

        // Delete the user from the database
        $this->model->delete_user($id);

        // Redirect with a success message
        wp_redirect(admin_url('admin.php?page=afs-crud&message=deleted'));
        exit;
    }

    /**
     * Enqueues admin-specific styles for the AFS CRUD plugin.
     *
     * @param string $hook The current admin page hook.
     */
    public function afs_crud_enqueue_scripts($hook)
    {
        if ($hook != 'toplevel_page_afs-crud') {
            return;
        }
        wp_enqueue_style('afs-crud-admin', AFS_CRUD_URL . 'assets/css/afs-crud-admin.css', array(), AFS_CRUD_VERSION);
    }
}