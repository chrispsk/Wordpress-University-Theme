<?php
## get rid of annoying jquery in console
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});

## change default wordpress email sender name ##
function new_mail_from($old) {
return 'chris@danv.sgedu.site';
}

function new_mail_from_name($old) {
return 'Chris';
}
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');


####### My OWN ROUTE ###########
add_action('rest_api_init', 'uniRegSearch');

function uniRegSearch() {
    register_rest_route('goapi/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE, //or GET
        'callback' => 'uniResults'
    ));
    // ####### LIKE END POINTS POST ########
    // register_rest_route('goapi/v1', 'manageLike', array(
    //     'methods' => 'POST', //or GET
    //     'callback' => 'createLike'
    // ));

    // ####### LIKE END POINTS DELETE ########
    // register_rest_route('goapi/v1', 'manageLike', array(
    //     'methods' => 'DELETE', //or GET
    //     'callback' => 'deleteLike'
    // ));
}

// function createLike($data) {
//     if(is_user_logged_in()) {
//         $prof = sanitize_text_field($data['professorId']);
    
//         return wp_insert_post(array(
//         'post_type' => 'Like',
//         'post_status' => 'publish',
//         'post_title' => 'Our PHP Create Post Test',
//         'meta_input' => array(
//             'liked_professor_id' => $prof
//         )
//         //'post_content' => 'Hello content'
//     ));
//     } else {
//         die("Only logged in users can create a like");
//     }
    
// }

// function deleteLike() {
//     return "Thanks for deleting a Like";
// }

function uniResults($data) {
    // professors
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'professor', 'page', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' =>array(),
        'events' => array(),
        'campuses' => array()
    );
    
    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if(get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
        
        if(get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0)
            ));
        }

        if(get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if(get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
              } else {
                $description = wp_trim_words(get_the_content(), 18);
              }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description 
            ));
        }

        if(get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');
            if($relatedCampuses) {
            foreach($relatedCampuses as $campus) {
                array_push($results['campuses'], array(
                   'title' => get_the_title($campus),
                   'permalink' => get_the_permalink($campus)   
                ));
            }
        }
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        }
    }

    if($results['programs']) {
        $programsMetaQuery = array('relation' => 'OR');

    foreach($results['programs'] as $item) {
        array_push($programsMetaQuery, array(
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '""' . $item['id'] . '""'
        ));
    }

    $programRel = new WP_Query(array(
        'post_type' => array('professor', 'event'),
        'meta_query' => $programsMetaQuery
    ));

    while($programRel->have_posts()) {
        $programRel->the_post();

        if(get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
              } else {
                $description = wp_trim_words(get_the_content(), 18);
              }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description 
            ));
        }

        if(get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0)
            ));
        }
    }
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }
    
    return $results;
}

#######################################

//add new proprety in the rest called authorName
function univ_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));
}
add_action('rest_api_init', 'univ_custom_rest');

function pageBanner($args = NULL){
    if(!$args['title']){ //if no title is provided
        $args['title']=get_the_title();
    }
    if(!$args['subtitle']){ //if no title is provided
        $args['subtitle']=get_field('page_banner_subtitle');
    }
    if(!$args['photo']){ //if no title is provided
        if(get_field('page_banner_background')){
            $args['photo'] = get_field('page_banner_background')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php 
    echo $args['photo'];
    ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php }

function resource_css() {
    //wp_enqueue_script('jquery'); 
    wp_enqueue_script('ajax', '//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    wp_enqueue_script('main', get_theme_file_uri('/js/modules/Search.js'), NULL, '1.0', true);
    //wp_enqueue_script('like', get_theme_file_uri('/js/modules/Like.js'), NULL, '1.0', true);
    wp_enqueue_script('main-univ', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

    wp_localize_script('main', 'uniData', array(
        'root_url' => get_site_url()
    ));
}

add_action('wp_enqueue_scripts', 'resource_css');


function uni_feat() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); //this will enable for blog post type
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'uni_feat');

function uni_adjust_queries($query) {
    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()){
        $query->set('posts_per_page', '-1');
    }

    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
        $query->set('posts_per_page', '-1');
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }

    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) { //not in the dashboard
        // $query->set('posts_per_page', '1');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => date('Ymd'),
            'type' => 'numeric'
        )); 
    }
}

add_action('pre_get_posts', 'uni_adjust_queries');

function universityMapKey($api){
    $api['key'] = 'AIzaSyA4zbtMFU6TW0tunfV6l2hMSSj2ns7H24c';
    return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');

// REDIRECT SUBSCRIBER ACCONTS OUT OF ADMIN INTO FRONTPAGE

function redirectSubs(){
    $ourUser = wp_get_current_user();
    if(count($ourUser->roles) == 1 AND $ourUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('admin_init', 'redirectSubs');

function noSubsAdminBar(){
    $ourUser = wp_get_current_user();
    if(count($ourUser->roles) == 1 AND $ourUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

//Customize login screen
//Change the link of wordpress logo
function ourHeader() {
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'ourHeader');

//overwrite the css of the login page and customize it
function ourLoginCss() {
    wp_enqueue_style('style', get_theme_file_uri('/css/modules/login.css'));
    wp_enqueue_style('fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}
add_action('login_enqueue_scripts', 'ourLoginCss');

function loginTitle() {
    return get_bloginfo('name');
}

add_filter('login_headertitle', 'loginTitle');

#### EXCLUDE (git ignore) for all in one wp migration plugin #####

function ignore($excl){
    $excl[] = 'themes/myown/node_modules';
    return $excl;
}

add_filter('ai1wm_exclude_content_from_export', 'ignore');