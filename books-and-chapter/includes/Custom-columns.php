<?php

class Custom_Columns
{
    public function add_to_book($columns)
    {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key == 'title') {
                $new_columns['book_author'] = __('Author', 'afs');
            }
        }
        return $new_columns;
    }

    public function manage_book($column, $post_id)
    {
        if ($column == 'book_author') {
            $book_author = get_post_meta($post_id, 'book_author', true);
            echo esc_html($book_author);
        }
    }

    public function add_to_chapter($columns)
    {
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key == 'title') {
                $new_columns['book'] = __('Book', 'afs');
            }
        }
        return $new_columns;
    }

    public function manage_chapter($column, $post_id)
    {
        if ($column == 'book') {
            $book_id = get_post_meta($post_id, 'chapter_source', true);
            $book = get_post($book_id);
            if ($book) {
                echo '<strong><a href="' . get_permalink($book->ID) . '">' . esc_html($book->post_title) . '</a></strong>';
            }
        }
    }
}
