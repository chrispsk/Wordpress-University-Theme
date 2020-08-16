<?php
get_header();
pageBanner(array(
  'title' => 'Search Results',
  'subtitle' => 'You searched for: ' . get_search_query()
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
        echo ("No Results Match That Search");
    } 
    ?>
    
    <div class="generic-content">
    <form class="search-form" action="<?php echo esc_url(site_url('/')); ?>" method="get">
    <div class="search-form-row">
    <input class="s" type="search" name="s" placeholder="What are you looking for?">
    <input class="search-submit" type="submit" value="Search">
    </div>
    </form>
</div>
</div>

<?php
get_footer();
?>