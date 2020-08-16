<?php 

if(!is_user_logged_in()) {
    wp_redirect(site_url('/'));
    exit;
} 
?>
This page is visible only if you are logged in. <br>
Hello <?php 
$ourUser = wp_get_current_user();
echo $ourUser->user_login; 
?>


