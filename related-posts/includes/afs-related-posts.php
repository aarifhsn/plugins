<?php

class Afs_Related_Posts
{
    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }
    public function initialize()
    {
        add_action('wp_enqueue_scripts', array($this, 'afs_load_assets'));
        add_action('the_content', array($this, "display_related_posts"));
    }

    public function afs_load_assets()
    {
        $version = AFS_RP_VERSION;

        // Enqueue plugin's CSS and JS
        $afs_assets_path = plugins_url('../assets', __FILE__); // Points to root/assets/
        $afs_css_path = $afs_assets_path . '/css/';
        $afs_js_path = $afs_assets_path . '/js/';

        wp_enqueue_style('afs-rp-styles', $afs_css_path . 'afs-related-posts.css', array(), $version);
        wp_enqueue_script('afs-rp-scripts', $afs_js_path . 'afs-related-posts.js', array('jquery'), $version, true);
    }

    public function display_related_posts($content)
    {
        // get the current post ID
        $id = get_the_ID();

        // get the categories of the corrent posts
        $categories = get_the_category($id);

        if (!empty($categories)) {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post__not_in' => array($id), // don't include the current post in the results
                'category__in' => wp_list_pluck($categories, 'term_id'),
                'orderby' => 'rand',
                'ignore_sticky_posts' => 1
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                $content .= '<div class="afs_related_posts">';
                $content .= '<h5>' . __('Related Posts', 'related-posts') . '</h5>';
                $content .= '<ul class="w-3">';
                while ($query->have_posts()) {
                    $query->the_post();
                    $content .= '<li>';
                    $content .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</a>';
                    $content .= '<h6>' . get_the_date() . '</h6>';
                    $content .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                    // show content of 20 words
                    $content .= '<p>' . wp_trim_words(get_the_content(), 20) . '</p>';
                    //author image
                    $content .= get_avatar(get_the_author_meta('user_email'), 40);
                    //author
                    $content .= '<span>' . __('By', 'related-posts') . ' ' . get_the_author() . '</span>';
                    // author bio
                    $content .= get_the_author_meta('description');

                    $content .= '</li>';
                }
                $content .= '</ul>';
                $content .= '</div>';

                wp_reset_postdata();
            }
            return $content;
        }
    }

    public function run() {}
}
