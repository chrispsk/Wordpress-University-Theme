<!-- This is for individual posts -->
<?php
get_header();

if (have_posts()){
    while(have_posts()){    
    the_post(); 
    pageBanner();
    ?>
    

  <div class="container container--narrow page-section">

  <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Events </a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

  <div class="generic-content">
      <?php the_content(); ?>
  </div>
      <?php 
      $relProg = get_field('related_programs'); //array
      //print_r($relProg);
      if($relProg) {
        echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
      echo '<ul class="link-list min-list">';
      foreach($relProg as $p) { ?>
        <li><a href="<?php echo get_the_permalink($p); ?>"><?php echo get_the_title($p); ?></a></li>
        <?php } 
     echo '</ul>'
     ?>
    <?php } ?>
      
      
        
     
    </div>


<?php
    }
    
} else {
    echo ("No posts");
} 

get_footer();
?>