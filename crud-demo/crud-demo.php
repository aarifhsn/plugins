<?php
/**
 * Plugin Name: CRUD Demo
 * Plugin URI: https://github.com/WordPress/wordpress-develop/blob/develop/wp-content/plugins/crud-demo/crud-demo.php
 * Description: CRUD Demo
 * Version: 1.0.0
 * Author: WP CRUD
 * Author URI: https://mountaviary.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: crud-demo
 * Domain Path: /languages
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class CustomUserTable
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'custom_users';

        register_activation_hook(__FILE__, array($this, 'create_table'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function create_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function add_admin_menu()
    {
        add_menu_page('Custom Users', 'Custom Users', 'manage_options', 'custom-users', array($this, 'admin_page'));
    }

    public function admin_page()
    {
        if (isset($_POST['cut_action'])) {
            $this->handle_form_submission();
        }

        $users = $this->get_users();
        $this->render_admin_page($users);
    }

    private function handle_form_submission()
    {
        check_admin_referer('cut_action_nonce');

        $action = $_POST['cut_action'];
        $email = sanitize_email($_POST['email']);
        $name = sanitize_text_field($_POST['name']);

        switch ($action) {
            case 'add':
                $this->add_user($email, $name);
                break;
            case 'edit':
                $id = intval($_POST['id']);
                $this->update_user($id, $email, $name);
                break;
            case 'delete':
                $id = intval($_POST['id']);
                $this->delete_user($id);
                break;
        }
    }

    private function add_user($email, $name)
    {
        global $wpdb;
        $wpdb->insert($this->table_name, array('email' => $email, 'name' => $name));
    }

    private function update_user($id, $email, $name)
    {
        global $wpdb;
        $wpdb->update($this->table_name, array('email' => $email, 'name' => $name), array('id' => $id));
    }

    private function delete_user($id)
    {
        global $wpdb;
        $wpdb->delete($this->table_name, array('id' => $id));
    }

    private function get_users()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM $this->table_name");
    }

    private function render_admin_page($users)
    {
        ?>
        <div class="wrap">
            <h1>Custom Users</h1>

            <!-- Add new user form -->
            <h2>Add New User</h2>
            <form method="post" action="">
                <?php wp_nonce_field('cut_action_nonce'); ?>
                <input type="hidden" name="cut_action" value="add">
                <table class="form-table">
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" name="email" id="email" required></td>
                    </tr>
                    <tr>
                        <th><label for="name">Name</label></th>
                        <td><input type="text" name="name" id="name" required></td>
                    </tr>
                </table>
                <?php submit_button('Add User'); ?>
            </form>

            <!-- Display users table -->
            <h2>Users</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user->id; ?></td>
                            <td><?php echo esc_html($user->email); ?></td>
                            <td><?php echo esc_html($user->name); ?></td>
                            <td>
                                <button class="button"
                                    onclick="cutEditUser(<?php echo $user->id; ?>, '<?php echo esc_js($user->email); ?>', '<?php echo esc_js($user->name); ?>')">Edit</button>
                                <form method="post" action="" style="display:inline;">
                                    <?php wp_nonce_field('cut_action_nonce'); ?>
                                    <input type="hidden" name="cut_action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                                    <input type="submit" class="button" value="Delete"
                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Edit user form (hidden by default) -->
        <div id="cut-edit-form" style="display:none;">
            <h2>Edit User</h2>
            <form method="post" action="">
                <?php wp_nonce_field('cut_action_nonce'); ?>
                <input type="hidden" name="cut_action" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <table class="form-table">
                    <tr>
                        <th><label for="edit-email">Email</label></th>
                        <td><input type="email" name="email" id="edit-email" required></td>
                    </tr>
                    <tr>
                        <th><label for="edit-name">Name</label></th>
                        <td><input type="text" name="name" id="edit-name" required></td>
                    </tr>
                </table>
                <?php submit_button('Update User'); ?>
                <button type="button" class="button" onclick="cutCancelEdit()">Cancel</button>
            </form>
        </div>

        <script>
            function cutEditUser(id, email, name) {
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-name').value = name;
                document.getElementById('cut-edit-form').style.display = 'block';
            }

            function cutCancelEdit() {
                document.getElementById('cut-edit-form').style.display = 'none';
            }
        </script>
        <?php
    }
}

// Initialize the plugin
new CustomUserTable();