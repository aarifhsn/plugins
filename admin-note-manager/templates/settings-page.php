<div class="wrap">
    <h1><?php esc_html_e('Admin Notes Settings', 'admin-note-manager'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('admin_note_manager_settings');
        do_settings_sections('admin-note-settings');
        submit_button();
        ?>
    </form>
</div>