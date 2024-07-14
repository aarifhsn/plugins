<?php

class Permalinks
{
    public function custom_chapter($post_link, $post)
    {
        if ($post->post_type == 'chapter') {
            $book_id = get_post_meta($post->ID, 'chapter_source', true);
            if ($book_id) {
                $book = get_post($book_id);
                $post_link = str_replace('%book%', $book->post_name, $post_link);
            }
        }
        return $post_link;
    }
}
