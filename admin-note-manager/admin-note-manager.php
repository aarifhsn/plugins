<?php
/**
 * Plugin Name: Admin Note Manager
 * Description: A simple plugin to manage admin notes in the WordPress dashboard.
 * Version: 1.0
 * Author: Arif Hassan
 * Author URI: https://github.com/aarifhsn
 * Text Domain: admin-note-manager
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class AdminNoteManager
{

    /**
     * Constructor to initialize hooks.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
        add_action('admin_head', [$this, 'enqueue_admin_styles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_fetch_admin_notes', [$this, 'fetch_notes']);
        add_action('wp_ajax_delete_admin_note', [$this, 'delete_note']);
        add_action('wp_footer', [$this, 'add_modal_to_site']);
        add_action('admin_footer', [$this, 'add_modal_to_site']);
        add_action('wp_footer', [$this, 'add_icon_to_site']);
        add_action('admin_footer', [$this, 'add_icon_to_site']);
        add_action('admin_bar_menu', [$this, 'admin_bar'], 100);
        add_action('admin_head', [$this, 'enqueue_custom_styles']);
    }

    /**
     * Register the custom post type for Admin Notes.
     */
    public function register_post_type()
    {
        register_post_type('admin_note', [
            'labels' => [
                'name' => __('Admin Notes', 'admin-note-manager'),
                'singular_name' => __('Admin Note', 'admin-note-manager'),
                'add_new_item' => __('Add New Note', 'admin-note-manager'),
                'edit_item' => __('Edit Note', 'admin-note-manager'),
                'new_item' => __('New Note', 'admin-note-manager'),
                'view_item' => __('View Note', 'admin-note-manager'),
                'not_found' => __('No notes found', 'admin-note-manager'),
            ],
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'post',
            'supports' => ['title', 'editor'],
            'menu_icon' => 'dashicons-edit',
        ]);
    }

    /**
     * Add a dashboard widget to display notes.
     */
    public function add_dashboard_widget()
    {
        wp_add_dashboard_widget(
            'admin_note_manager_widget',
            __('Admin Notes', 'admin-note-manager'),
            [$this, 'display_dashboard_widget']
        );

        global $wp_meta_boxes;
        $dashboard = &$wp_meta_boxes['dashboard']['normal']['core'];
        $widget = ['admin_note_manager_widget' => $dashboard['admin_note_manager_widget']];
        unset($dashboard['admin_note_manager_widget']);
        $dashboard = $widget + $dashboard;
    }

    /**
     * Display the content of the dashboard widget.
     */
    public function display_dashboard_widget()
    {
        $notes = get_posts([
            'post_type' => 'admin_note',
            'post_status' => 'publish',
            'numberposts' => 10,
        ]);

        if (empty($notes)) {
            echo '<p>' . __('No notes found. Add some from the Admin Notes menu!', 'admin-note-manager') . '</p>';
            return;
        }

        echo '<ul>';
        foreach ($notes as $note) {
            echo '<li><strong style="display: block; text-transform: capitalize;">' . esc_html($note->post_title) . ':</strong>' . esc_html(wp_trim_words($note->post_content, 15)) . '</li>';
        }
        echo '</ul>';
        echo '<p><a href="' . admin_url('edit.php?post_type=admin_note') . '">' . __('View All Notes', 'admin-note-manager') . '</a></p>';
    }

    /**
     * Enqueue admin styles for better visuals.
     */
    public function enqueue_admin_styles()
    {
        echo '<style>
            #admin_note_manager_widget ul {
                list-style: disc;
                margin-left: 20px;
            }
            #admin_note_manager_widget li {
                margin-bottom: 10px;
            }
        </style>';
    }

    /**
     * Enqueue styles and scripts.
     */
    public function enqueue_scripts()
    {
        $main_js = require plugin_dir_path(__FILE__) . '/assets/settings/scripts.asset.php';
        wp_enqueue_style('admin-note-manager-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        wp_enqueue_script('admin-note-manager-script', plugin_dir_url(__FILE__) . 'assets/settings/scripts.js', $main_js['dependencies'], $main_js['version'], ['in_footer' => true]);

        wp_localize_script('admin-note-manager-script', 'adminNoteManagerAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('admin_note_manager_nonce'),
        ]);
    }

    /**
     * AJAX handler to fetch admin notes.
     */
    public function fetch_notes()
    {
        check_ajax_referer('admin_note_manager_nonce', 'security');

        $notes = get_posts([
            'post_type' => 'admin_note',
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        if (empty($notes)) {
            wp_send_json_error(__('No notes found.', 'admin-note-manager'));
        }

        $output = '<div class="admin-notes-list">';
        foreach ($notes as $note) {
            $output .= '<div class="admin-note-item" data-note-id="' . esc_attr($note->ID) . '">
                <h4 class="admin-note-title">' . esc_html($note->post_title) . '</h4>
                <div class="admin-note-actions">
                    <button class="admin-note-view" data-note-id="' . esc_attr($note->ID) . '">' . __('View', 'admin-note-manager') . '</button>
                    <button class="admin-note-edit" data-note-id="' . esc_attr($note->ID) . '">' . __('Edit', 'admin-note-manager') . '</button>
                    <button class="admin-note-delete" data-note-id="' . esc_attr($note->ID) . '">' . __('Delete', 'admin-note-manager') . '</button>
                </div>
            </div>';
        }
        $output .= '</div>';

        wp_send_json_success($output);
    }

    public function view_note()
    {
        check_ajax_referer('admin_note_manager_nonce', 'security');
        $note_id = intval($_POST['note_id']);

        $note = get_post($note_id);

        if ($note && $note->post_type === 'admin_note') {
            wp_send_json_success(
                '<h4>' . esc_html($note->post_title) . '</h4>' .
                '<p>' . esc_html($note->post_content) . '</p>'
            );
        } else {
            wp_send_json_error(__('Note not found.', 'admin-note-manager'));
        }
    }

    /**
     * AJAX handler to delete admin notes.
     */
    public function delete_note()
    {
        check_ajax_referer('admin_note_manager_nonce', 'security');
        $note_id = intval($_POST['note_id']);

        if (current_user_can('delete_post', $note_id)) {
            wp_delete_post($note_id, true);
            wp_send_json_success(__('Note deleted successfully.', 'admin-note-manager'));
        } else {
            wp_send_json_error(__('You do not have permission to delete this note.', 'admin-note-manager'));
        }
    }

    public function register_ajax_actions()
    {
        add_action('wp_ajax_admin_note_manager_view_note', [$this, 'view_note']);
        add_action('wp_ajax_admin_note_manager_delete_note', [$this, 'delete_note']);
    }

    /**
     * Add a modal for viewing/editing notes.
     */
    public function add_modal_to_site()
    {
        if (current_user_can('manage_options')) {
            echo '<div id="admin-note-manager-modal" class="hidden">
                <div class="modal-content">
                    <span class="modal-close">&times;</span>
                    <div id="admin-note-modal-body"></div>
                </div>
            </div>';
        }
    }

    /**
     * Add an icon to the site for accessing admin notes.
     */
    public function add_icon_to_site()
    {
        if (current_user_can('manage_options')) {
            echo '<div id="admin-note-manager-icon" title="' . __('Admin Notes', 'admin-note-manager') . '">📝</div>';
            echo '<div id="admin-note-manager-panel"></div>';
        }
    }

    /**
     * Add a link to the admin bar for Admin Notes.
     */
    public function admin_bar($wp_admin_bar)
    {
        $wp_admin_bar->add_node([
            'id' => 'admin-note-manager',
            'title' => __('Admin Notes', 'admin-note-manager'),
            'href' => admin_url('edit.php?post_type=admin_note'),
        ]);
    }

    public function add_settings_page()
    {
        add_options_page(
            __('Admin Note Manager Settings', 'admin-note-manager'),
            __('Admin Note Manager', 'admin-note-manager'),
            'manage_options',
            'admin-note-manager-settings',
            [$this, 'render_settings_page']
        );
    }

    public function render_settings_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Save settings
        if (isset($_POST['submit'])) {
            check_admin_referer('admin_note_manager_settings');
            update_option('admin_note_manager_styles', sanitize_textarea_field($_POST['styles']));
        }

        // Get current styles
        $styles = get_option('admin_note_manager_styles', '');

        echo '<div class="wrap">
            <h1>' . __('Admin Note Manager Settings', 'admin-note-manager') . '</h1>
            <form method="post" action="">
                ' . wp_nonce_field('admin_note_manager_settings', '_wpnonce', true, false) . '
                <table class="form-table">
                    <tr>
                        <th>' . __('Custom Styles', 'admin-note-manager') . '</th>
                        <td>
                            <textarea name="styles" rows="10" cols="50" class="large-text">' . esc_textarea($styles) . '</textarea>
                            <p class="description">' . __('Add custom CSS styles for the admin notes.', 'admin-note-manager') . '</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" name="submit" class="button button-primary">' . __('Save Changes', 'admin-note-manager') . '</button>
                </p>
            </form>
        </div>';
    }

    public function register_admin_menu()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
    }

    public function enqueue_custom_styles()
    {
        $custom_styles = get_option('admin_note_manager_styles', '');

        if (!empty($custom_styles)) {
            echo '<style>' . wp_strip_all_tags($custom_styles) . '</style>';
        }
    }
}

// Initialize the plugin.
new AdminNoteManager();
