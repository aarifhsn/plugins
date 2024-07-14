<?php

class Books_Post_Type
{
    public function register()
    {
        $args = array(
            'labels' => array(
                'name' => __('Books', 'afs'),
                'singular_name' => __('Book', 'afs'),
                'add_new' => __('Add Book', 'afs'),
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'book', 'with_front' => false),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-book',
        );
        register_post_type('book', $args);

        $args = array(
            'labels' => array(
                'name' => __('Book Genre', 'afs'),
                'singular_name' => __('Book Genre', 'afs'),
                'search_items' => __('Search Book Genre', 'afs'),
                'all_items' => __('All Book Genre', 'afs'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'book-genre', 'with_front' => false),
            'has_archive' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
        );
        register_taxonomy('book_genre', 'book', $args);
    }
}
