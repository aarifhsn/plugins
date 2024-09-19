<?php

/**
 * Plugin Name: Related Posts
 * Plugin URI: https://github.com/aarifhsn/plugins/tree/main/related-posts
 * Description: Show Related Posts on Single Post's Page
 * Version: 1.0.0
 * Author: Arif Hassan
 * Author URI: https://mountaviary.com/
 * Text Domain: afs-related-posts
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AFS_RP_VERSION', '1.0.0'); // Plugin version
define('AFS_RP_PLUGIN_DIR', plugin_dir_path(__FILE__)); // Plugin directory path
define('AFS_RP_PLUGIN_URL', plugin_dir_url(__FILE__)); // Plugin directory URL

// Include necessary files
require_once AFS_RP_PLUGIN_DIR . 'includes/afs-related-posts.php';
require_once AFS_RP_PLUGIN_DIR . 'includes/afs-related-posts-assets.php';
require_once AFS_RP_PLUGIN_DIR . 'includes/afs-related-posts-display.php';

/**
 * Initialize and run the plugin.
 */
function run_afs_related_posts_plugin()
{
    $plugin = new Afs_Related_Posts();
}
run_afs_related_posts_plugin();
