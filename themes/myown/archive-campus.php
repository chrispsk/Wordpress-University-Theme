<?php
get_header();
pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'We have several conveniently locations.'
));
?>

<div class="container container--narrow page-section">
<ul class="link-list min-list">
<?php
if (have_posts()){
    
    while(have_posts()){
        
    the_post();    ?>
        
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); 
        
        ?></a></li>
        
    
    <?php
        } 

    } else {
        echo ("No posts");
    } 
    
    ?>
</ul>

</div>

<?php
get_footer();
?>