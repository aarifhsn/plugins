<?php
/**
 * Plugin Name: WP Title
 * Plugin URI: https://wordpress.org/plugins/wp-title/
 * Description: WP Title
 * Version: 1.0.0
 * Author: WP Title
 * Author URI: https://wp-title.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-title
 * Domain Path: /languages
 **/

class afs_reading_time
{
    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
        add_action('admin_enqueue_scripts', array($this, 'wpt_enqueue_scripts'));
    }

    public function initialize()
    {
        add_filter('the_title', array($this, "change_title"));
        add_filter('the_content', array($this, "time_to_read"));

    }

    function change_title($title)
    {
        return strtoupper($title);
    }

    function time_to_read($content)
    {
        $words = str_word_count(strip_tags($content));
        $minutes = ceil($words / 180);
        $time = "<h4>" . $minutes . ' min. ' . " to read</h4>";
        return $content . $time;
    }

    function wpt_enqueue_scripts($hook)
    {
        if ($hook == 'wp-title_page_wp-title-help') {
            $main_js = require __DIR__ . '/assets/settings/scripts.asset.php';

            wp_enqueue_script('wpt-script', plugin_dir_url(__FILE__) . './assets/settings/scripts.js', $main_js['dependencies'], $main_js['version'], array('in_footer' => true));

            wp_localize_script('wpt-script', 'wpt_data', array('ajaxurl' => admin_url('admin-ajax.php')));
        }
    }
}

new afs_reading_time();

// Load the settings page functionality
require_once __DIR__ . '/includes/settings-page.php';
