<?php

class Content_Filters
{
    public function show_chapter_in_books($content)
    {
        if (is_singular('book')) {
            $chapters = get_posts(array(
                'post_type' => 'chapter',
                'meta_key' => 'chapter_source',
                'meta_value' => get_the_ID(),
            ));
            if ($chapters) {
                $content .= '<h3>' . __('Chapters', 'afs') . '</h3><ul>';
                foreach ($chapters as $chapter) {
                    $content .= '<li><a href="' . get_permalink($chapter->ID) . '">' . esc_html($chapter->post_title) . '</a></li>';
                }
                $content .= '</ul>';
            }
        }
        return $content;
    }

    public function show_books_in_chapter($content)
    {
        if (is_singular('chapter')) {
            $book_id = get_post_meta(get_the_ID(), 'chapter_source', true);
            if ($book_id) {
                $book = get_post($book_id);
                $content = '<h3>' . __('From Book:', 'afs') . ' <a href="' . get_permalink($book->ID) . '">' . esc_html($book->post_title) . '</a></h3>' . $content;
            }
        }
        return $content;
    }

    public function show_related_books($content)
    {
        if (is_singular('book')) {
            $related_books = get_posts(array(
                'post_type' => 'book',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'rand'
            ));
            if ($related_books) {
                $content .= '<h3>' . __('Related Books', 'afs') . '</h3><ul>';
                foreach ($related_books as $book) {
                    $content .= '<li><a href="' . get_permalink($book->ID) . '">' . esc_html($book->post_title) . '</a></li>';
                }
                $content .= '</ul>';
            }
        }
        return $content;
    }
}
