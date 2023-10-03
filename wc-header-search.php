<?php
/*
Plugin Name: WC Header Search
Description: Elevate your WooCommerce store's user experience with the WC Header Search plugin. Allow your customers to quickly find products with a powerful and intuitive search feature conveniently located in the website header. Boost conversion rates and customer satisfaction by simplifying the product discovery process. Enhance your online store's functionality and make shopping effortless with WC Header Search.
Version: 1.0
Author: Rubel Mahmud
Author URi: https://www.linkedin.com/in/vxlrubel
*/


// directly access deniyed

defined('ABSPATH') OR exit('No direct script access allowed');

if( ! class_exists('WC_Header_Search') ){

    class WC_Header_Search{
        // create singletone instance of WC_Header_Search
        private static $instance;

        public function __construct(){
            // settings link
            add_filter( 'plugin_action_links', [ $this, 'create_settings_link'], 10, 2 );
            
            // documentation
            add_filter( 'plugin_row_meta', [ $this, 'documentation' ], 10, 2 );

            // enqueue_script
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );

            // get the search result using AJAX technology
            add_action('wp_ajax_wch_get_products', [ $this, 'get_search_result' ] );
            add_action('wp_ajax_nopriv_wch_get_products', [ $this, 'get_search_result' ] );

        }

        /**
         * create setting link 
         *
         * @param [type] $links
         * @param [type] $file
         * @return $links
         */
        public function create_settings_link( $links, $file ){
            if( plugin_basename( __FILE__ ) === $file ){
                $url = '#';
                $settings = "<a href=\"{$url}\">Settings</a>";
                array_unshift( $links, $settings );
            }
            return $links;
        }

        /**
         * create documetation page links for WC_Header_Search
         *
         * @param [type] $meta
         * @param [type] $file
         * @return $meta
         */
        public function documentation( $meta, $file ){
            if( plugin_basename( __FILE__) === $file ){
                $url = '#';
                $meta[] = "<a href=\"{$url}\">Documentation</a>";
            }
            return $meta;
        }

        /**
         * enqueue plugin scripts
         *
         * @return void
         */
        public function enqueue_script(){
            
            // nequeue style
            wp_enqueue_style( 'wc-header-search-style', plugins_url( 'assets/css/main.css', __FILE__ ) );

            // enqueue script
            wp_enqueue_script( 'wc-header-search-script', plugins_url( 'assets/js/custom.js', __FILE__ ), ['jquery'], '1.0', true );

            // transfer data into Javascript file
            $logo_id = get_theme_mod('custom_logo');
            $logo_url = esc_url( wp_get_attachment_url( $logo_id ) );
            
            $args = [
                'ajaxurl'  => admin_url( 'admin-ajax.php' ),
                'logo_url' => $logo_url,
                'site_url' => esc_url( home_url('/') )
            ];
            wp_localize_script( 'wc-header-search-script', 'wch', $args );
        }


        /**
         * get the search results of the product
         *
         * @return void
         */
        public function get_search_result(){
            
            $data = [];

            if( isset( $_GET['action'] ) && isset( $_GET['action'] ) == 'wch_get_products' ){
                // $data = 'products';

                if( $_GET['terms'] !== '' ){
                    $terms = $_GET['terms'];
                }else{
                    exit();
                }
                
                $args = [
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    's'              => $terms
                ];

                $qry = new WP_Query( $args );

                if( $qry->have_posts() ){
                    while( $qry->have_posts() ): $qry->the_post();
                        $data[] = [
                            'title'   => get_the_title(),
                            'content' => wp_trim_words( get_the_content( get_the_ID() ), 15, '' ),
                            'links'   => esc_url( get_permalink() ),
                            'thumb'   => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                        ];
                    endwhile;
                }else{
                    $data[]= [
                        'not_found' => 'No Product Found'
                    ];
                }
                wp_reset_postdata();

                
            }else{
                $data[] = 'You are not allowed to...';
            }
            
            wp_send_json_success( $data );
        }

        /**
         * create instance of WC_Header_Search
         *
         * @return void
         */
        public static function init(){
            if( is_null(self::$instance) ){
                self::$instance = new self();
            }
            return self::$instance;
        }
        
    }

}
if( ! function_exists('wc_header_search')){
    function wc_header_search(){
        return WC_Header_Search::init();
    }
}
wc_header_search();