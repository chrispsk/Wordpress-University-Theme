<?php
get_header();
pageBanner(array(
  'title' => 'Welcome to our blog',
  'subtitle' => 'Keep up with our latest news!'
));
?>


<div class="container container--narrow page-section">
<?php
if (have_posts()){
    
    while(have_posts()){
        
    the_post();    ?>
        
    <article class="post-item">
        
    <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="metabox">
      <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in <?php echo get_the_category_list(', '); ?></p>
    </div>
    <div class="generic-content">
    <?php the_excerpt(); ?>
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
    </div>    
    
        </article>
        
    
    <?php
        }
         echo paginate_links(); 
    } else {
        echo ("No posts");
    } 
    
    ?>
</div>

<?php
get_footer();
?>