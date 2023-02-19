<?php

// require __DIR__ . '/includes/veranstaltungen.php';

// Enqueue Child CSS
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );

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

// Enable Featured Images
function pk_features(){
    add_theme_support('post-thumbnails');
    add_image_size('landscape', 400, 260, true );
    add_image_size('portrait', 480, 650, true );
    add_image_size('pageBanner', 1500, 350, true );
}

add_action('after_setup_theme', 'pk_features');

// Register Custom Post Types 

function pk_post_types(){
    // Post Type Veranstaltung
    register_post_type( 'veranstaltung', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor','excerpt', 'comments', 'thumbnail'), # Maskenfelder 
        'capability_type' => 'veranstaltung', # Un Rechte gesondert zu steuern
        'map_meta_cap' => true,
        'rewrite' => array(
            'slug' => 'veranstaltungen'  
        ),
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => 'Veranstaltungen',
            'add_new_item' => 'Neue Veranstaltung',
            'edit_item' => 'Veranstaltung bearbeiten',
            'all_items' => 'Alle Veranstaltungen',
            'singular_name' => 'Veranstaltung'
        )
    ) );

    // Post Type class
    register_post_type( 'class', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),  
        'rewrite' => array(
            'slug' => 'classes'  
        ),
        'menu_icon' => 'dashicons-format-video',
        'labels' => array(
            'name' => 'Classes',
            'add_new_item' => 'Neue Class',
            'edit_item' => 'Classes bearbeiten',
            'all_items' => 'Alle Classes',
            'singular_name' => 'Class'
        )
    ) );

    // Post Type Video
    register_post_type( 'video', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),  
        'rewrite' => array(
            'slug' => 'videos'  
        ),
        'menu_icon' => 'dashicons-format-video',
        'labels' => array(
            'name' => 'Videos',
            'add_new_item' => 'Neues Video',
            'edit_item' => 'Videos bearbeiten',
            'all_items' => 'Alle Videos',
            'singular_name' => 'Video'
        )
    ) );

    // Post Type Referenzen
    register_post_type( 'referenz', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
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

    // Post Type News
    register_post_type( 'news', array(
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor','excerpt', 'thumbnail'),   
        'menu_icon' => 'dashicons-media-text',
        'labels' => array(
            'name' => 'News',
            'add_new_item' => 'Neue News',
            'edit_item' => 'News bearbeiten',
            'all_items' => 'Alle News',
            'singular_name' => 'News'
        )
    ) );
}

add_action( 'init', 'pk_post_types');

// ------------------------------------

// Um die Sortierung in Archive zu manipulieren 

function pk_adjust_queries($query){
    if(!is_admin() AND is_post_type_archive('video') AND $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() AND is_post_type_archive('veranstaltung') AND $query->is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key', 'veranstaltungsdatum');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'veranstaltungsdatum',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action( 'pre_get_posts', 'pk_adjust_queries' );

// -----------------------------------------

// Zur Nutzung von Google Maps

function pk_map_key($api){
    $api['key'] = 'AIzaSyD7J5yFUOtb0wI5ijYcJeS1WujBviE5zQY';
    return $api;
}

add_filter('acf/fields/google_map/api', 'pk_map_key');

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


// Titel ändern
function ourLoginTitle(){
    // return get_bloginfo('name');
    return 'Peter seine Website';
}

add_filter('login_headertext','ourLoginTitle');

