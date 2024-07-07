<?php

/**
 * Plugin Name: Wedevs Custom Column
 * Plugin URI: https://arifhsn.com
 * Description: A plugin to learn plugin devs
 * Version: 1.0.0
 * Author: Arif Hassan
 * Author URI: https://arifhsn.com 
 */

if (!class_exists('Wedevs_Custom_Column')) {

    class Wedevs_Custom_Column
    {
        private static $instance;

        public static function get_instance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct()
        {
            $this->required_classes();
        }

        private function required_classes()
        {
            require_once plugin_dir_path(__FILE__) . '/includes/Wedevs_Custom_Column_Admin_Menu.php';
            require_once plugin_dir_path(__FILE__) . '/includes/Wedevs_Post_Column.php';
            require_once plugin_dir_path(__FILE__) . '/includes/Wedevs_Posts_Type.php';

            new Wedevs_Custom_Column_Admin_Menu();
            new Wedevs_Post_Column();
            new Wedevs_Posts_Type();
        }
    }

    // Instantiate the plugin class
    Wedevs_Custom_Column::get_instance();
}
