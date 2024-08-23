<?php

/**
 * Plugin Name: Related Posts
 * Plugin URI: https://wordpress.org/plugins/related-posts/
 * Description: Show Related Posts on Single Post's Page
 * Version: 1.0.0
 * Author: Arif Hassan
 * Author URI: https://mountaviary.com/
 * Text Domain: afs-related-posts
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * 
 */

// If this file is called directly, abort.

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('AFS_RP_VERSION', '1.0.0');
define('AFS_RP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AFS_RP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the main class
require_once AFS_RP_PLUGIN_DIR . 'includes/afs-related-posts.php';

function run_afs_related_posts_plugin()
{
    $plugin = new Afs_Related_Posts();
    $plugin->run();
}
run_afs_related_posts_plugin();
