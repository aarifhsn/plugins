<?php

/**
 * View class for displaying user data
 *
 * This class is responsible for rendering the user data and templates 
 * in the WordPress admin area.
 *
 * @package AFS_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class AFS_CRUD_View
{

    /**
     * Display the list of users.
     *
     * This method includes the template file that displays the list of users
     * in the WordPress admin area.
     *
     * @param array $users The list of users to be displayed.
     * @since 1.0.0
     */
    public function display_users($users, $orderby, $order)
    {
        include AFS_CRUD_PATH . 'templates/afs-crud-display.php';
    }

    /**
     * Display the form to edit a user.
     *
     * This method includes the template file that displays the edit form for a single user.
     *
     * @param object $user The user data to be edited.
     * @since 1.0.0
     */
    public function edit_user($user)
    {
        include AFS_CRUD_PATH . 'includes/afs-crud-edit-user.php';
    }
}