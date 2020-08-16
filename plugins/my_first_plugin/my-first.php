<?php
/*
Plugin Name: My First Plugin
Description: This will change your skills
*/

function amazingEdits($content) {
    $content = $content . '<p>All contents belongs to Me</p>';
    $content = str_replace('Lorem', '***', $content);
    return $content;   
}
add_filter('the_content', 'amazingEdits');

function programCountFunction(){
    $count_posts = wp_count_posts('program')->publish;
	return $count_posts;
}
add_shortcode('programCount', 'programCountFunction');