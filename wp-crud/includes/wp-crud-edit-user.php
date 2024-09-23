<?php

/**
 * Edit user template
 *
 * This template is used to render the form for editing a user's information. It pulls in
 * the user's current data and allows the admin to make changes, which are then submitted
 * via a POST request.
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>

<body>
    <div class="wp_crud_page_title">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    </div>
    <div class="wp_crud_edit_wrap">

        <h2><?php echo esc_html__('Edit User', 'wp_crud'); ?></h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

            <!-- Security: Nonce field to verify form requests -->
            <?php wp_nonce_field('wp_crud_nonce_action', 'wp_crud_nonce'); ?>

            <!-- Hidden fields for action and user ID -->
            <input type="hidden" name="action" value="edit_user">
            <input type="hidden" name="id" value="<?php echo isset($user->id) ? esc_attr($user->id) : ''; ?>">

            <!-- Name field -->
            <p>
                <label for="name"><?php echo esc_html__('Name', 'wp_crud'); ?></label>
                <input type="text" name="name" value="<?php echo isset($user->name) ? esc_attr($user->name) : ''; ?>"
                    required>
            </p>

            <!-- Email field -->
            <p>
                <label for="email"><?php echo esc_html__('Email', 'wp_crud'); ?></label>
                <input type="email" name="email"
                    value="<?php echo isset($user->email) ? esc_attr($user->email) : ''; ?>" required>
            </p>

            <!-- Submit button -->
            <p>
                <input type="submit" value="<?php echo esc_html__('Update User', 'wp_crud'); ?>" class="button-primary">
            </p>
        </form>
    </div>
</body>

</html>