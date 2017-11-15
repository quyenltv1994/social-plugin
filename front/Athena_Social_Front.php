<?php
require_once PLUGIN_DIR."api/Athena_Social_Object.php";

Class Athena_Social_Front{

    public function __construct(){
        add_action( 'wp_enqueue_scripts', array($this, 'load_assets') );
        add_shortcode('athena_socials', array($this, 'show_Front'));
    }

    //load assets
    public function load_assets(){
        wp_register_style( 'isotope-css', plugins_url('css/athena_social_style.css',__FILE__ ));
        wp_enqueue_style( 'isotope-css' );
        wp_register_script( 'isotope-js', plugins_url('js/isotope-master/dist/isotope.pkgd.min.js',__FILE__ ), null, '1.0.0', true );
        wp_enqueue_script( 'isotope-js' );
        wp_register_script( 'athena-social-js', plugins_url('js/athena_social_js.js',__FILE__ ), null, '1.0.0', true );
        wp_enqueue_script( 'athena-social-js' );
    }

    //get results
    public function getResults(){
        $args = array(
            'posts_per_page'=> 10,
            'post_type' => array( 'facebook', 'youtube', 'twitter' ),
            'order_by' => 'date',
            'order' => 'DECS'
        );
        $query = new WP_Query($args);
        if($query->have_posts()){
            $results = array();
            while($query->have_posts()) : $query->the_post();
                global $post;
                $photo = get_post_meta($post->ID, 'social_photo', true);
                $social_link = get_post_meta($post->ID, 'social_link', true);
                $social_created_time = get_post_meta($post->ID, 'social_created_time', true);
                $object = new Athena_Social_Object();
                $object->setTitle($post->post_title);
                $object->setContent($post->post_content);
                $object->setCreateTime($post->post_date);
                $object->setPostType($post->post_type);
                $object->setPhoto($photo);
                $object->setLink($social_link);
                $results[] =  $object;
            endwhile;
            return $results;
        }
        return false;
    }

    public function show_Front($atts){
        $results = $this->getResults();
        require_once plugin_dir_path(dirname(__FILE__)).'front/templates/athena-social-display.php';
    }
}