<?php

/**
 * View class
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class WP_CRUD_View
{

    public function display_users($users)
    {
        include WP_CRUD_PATH . 'templates/wp-crud-display.php';
    }

    public function edit_user($user)
    {
        include WP_CRUD_PATH . 'includes/wp-crud-edit-user.php';
    }
}