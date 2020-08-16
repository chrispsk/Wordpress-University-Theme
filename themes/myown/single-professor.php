<!-- This is for individual posts -->
<?php
get_header();

if (have_posts()){
    while(have_posts()){    
    the_post(); 
    pageBanner();
    ?>
    

  <div class="container container--narrow page-section">

  <div class="generic-content">
      <div class="row group">
        <div class="one-third"><?php the_post_thumbnail(); ?></div>
        <div class="two-thirds">
        <?php 
        // $likeCount = new WP_Query(array(
        //   'post_type' => 'like',
        //   'meta_query' => array(
        //     array(
        //       'key' => 'liked_professor_id',
        //       'compare' => '=',
        //       //the ID Professor I am viewing
        //       'value' => get_the_ID() 
        //     )
        //   )
        // ));

        // $existStatus = 'no';

        // $existQuery = new WP_Query(array(
        //   'author' => get_current_user_id(),
        //   'post_type' => 'like',
        //   'meta_query' => array(
        //     array(
        //       'key' => 'liked_professor_id',
        //       'compare' => '=',
        //       //the ID Professor I am viewing
        //       'value' => get_the_ID() 
        //     )
        //   )
        // ));

        // if($existQuery->found_posts) {
        //   $existStatus = 'yes';
        // }
        ?>
        <span class="like-box" data-professor="<?php the_ID(); ?>" data-exists="<?php //echo $existStatus; ?>yes">
        <i class="fa fa-heart-o" aria-hidden="true"></i>
        <i class="fa fa-heart" aria-hidden="true"></i>
        <span class="like-count"><?php //echo $likeCount->found_posts; ?>3</span>
        </span>
        <?php the_content(); ?>
        </div>
      </div>
  </div>
      <?php 
      $relProg = get_field('related_programs'); //array
      //print_r($relProg);
      if($relProg) {
        echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
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