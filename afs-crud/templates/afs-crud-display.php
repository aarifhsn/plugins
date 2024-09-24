<?php
/**
 * Admin display template for managing users
 *
 * This template is used to display the list of users in the WordPress admin area.
 * It includes features for adding, editing, and deleting users.
 *
 * @package AFS_CRUD
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Sort parameter for the user lists sorting
$orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'id';
$order = isset($_GET['order']) ? sanitize_text_field(wp_unslash($_GET['order'])) : 'DESC';

// Sort the users based on the selected field and order
$sort_column = array_column($users, $orderby);
$sort_order = ($order === 'ASC') ? SORT_ASC : SORT_DESC;

array_multisort($sort_column, $sort_order, $users);

// Toggle ID order between ASC and DESC
$id_order = ($orderby === 'id' && $order === 'ASC') ? 'DESC' : 'ASC';
$id_url = add_query_arg(array(
    'orderby' => 'id',
    'order' => $id_order
));

// Toggle Name order between ASC and DESC
$name_order = ($orderby === 'name' && $order === 'ASC') ? 'DESC' : 'ASC';
$name_url = add_query_arg(array(
    'orderby' => 'name',
    'order' => $name_order
));

?>


<div class="afs_crud_wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <!-- Display success messages if any actions were performed (add, update, delete) -->
    <?php
    if (isset($_GET['message'])) {
        $message = sanitize_text_field(wp_unslash($_GET['message']));
        if ('added' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User added successfully.</p></div>';
        } elseif ('updated' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User updated successfully.</p></div>';
        } elseif ('deleted' === $message) {
            echo '<div class="notice notice-success is-dismissible"><p>User deleted successfully.</p></div>';
        }
    }
    ?>

    <!-- Form for adding new user -->
    <h3><?php echo esc_html__('Create New User', 'afs_crud'); ?></h3>

    <form class="afs_crud_form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

        <!-- Nonce for security -->
        <?php wp_nonce_field('afs_crud_nonce_action', 'afs_crud_nonce'); ?>

        <input type="hidden" name="action" value="add_user">
        <p>
            <label for="name"><?php echo esc_html__('Name', 'afs_crud'); ?></label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label for="email"><?php echo esc_html__('Email', 'afs_crud'); ?></label>
            <input type="email" name="email" required>
        </p>
        <p>
            <input type="submit" value="<?php echo esc_html__('Add User', 'afs_crud'); ?>" class="button-primary">
        </p>
    </form>

    <h3><?php echo esc_html__('Existing Users', 'afs_crud'); ?> (<?php echo count($users); ?>)</h3>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>
                    <a href="<?php echo esc_url($id_url); ?>">ID
                        <?php echo ($orderby === 'id') ? ($order === 'ASC' ? '▲' : '▼') : ''; ?>
                    </a>
                </th>
                <th>
                    <a href="<?php echo esc_url($name_url); ?>">Name
                        <?php echo ($orderby === 'name') ? ($order === 'ASC' ? '▲' : '▼') : ''; ?>
                    </a>
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

                            <a href="<?php echo esc_url(admin_url('admin.php?page=afs-crud&edit_user=' . esc_attr($user->id))); ?>"
                                class="button-primary">Edit</a>
                            <!-- Delete Button -->
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                                style="display:inline;">

                                <?php wp_nonce_field('afs_crud_nonce_action', 'afs_crud_nonce'); ?>
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?php echo esc_attr($user->id); ?>">
                                <input type="submit" value="Delete" class="button-secondary"
                                    onclick="return confirm('Are you sure you want to delete this user?');">
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="4"><?php echo esc_html__('No users found.', 'afs_crud'); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>