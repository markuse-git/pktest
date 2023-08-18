<?php

// Enqueue Child CSS
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );

// Enqueue CSS für das Login (selbe CSS s.o.)
function ourLoginCSS(){
    wp_enqueue_style('pk_login_style',get_stylesheet_uri());
}

add_action('login_enqueue_scripts','ourLoginCSS');

// -------------------------------------------------------------------

// pageBanner
function pageBanner($args = NULL){ 
    if(!isset($args['title'])){
        $args['title'] = get_the_title();
    }
    if(!isset($args['subtitle'])){
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if(!isset($args['photo'])){
        if(get_field('page_banner_background_image')){
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else{
            $args['photo'] = get_theme_file_uri( '/uploads/2022/05/qualifikation.jpg' );
        }
    }

    ?>
    <img src="<?php echo $args['photo'];?>">
    <?php            
    echo $args['title'];
    echo $args['subtitle'];
}

// -----------------------------------------------------------------

// Enable Featured Images
// function pk_features(){
//     add_theme_support('post-thumbnails');
//     add_image_size('landscape', 400, 260, true );
//     add_image_size('portrait', 480, 650, true );
//     add_image_size('pageBanner', 1500, 350, true );
// }

// add_action('after_setup_theme', 'pk_features');

// --------------------------------------------------------

// Register Custom Post Types 
function pk_post_types(){

    // Post Type class
    register_post_type( 'class', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'capability_type' => 'class', # zur Rechtesteuerung in Members
        'map_meta_cap' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),  
        'rewrite' => array(
            'slug' => 'classes'  
        ),
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => 'Classes',
            'add_new_item' => 'Neue Class',
            'edit_item' => 'Classes bearbeiten',
            'all_items' => 'Alle Classes',
            'singular_name' => 'Class'
        )
    ) );

    // Post Type referenz
    register_post_type( 'referenz', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'capability_type' => 'referenz', # zur Rechtesteuerung in Members
        'map_meta_cap' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),   
        'rewrite' => array(
            'slug' => 'referenzen'  
        ),
        'menu_icon' => 'dashicons-thumbs-up',
        'labels' => array(
            'name' => 'Referenzen',
            'add_new_item' => 'Neue Referenz',
            'edit_item' => 'Referenz bearbeiten',
            'all_items' => 'Alle Referenzen',
            'singular_name' => 'Referenz'
        )
    ) );

    // Post Type clipping
    register_post_type( 'clipping', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'capability_type' => 'clipping', # zur Rechtesteuerung in Members
        'map_meta_cap' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),   
        'rewrite' => array(
            'slug' => 'clippings'  
        ),
        'menu_icon' => 'dashicons-media-document',
        'labels' => array(
            'name' => 'Clippings',
            'add_new_item' => 'Neues Clipping',
            'edit_item' => 'Clipping bearbeiten',
            'all_items' => 'Alle Clippings',
            'singular_name' => 'Clipping'
        )
    ) );
    
}

add_action( 'init', 'pk_post_types');

// ------------------------------------

// Um die Sortierung in Archive zu manipulieren 
function pk_adjust_queries($query){
    // if(!is_admin() AND is_post_type_archive('video') AND $query->is_main_query()){
    //     $query->set('orderby', 'title');
    //     $query->set('order', 'ASC');
    //     $query->set('posts_per_page', -1);
    // }

    if(!is_admin() AND is_post_type_archive('clipping') AND $query->is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key', 'publication_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'DESC');
        // $query->set('meta_query', array(
        //     array(
        //         'key' => 'publication_date',
        //         'compare' => '>=',
        //         'value' => $today,
        //         'type' => 'numeric'
        //     )
        // ));
    }
}

add_action( 'pre_get_posts', 'pk_adjust_queries' );

// -----------------------------------------


// -------------------------------------------

// Redirect subscriber accounts to homepage
function redirectSubsToFrontend(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('admin_init', 'redirectSubsToFrontend');

// hide admin bar for subscribers
function noSubsBar(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        show_admin_bar(false);
    }
}

add_action('wp_loaded', 'noSubsBar');

// --------------------------------------------

// Login Window

// Title Link ändern
function ourHeaderUrl(){
    return esc_url(site_url('/'));
}

add_filter('login_headerurl','ourHeaderUrl');


// Titel/Logo ändern
function ourLoginTitle(){
    return get_bloginfo('name');
    // return 'Peter seine Website';
}

add_filter('login_headertext','ourLoginTitle');


