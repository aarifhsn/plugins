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
            'show_in_rest' => false,
            'menu_icon' => 'dashicons-book',
            'taxonomy'  => ['genre', 'movie']
        );
        register_post_type('book', $args);
    }

    public function register_taxonomy()
    {
        register_taxonomy('genre', ['book', 'post'], [
            'labels' => [
                'name' => 'Genre',
                'singular_name' => 'Genre',
            ],
            'hierarchical' => true,
        ]);
    }
}
