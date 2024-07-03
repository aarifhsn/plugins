<?php

function afdevs_author_bio_methods($methods)
{
    $methods['facebook'] = __('Facebook', 'afdevs');
    $methods['twitter'] = __('Twitter', 'afdevs');
    $methods['linkedIn'] = __('LinekIn', 'afdevs');
    return $methods;
};

add_filter('user_contactmethods', 'afdevs_author_bio_methods');
