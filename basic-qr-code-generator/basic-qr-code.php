<?php

/**
 * Plugin name: Basic QR Code Generator
 * Author: arif hassan
 * Version: 1.0
 * Decription: Generate QR Code
 * Plugin URI: https://github.com/arifhassan
 * Author URI: https://github.com/arifhassan
 * Text Domain: basic-qr-code
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 **/

class afs_qr_code_generate
{

    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }

    public function initialize()
    {
        add_filter('the_content', array($this, "display_qr_code"), 999, 1);
    }

    public function display_qr_code($content)
    {
        $current_post_url = get_permalink();
        $size = 150;
        $filtered_size = apply_filters('basic_qr_code_size', $size);
        $color = 'FF0000';
        $filtered_color = apply_filters('basic_qr_code_color', $color);
        $qr_code_image = '<div><img src="https://api.qrserver.com/v1/create-qr-code/?size=' . $filtered_size . 'x' . $filtered_size . '&color=' . $filtered_color . '&data=' . $current_post_url . ' " alt="QR Code" /></div>';
        $new_content = $content . $qr_code_image;
        return $new_content;
    }
}

new afs_qr_code_generate();
