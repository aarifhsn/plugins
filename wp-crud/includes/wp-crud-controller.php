<?php

/**
 * Controller class
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class WP_CRUD_Controller
{

    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new WP_CRUD_Model();
        $this->view = new WP_CRUD_View();

        // Initialize hooks and actions
        add_action('init', array($this, 'create_table'));
        add_action('admin_post_add_user', array($this, 'add_user'));
        add_action('admin_post_edit_user', array($this, 'edit_user'));
        add_action('admin_post_delete_user', array($this, 'delete_user'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function create_table()
    {
        $this->model->create_table();
    }

    public function display_users()
    {
        global $wpdb;

        if (isset($_GET['edit_user'])) {
            $id = intval($_GET['edit_user']);
            $user = $this->model->get_user($id);
            $this->view->edit_user($user);
        } else {
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
            $order = isset($_GET['order']) && strtolower($_GET['order']) === 'asc' ? 'asc' : 'desc';
            $users = $this->model->get_users($orderby, $order);
            $this->view->display_users($users);
        }
    }

    public function add_user()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('wp_crud_nonce_action', 'wp_crud_nonce');
        if (empty($_POST['name']) || empty($_POST['email'])) {
            wp_die('Name and Email are required fields.');
        }

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $this->model->add_user($name, $email);

        wp_redirect(admin_url('admin.php?page=wp-crud&message=added'));
        exit;
    }

    public function edit_user()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('wp_crud_nonce_action', 'wp_crud_nonce');
        if (empty($_POST['name']) || empty($_POST['email'])) {
            wp_die('Name and Email are required fields.');
        }

        $id = intval($_POST['id']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $this->model->update_user($id, $name, $email);

        wp_redirect(admin_url('admin.php?page=wp-crud&message=updated'));
        exit;
    }

    public function delete_user()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('wp_crud_nonce_action', 'wp_crud_nonce');
        $id = intval($_POST['id']);
        $this->model->delete_user($id);

        wp_redirect(admin_url('admin.php?page=wp-crud&message=deleted'));
        exit;
    }

    public function enqueue_scripts($hook)
    {
        if ($hook != 'toplevel_page_wp-crud') {
            return;
        }
        wp_enqueue_style('wp-crud-admin', WP_CRUD_URL . 'assets/css/wp-crud-admin.css');
    }
}