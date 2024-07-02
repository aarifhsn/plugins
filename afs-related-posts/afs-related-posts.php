<?php

/**
 * plugin name: Afs Related Posts
 * plugin URI: http://www.affiliatetheme.com
 * description: Display related posts on your site
 * version: 1.0
 * author: Affiliatetheme
 * author URI: http://www.affiliatetheme.com
 */


class Afs_Related_Posts
{
    function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }
    public function initialize()
    {
        add_action('the_content', array($this, "display_related_posts"));
    }
    public function display_related_posts($content)
    {
        $id = get_the_ID();
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => array($id), // don't include the current post in the results
            'orderby' => 'rand',
            'ignore_sticky_posts' => 1
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            $content .= '<div class="related-posts">';
            $content .= '<h5>' . __('Related Posts', 'related-posts') . '</h5>';
            $content .= '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                $content .= '<li>';
                $content .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</a>';
                $content .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                // show content of 20 words
                $content .= '<p>' . wp_trim_words(get_the_content(), 20) . '</p>';
                $content .= '</li>';
            }
            $content .= '</ul>';
            $content .= '</div>';

            wp_reset_postdata();
        }
        return $content;
    }
}

new Afs_Related_Posts();
