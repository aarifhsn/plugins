<?php

class Admin_Menu
{
    public function move_chapter_to_book()
    {
        remove_menu_page('edit.php?post_type=chapter');
        add_submenu_page('edit.php?post_type=book', 'Chapters', 'All Chapters', 'edit_posts', 'edit.php?post_type=chapter');
    }
}
