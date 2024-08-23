<?php

/**
 * Plugin Name: WP Taxonomy
 * Plugin URI: https://wordpress.org/plugins/wp-taxonomy/
 * Description: WP Taxonomy
 * Author: Arif Hassan
 * Author URI: https://mountaviary.com
 * Version: 1.0.0
 * Text Domain: wp-taxonomy
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

class Afs_WP_Taxonomy
{
    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));

        add_filter('the_content', array($this, 'add_taxonomies_to_content'));
        add_filter('the_content', array($this, 'show_related_movies'));
        add_filter('the_title', array($this, 'add_movie_year_to_title'));
    }

    public function register_post_type()
    {
        register_post_type(
            'movie',
            array(
                'labels' => array(
                    'name' => __('Movies', 'wp-taxonomy'),
                    'singular_name' => __('Movie', 'wp-taxonomy'),
                    'add_new' => __('Add Movie', 'wp-taxonomy'),
                ),
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'movie', 'with_front' => false),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                'taxonomies'    => array('genre', 'actor', 'director', 'year', 'rating'),
            )
        );
    }

    public function register_taxonomies()
    {
        register_taxonomy('genre', ['movie'], array(
            'labels' => array(
                'name' => __('Genre', 'wp-taxonomy'),
                'singular_name' => __('Genre', 'wp-taxonomy'),
            ),
            'hierarchical' => true,
            'show_in_menu'  => false,
            'rewrite'   => array('slug' => 'movie-genre', 'with_front' => false),
            'show_admin_column' => true
        ));
        register_taxonomy('actor', ['movie'], array(
            'labels' => array(
                'name' => __('Actors', 'wp-taxonomy'),
                'singular_name' => __('Actor', 'wp-taxonomy'),
            ),
            'hierarchical' => true,
            'show_in_menu'  => false,
            'rewrite'   => array('slug' => 'movie-actor', 'with_front' => false),
            'show_admin_column' => true
        ));
        register_taxonomy('director', ['movie'], array(
            'labels' => array(
                'name' => __('Directors', 'wp-taxonomy'),
                'singular_name' => __('Director', 'wp-taxonomy'),
            ),
            'hierarchical' => true,
            'show_in_menu'  => false,
            'rewrite'   => array('slug' => 'movie-director', 'with_front' => false),
            'show_admin_column' => true
        ));
        register_taxonomy('year', ['movie'], array(
            'labels' => array(
                'name' => __('Year', 'wp-taxonomy'),
                'singular_name' => __('Year', 'wp-taxonomy'),
            ),
            'hierarchical' => false,
            'show_in_menu'  => false,
            'rewrite'   => array('slug' => 'movie-year', 'with_front' => false),
            'show_admin_column' => true

        ));
    }

    public function add_taxonomies_to_content($content)
    {
        $post = get_post(get_the_ID());

        if ($post->post_type !== 'movie') {
            return $content;
        }

        $movie_genres = get_the_term_list($post->ID, 'genre', '', ', ', '');
        $movie_actors = get_the_term_list($post->ID, 'actor', '', ', ', '');
        $movie_directors = get_the_term_list($post->ID, 'director', '', ', ', '');
        $movie_year = get_the_term_list($post->ID, 'year', '', ', ', '');

        if ($movie_actors) {
            $content .= '<p><strong>' . __('Actors:', 'wp-taxonomy') . '</strong> ' . $movie_actors . '</p>';
        }
        if ($movie_directors) {
            $content .= '<p><strong>' . __('Director:', 'wp-taxonomy') . '</strong> ' . $movie_directors . '</p>';
        }
        if ($movie_year) {
            $content .= '<p><strong>' . __('Year:', 'wp-taxonomy') . '</strong> ' . $movie_year . '</p>';
        }
        if ($movie_genres) {
            $content .= '<p><strong>' . __('Genre:', 'wp-taxonomy') . '</strong> ' . $movie_genres . '</p>';
        }

        return $content;
    }

    public function add_movie_year_to_title($title)
    {
        $post = get_post(get_the_ID());
        if ($post->post_type !== 'movie') {
            return $title;
        }
        $years = get_the_terms($post->ID, 'year');
        if ($years) {
            $title .= ' (' . $years[0]->name . ')';
        }
        return $title;
    }

    public function show_related_movies($content)
    {
        $genre = get_the_terms(get_the_ID(), 'genre');

        if (!$genre) {
            return $content;
        }

        $query = new WP_Query(array(
            'post_type' => 'movie',
            'post__not_in' => [get_the_ID()],
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'genre',
                    'field' => 'slug',
                    'terms' => wp_list_pluck($genre, 'slug')
                ),
            ),
        ));

        if ($query->have_posts()) {
            $related = '<h3>Relared Movies</h3><ul class="related_movie_lists">';
            while ($query->have_posts()) {
                $query->the_post();
                $related .= '<li>';
                $related .= '<div class="">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
                $related .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                $related .= '</li>';
            }
            $related .= '</ul>';
            wp_reset_postdata();
            return $content . $related;
        }
    }
}

new Afs_WP_Taxonomy();
