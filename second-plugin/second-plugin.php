<?php

/**
 * Plugin Name: Second Plugin
 * Plugin URI: https://example.com
 * Description: This is a test plugin
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPLv2
 * Text Domain: AFS
 */

class SecondPlugin
{
    public function __construct()
    {
        add_action('init', [$this, 'initialize']);
    }

    public function initialize()
    {
        add_action('wp_head', [$this, 'afs_update_style']);
        add_filter('the_title', [$this, 'afs_update_title'], 10, 2);
        add_filter('logout_redirect', [$this, 'afs_logout_redirect'], 10, 3);
        add_filter('basic_qr_code_size', [$this, 'afs_qr_code_size'], 10, 1);
        add_filter('basic_qr_code_color', [$this, 'afs_qr_code_color'], 10, 1);
    }

    public function afs_update_style()
    {
?>
        <style>
            body {
                background-color: #ddd;
            }
        </style>
<?php
    }

    public function afs_update_title($title)
    {

        $message = 'Hello World';
        $new_title = $title . ': ' . $message;
        return $new_title;
    }

    public function afs_logout_redirect($url, $to_redirect, $user)
    {
        $url = 'https://example.com';
        return $url;
    }

    public function afs_qr_code_size($size)
    {
        return 50;
    }
    public function afs_qr_code_color($color)
    {
        return 'DDD000';
    }
}

new SecondPlugin();
