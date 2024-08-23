<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Afs_Related_Posts
{
    /**
     * Constructor.
     * Hooks the 'initialize' method to the 'init' action.
     */
    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }

    /**
     * Initialization method.
     * Loads assets and display logic by initializing respective classes.
     */
    public function initialize()
    {
        // Initialize asset loading
        $assets = new Afs_Related_Posts_Assets();
        $assets->init();

        // Initialize display logic
        $display = new Afs_Related_Posts_Display();
        $display->init();
    }
}
