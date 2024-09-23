<?php
/**
 * Admin display template
 *
 * @package WP_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wp_crud_wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php
    if (isset($_GET['message'])) {
        $message = sanitize_text_field($_GET['message']);
        if ('added' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User added successfully.</p></div>';
        } elseif ('updated' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User updated successfully.</p></div>';
        } elseif ('deleted' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User deleted successfully.</p></div>';
        }
    }
    ?>

    <h2>Manage Users</h2>

    <!-- Form for adding new user -->
    <h3>Create New User</h3>
    <form class="wp_crud_form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('wp_crud_nonce_action', 'wp_crud_nonce'); ?>

        <input type="hidden" name="action" value="add_user">
        <p>
            <label for="name">Name</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <input type="submit" value="Add User" class="button-primary">
        </p>
    </form>

    <h3>Existing Users</h3>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>
                    <a href="?orderby=id&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">ID</a>
                </th>
                <th>
                    <a href="?orderby=name&order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">Name</a>
                </th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) {
                foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo esc_html($user->id); ?></td>
                        <td><?php echo esc_html($user->name); ?></td>
                        <td><?php echo esc_html($user->email); ?></td>
                        <td>
                            <!-- Edit Button -->

                            <a href="<?php echo admin_url('admin.php?page=wp-crud&edit_user=' . esc_attr($user->id)); ?>"
                                class="button-primary">Edit</a>
                            <!-- Delete Button -->
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                                style="display:inline;">
                                <?php wp_nonce_field('wp_crud_nonce_action', 'wp_crud_nonce'); ?>
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?php echo esc_attr($user->id); ?>">
                                <input type="submit" value="Delete" class="button-secondary"
                                    onclick="return confirm('Are you sure?');">
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>