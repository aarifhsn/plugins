<?php

class afs_reading_time_settings_page
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu'));  // Add settings page in the admin menu
    }

    public function add_menu()
    {
        add_menu_page('WP Title', 'WP Title', 'manage_options', 'wp-title-settings', array($this, 'main_menu_load_settings'), 'dashicons-edit');

        add_submenu_page('wp-title-settings', 'WP Title Settings', 'Settings', 'manage_options', 'wp-title-settings');

        add_submenu_page('wp-title-settings', 'WP Title Help', 'Help', 'manage_options', 'wp-title-help', array($this, 'sub_menu_load_settings'));
    }

    public function main_menu_load_settings()
    {
        echo '<h1>WP Title Settings Page</h1>';
    }

    public function sub_menu_load_settings()
    {
        echo '<div id="react-app"></div>';
    }
}

new afs_reading_time_settings_page();  // Ensure this class is initialized
