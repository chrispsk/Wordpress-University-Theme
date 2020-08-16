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
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

  <div class="generic-content">
      <?php the_content(); ?>
  </div>
  
  
  <?php
  //######### GET THE RELATED Professor ########## 
  $rePro = new WP_Query(array(
    'posts_per_page' => -1, //-1 means all pages
    'post_type' => 'professor',
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . get_the_ID() . '"'
      ) 
    )
  ));
  if($rePro->have_posts()) {
    echo '<hr class="section-break">';
  echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';

  echo "<ul class='professor-cards'>";
  while($rePro->have_posts()) {
    $rePro->the_post(); ?>
    <li class="professor-card__list-item">
    <a class="professor-card" href="<?php the_permalink(); ?>">
      <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="professor-card__image">
      <span class="professor-card__name"><?php the_title(); ?></span>
    </a></li>
  <?php
  }
  echo "</ul>";
  }

        wp_reset_postdata();
  //######### GET THE RELATED EVENTS ########## 
        $homeEvents = new WP_Query(array(
          'posts_per_page' => 2, //-1 means all pages
          'post_type' => 'event',
          'meta_key' => 'event_date',
          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => date('Ymd'), //today
              'type' => 'numeric'
            ),
            array(
              'key' => 'related_programs',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            ) 
          )
        ));
        if($homeEvents->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        while($homeEvents->have_posts()) {
          $homeEvents->the_post(); 
          get_template_part('template_parts/content-event');
        }
        }
  
        wp_reset_postdata();

        $relatedCampuses = get_field("related_campus");
        if($relatedCampuses){
          echo "<hr class='section-break'>";
          echo '<h2 class="headline headline--medium">'. get_the_title() .' is available at these campuses</h2>';
          echo "<ul class='min-list link-list'>";
          foreach($relatedCampuses as $c){
            ?>
          <li><a href="<?php echo get_the_permalink($c); ?>"><?php echo get_the_title($c); ?></a></li>
            <?php
          }
          echo "</ul>";
        }

        ?>

    </div>


<?php
    }
    
} else {
    echo ("No posts");
} 

get_footer();
?>