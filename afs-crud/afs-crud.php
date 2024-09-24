<?php
/**
 * Plugin Name: AFS CRUD
 * Plugin URI: https://github.com/aarifhsn/plugins/tree/main/wp-crud
 * Description: A simple plugin for managing users with CRUD functionality.
 * Version: 1.0.0
 * Author: Arif Hassan
 * Author URI: https://mountaviary.com
 * License: GPLv2 or later
 * Text Domain: afs-crud
 * Domain Path: /languages
 * 
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constants for the plugin path and version
define('AFS_CRUD_PATH', plugin_dir_path(__FILE__));
define('AFS_CRUD_URL', plugin_dir_url(__FILE__));
define('AFS_CRUD_VERSION', '1.0.0');


// Include the main admin functionality
if (!class_exists('AFS_CRUD_Admin')) {
    require_once AFS_CRUD_PATH . 'includes/afs-crud-admin.php';
}

// Include class files
require_once AFS_CRUD_PATH . 'includes/afs-crud-model.php';
require_once AFS_CRUD_PATH . 'includes/afs-crud-view.php';
require_once AFS_CRUD_PATH . 'includes/afs-crud-controller.php';

// Initialize the plugin
function run_afs_crud()
{
    $afs_crud_admin = new AFS_CRUD_Admin();
    $afs_crud_admin->init();

    // Initialize the controller to handle logic
    new AFS_CRUD_Controller();
}
add_action('plugins_loaded', 'run_afs_crud');

// Load text domain
function afs_crud_load_textdomain()
{
    load_plugin_textdomain('afs-crud', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'afs_crud_load_textdomain');

/**
 * Function to handle plugin deactivation.
 * This function drops the custom table when the plugin is deactivated.
 */
function afs_crud_deactivate()
{
    $model = new AFS_CRUD_Model();
    $model->drop_table(); // Drop the custom user table
}

// Register the deactivation hook
register_deactivation_hook(__FILE__, 'afs_crud_deactivate');
