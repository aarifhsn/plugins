<?php

/**
 * Plugin Name: Wedevs Practice
 * Description: Wedevs Practice
 * Plugin URI: https://wedevs.com
 * Version: 1.0.0
 * Author: Mr. Arif
 * Author URI: https://wedevs.com
 * Text Domain: wedevs
 * Domain Path: /languages
 */


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 *
 * @since 1.0.0
 */

// Create a final class so that no one can extends this later
final class Wedevs_Practice
{
    /**
     * Version of the plugin
     */
    const VERSION = '1.0.0';

    // private constructor, so that no one can create instance using this keyword. they have to call the init method
    private function __construct()
    {
        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('plugins_loaded', [$this, 'init_plugin'], 10, 1);
    }

    // initialize a singleton method, so that we can have only one instance of this class
    public static function init()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    public function define_constants()
    {
        define('WeDevs_Practice_VERSION', self::VERSION);
        define('WeDevs_Practice_FILE', __FILE__);
        define('WeDevs_Practice_PATH', __DIR__);
        define('WeDevs_Practice_URL', plugins_url('', WeDevs_Practice_FILE));
        define('WeDevs_Practice_ASSETS', WeDevs_Practice_URL . '/assets');
    }

    public function init_plugin()
    {
    }
    public function activate()
    {
        $installed = get_option('wedevs_practice_installed');
        if (!$installed) {
            update_option('wedevs_practice_installed', time());
        }
        update_option('wedevs_practice_version', WeDevs_Practice_VERSION);
    }
}

/**
 * Initializes the main plugin
 *
 * @since 1.0.0
 */
function wedevs_practice()
{
    return Wedevs_Practice::init();
}

// kick-off the plugin
wedevs_practice();
