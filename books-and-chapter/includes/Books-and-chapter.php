<?php

class Books_And_Chapter
{
    protected $loader;

    public function __construct()
    {
        $this->afs_load_dependencies();
        $this->define_afs_admin_hooks();
        $this->define_afs_public_hooks();
    }

    private function afs_load_dependencies()
    {
        $dependencies = [
            'Books-post-type.php',
            'Chapters-post-type.php',
            'Meta-boxes.php',
            'Custom-columns.php',
            'Permalinks.php',
            'Content-filters.php',
            'Admin-menu.php'
        ];

        foreach ($dependencies as $file) {
            require_once AFS_PLUGIN_DIR . 'includes/' . $file;
        }
    }

    private function define_afs_admin_hooks()
    {
        $admin_menu = new Admin_Menu();
        add_action('admin_menu', [$admin_menu, 'move_chapter_to_book']);

        $books_post_type = new Books_Post_Type();
        $chapters_post_type = new Chapters_Post_Type();
        $meta_boxes = new Meta_Boxes();
        $custom_columns = new Custom_Columns();

        add_action('init', [$books_post_type, 'register']);
        add_action('init', [$books_post_type, 'register_taxonomy']);
        add_action('init', [$chapters_post_type, 'register']);
        add_action('add_meta_boxes', [$meta_boxes, 'register']);
        add_action('save_post', [$meta_boxes, 'save']);
        add_filter('manage_book_posts_columns', [$custom_columns, 'add_to_book']);
        add_action('manage_book_posts_custom_column', [$custom_columns, 'manage_book'], 10, 2);
        add_filter('manage_chapter_posts_columns', [$custom_columns, 'add_to_chapter']);
        add_action('manage_chapter_posts_custom_column', [$custom_columns, 'manage_chapter'], 10, 2);
    }

    private function define_afs_public_hooks()
    {
        $permalinks = new Permalinks();
        $content_filters = new Content_Filters();

        add_filter('post_type_link', [$permalinks, 'custom_chapter'], 10, 2);
        add_filter('the_content', [$content_filters, 'show_chapter_in_books']);
        add_filter('the_content', [$content_filters, 'show_books_in_chapter']);
        add_filter('the_content', [$content_filters, 'show_related_books']);
    }

    public function run()
    {
        // Additional initialization if needed
    }
}
