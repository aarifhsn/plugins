<?php
/**
 * Edit user template
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
    <div class="wp_crud_edit_wrap">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">

            <?php wp_nonce_field('wp_crud_nonce_action', 'wp_crud_nonce'); ?>

            <input type="hidden" name="action" value="edit_user">
            <input type="hidden" name="id" value="<?php echo isset($user->id) ? esc_attr($user->id) : ''; ?>">

            <p>
                <label for="email">Email</label>
                <input type="email" name="email"
                    value="<?php echo isset($user->email) ? esc_attr($user->email) : ''; ?>" required>
            </p>

            <p>
                <label for="name">Name</label>
                <input type="text" name="name" value="<?php echo isset($user->name) ? esc_attr($user->name) : ''; ?>"
                    required>
            </p>
            <p>
                <input type="submit" value="Update User" class="button-primary">
            </p>
        </form>
    </div>
</body>

</html>