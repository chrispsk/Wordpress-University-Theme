<?php
get_header();
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'See what is going on in our world!' 
));
?>


<div class="container container--narrow page-section">
<?php
if (have_posts()){
    
    while(have_posts()){
    the_post();   
    get_template_part('template_parts/content-event');
    
        }
         echo paginate_links(); 

    } else {
        echo ("No posts");
    } 
    
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Click Here</a></p>
</div>

<?php
get_footer();
?>