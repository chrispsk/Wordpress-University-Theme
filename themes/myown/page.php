<!-- This is for individual page -->

<!-- This is for individual posts -->
<?php
get_header();

if (have_posts()){
    while(have_posts()){    
    the_post();  
    pageBanner(array(
      'title' => 'Hello there this is the title',
      'subtitle' => 'Hi this is the subtitle',
      'photo' => 'https://www.phdmedia.com/estonia/wp-content/uploads/sites/56/2017/06/Banner-2.gif'
    ));  
?>

<!-- Start html -->


  <div class="container container--narrow page-section">
        <?php
        $theParent = wp_get_post_parent_id(get_the_ID()); 
        if($theParent) {
?>
<div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_the_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>
<?php
        }
        ?>
    
    <?php 
    $testArray = get_pages(array(
        'child_of' => get_the_ID()
    ));
    if($theParent or $testArray) { ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
      <ul class="min-list">
        <?php
            if($theParent) {
               $findchildof = $theParent; 
            } else {
                $findchildof = get_the_ID();
            }
            wp_list_pages(array(
                'title_li'=>'',
                'child_of' => $findchildof 
            ));
        ?>
      </ul>
    </div>
        <?php } ?>
    <div class="generic-content">
        <?php the_content(); ?>
</div>

  </div>

<!-- End html -->
<?php
    }
    
} else {
    echo ("No posts");
} 

get_footer();
?>