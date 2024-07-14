<?php

class Chapters_Post_Type
{
    public function register()
    {
        $args = array(
            'labels' => array(
                'name' => __('Chapters', 'afs'),
                'singular_name' => __('Chapter', 'afs'),
                'add_new' => __('Add Chapter', 'afs'),
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'book/%book%/chapter', 'with_front' => false),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest' => true,
        );
        register_post_type('chapter', $args);
    }
}
