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
}

$afs_reading_time = new afs_reading_time();
