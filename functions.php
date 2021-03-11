<?php

/**
 * Plugin Name:       Obscure Photo Gallery
 * Plugin URI:        https://auroratec.net/plugin/obscure
 * Description:       This plugin will create photo gallery for Obscure BD website
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Manishankar Vakta
 * Author URI:        https://auroratec.net/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       obscure-photo-gallery
 */
 
function my_plugin_assets() {

    wp_register_style( 'styleCss', plugins_url( '/css/style.css' , __FILE__ ), array(), '1.0', 'all' );
    wp_enqueue_style( 'styleCss' );
    

    wp_register_script( 'script', plugins_url( '/js/script.js' , __FILE__ ), array(), '1.0', false );
    wp_enqueue_script( 'script' );
}
add_action( 'wp_footer', 'my_plugin_assets' );


// create short code
function photo_gallery ( $atts ) {
	return gePhotos();
}
add_shortcode( 'obscure_gallery', 'photo_gallery' );


function gePhotos(){
    
    // echo '<h2>Obscure Photo Gallery</h2>';
    
    
    
    $photos = array();
    // $tp= get_post_by_post_parent(877);
    // var_dump($tp);
    
    $posts = getPhotoByTerms();
    // echo count($posts).'<br>';
    // echo count($photo);
    // echo '<pre>';
    // var_dump($posts);
    //  echo '</pre>';
    foreach($posts as $post){
        parse_str($post->guid, $url);
        
        $photo = get_post_by_post_parent($url["#038;p"]);
        foreach($photo as $pt){
            $pt->term_id = $post->term_id;
            array_push($photos, $pt);
            
        }
        
        // echo $url["#038;p"].'<br>'; //attachment post id
        
        // echo '<pre>';
        // var_dump($photos);
        // echo '</pre>';
        }
         
         
         
         
    // Crate gallery out put
    $gallery = '';
    
    
    // category sort  .' - '.$cat->term_id.'
    $gallery .= '<ul class="lists">';
    $gallery .= '<li class="li active" id="all">All</li>';
    $cats = getCategory();
    $terms = array();
    foreach($cats as $cat){
        
        $gallery .= '<li class="li" id="'.$cat->term_id.'">'.$cat->name.'</li>';
    }
    $gallery .= '</ul>';
    
    
    $gallery .= '<div class="container">';
    $gallery .= '<div class="row gallery">';
    foreach($photos as $photo){
        $gallery .= '<div class="box all Adelaide '.$photo->term_id.'" id="'.$photo->ID.'" style="background: url('.fileExt($photo->guid).');" >';
        $gallery .= '</div>';
    }
    $gallery .= '</div>';
    $gallery .= '</div>';
    
    
    echo $gallery;
    
}
    
    
function getCategory(){
    $taxonomies = array( 
        'photo-category',
    );
    
    $args = array(
        'orderby'           => 'name', 
        'order'             => 'ASC',
        'hide_empty'        => true, 
        'exclude'           => array(), 
        'exclude_tree'      => array(), 
        'include'           => array(),
        'number'            => '', 
        'fields'            => 'all', 
        'slug'              => '', 
        'parent'            => '',
        'hierarchical'      => true, 
        'child_of'          => 0, 
        'get'               => '', 
        'name__like'        => '',
        'description__like' => '',
        'pad_counts'        => false, 
        'offset'            => '', 
        'search'            => '', 
        'cache_domain'      => 'core'
    ); 
    
    $terms = get_terms( $taxonomies, $args );
    
    return $terms;
}
    
function fileExt($url){
    $ext = pathinfo(
        parse_url($url, PHP_URL_PATH), 
        PATHINFO_EXTENSION
    );
    
    if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'){
        
        return $url;
    }else{
        return false;
    }
}



function getPhotoByTerms(){
    global $wpdb;
    
    $categories = getCategory();
    $posts_array = array();
    foreach($categories as $category){
        // get_post_by_terms
        $posts = get_terms_post($category->term_id);  
            
            foreach($posts as $post){
                $post->term_id = $category->term_id;
                // $id = ['term_id'=>;
                // $post = array_push($post, $id);
                array_push($posts_array, $post);
            }
        
    }
    return $posts_array; 
}
        

function get_terms_post($category){
    $posts = get_posts(
                array(
                    'posts_per_page' => -1,
                    'post_type' => 'photo',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'photo-category',
                            'field' => 'term_id',
                            'terms' => $category,
                        )
                    )
                )
            );
            
        return $posts;
}

function get_post_by_post_parent($parent){
    global $wpdb;
    
    $sql = 'SELECT * FROM wp_posts WHERE post_parent="'.$parent.'"';
    $photos = $wpdb->get_results($sql);
    
    return $photos;
}

