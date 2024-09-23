<?php

/**
 * Plugin Name: WP CRUD
 * Plugin URI: http://wordpress.org/plugins/wp-crud/
 * Description: A simple plugin for managing users with CRUD functionality.
 * Version: 1.0.0
 * Author: WP CRUD
 * Author URI: https://mountaviary.com
 * Text Domain: wp-crud
 * Domain Path: /languages
 * 
 * @package WP_CRUD
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constants for the plugin path and version
define('WP_CRUD_PATH', plugin_dir_path(__FILE__));
define('WP_CRUD_URL', plugin_dir_url(__FILE__));
define('WP_CRUD_VERSION', '1.0.0');


// Include the main admin functionality
if (!class_exists('WP_CRUD_Admin')) {
    require_once WP_CRUD_PATH . 'includes/wp-crud-admin.php';
}

// Include class files
require_once plugin_dir_path(__FILE__) . 'includes/wp-crud-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-crud-model.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-crud-view.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-crud-controller.php';

// Initialize the plugin
new WP_CRUD_Controller();
new WP_CRUD_Admin();


// Initialize the plugin
function run_wp_crud()
{
    $wp_crud_admin = new WP_CRUD_Admin();
    $wp_crud_admin->init();
}
add_action('plugins_loaded', 'run_wp_crud');

// Register deactivation hook
register_deactivation_hook(__FILE__, 'wp_crud_deactivate');

function wp_crud_deactivate()
{
    $model = new WP_CRUD_Model();
    $model->drop_table();
}
