<?php

/**
 * Plugin Name: Books and Chapter
 * Plugin URI: http://wordpress.org/plugins/books-and-chapter/
 * Description: Add a chapter and book to your posts
 * Version: 1.0.0
 * Author: Arif Hassan
 * Author URI: https://mountaviary.com
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: afs
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('AFS_VERSION', '1.0.0');
define('AFS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AFS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the main class
require_once AFS_PLUGIN_DIR . 'includes/Books-and-chapter.php';

function run_books_and_chapter()
{
    $plugin = new Books_And_Chapter();
    $plugin->run();
}
run_books_and_chapter();
