<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Afs_Related_Posts_Assets
{
    /**
     * Initialize the asset loading process.
     * Hooks the 'enqueue_assets' method to the 'wp_enqueue_scripts' action.
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    /**
     * Enqueues the plugin's CSS and JavaScript assets.
     */
    public function enqueue_assets()
    {
        // Define plugin version (default to 1.0.0 if not defined)
        $version = AFS_RP_VERSION;

        // Enqueue plugin's main CSS file
        wp_enqueue_style('afs-rp-styles', AFS_RP_PLUGIN_URL . 'assets/css/afs-related-posts.css', array(), $version);

        // Enqueue plugin's main JavaScript file
        wp_enqueue_script('afs-rp-scripts', AFS_RP_PLUGIN_URL . 'assets/js/afs-related-posts.js', array('jquery'), $version, true);

        // Enqueue Owl Carousel CSS files
        wp_enqueue_style('owl-carousel-min-css', AFS_RP_PLUGIN_URL . 'assets/css/owl.carousel.min.css', array('afs-rp-styles'), '2.3.4');
        wp_enqueue_style('owl-theme-default-min-css', AFS_RP_PLUGIN_URL . 'assets/css/owl.theme.default.min.css', array(), '2.3.4');

        // Enqueue Owl Carousel JavaScript files
        wp_enqueue_script('owl-carousel-min-js', AFS_RP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', array('jquery'), '2.3.4', true);
        wp_enqueue_script('owl-init-js', AFS_RP_PLUGIN_URL . 'assets/js/owl-initilizer.js', array('jquery', 'owl-carousel-min-js'), '2.3.4', true);
    }
}
