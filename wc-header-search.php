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

            // create admin page
            add_action( 'admin_menu', [ $this, 'wc_admin_menu' ] );

            // register settings
            add_action('admin_init', [ $this, 'wc_header_search_settings'] );

            // create admin notice if WooCommerce is not active
            add_action( 'admin_notices', [ $this, 'admin_notice_if_woocommerce_is_not_active' ] );


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
                $url = esc_url( admin_url('admin.php'). '?page=wc-header-search' );
                $download_url = 'https://github.com/vxlrubel/wc-header-search/archive/refs/heads/main.zip';
                $settings = "<a href=\"{$url}\">Settings</a> | ";
                $settings .= "<a href=\"{$download_url}\">Download</a>";
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
                $url = 'https://github.com/vxlrubel/wc-header-search';
                $meta[] = "<a href=\"{$url}\" target=\"_blank\">Documentation</a>";
            }
            return $meta;
        }

        /**
         * enqueue plugin scripts
         *
         * @return void
         */
        public function enqueue_script(){
            if( get_wc_header_search_enable() ){
                // nequeue style
                wp_enqueue_style( 'wc-header-search-style', plugins_url( 'assets/css/main.css', __FILE__ ) );

                // enqueue script
                wp_enqueue_script( 'wc-header-search-script', plugins_url( 'assets/js/custom.js', __FILE__ ), ['jquery'], '1.0', true );

                // transfer data into Javascript file
                $logo_id     = get_theme_mod('custom_logo');
                $logo_url    = esc_url( wp_get_attachment_url( $logo_id ) );
                $whats_app   = get_option('wc_whatsapp_number');
                $whats_bg    = get_option('wc_whatsapp_background') ? get_option('wc_whatsapp_background') : '#ffffff';
                $whats_color = get_option('wc_whatsapp_color') ? get_option('wc_whatsapp_color') : '#444444';
                
                $args = [
                    'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                    'logo_url'       => $logo_url,
                    'site_url'       => esc_url( home_url('/') ),
                    'whatsapp'       => $whats_app,
                    'whatsapp_bg'    => $whats_bg,
                    'whatsapp_color' => $whats_color
                ];
                wp_localize_script( 'wc-header-search-script', 'wch', $args );
            }
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
         * create a admin menu
         *
         * @return void
         */
        public function wc_admin_menu(){
            add_submenu_page(
                'woocommerce',                  // parent slug
                'WC Header Search',             // page title
                'WC Header Search',             // menu title
                'edit_posts',                   //capability
                'wc-header-search',             // menu slug
                [ $this, '_cb_header_search'],  // callback
                110                             // position
            );
        }

        /**
         * create admin page
         *
         * @return void
         */
        public function _cb_header_search(){
            require_once dirname(__FILE__) . '/inc/admin-menu.php';
        }

        /**
         * register header search settings
         *
         * @return void
         */
        public function wc_header_search_settings(){
            register_setting('wc_header_search', 'wc_header_search_enable', 'intval');
            register_setting('wc_header_search', 'wc_whatsapp_number');
            register_setting('wc_header_search', 'wc_whatsapp_color');
            register_setting('wc_header_search', 'wc_whatsapp_background');
        }

        /**
         * admin notice if woocommerce is not active
         *
         * @return void
         */
        public function admin_notice_if_woocommerce_is_not_active(){
            if( ! class_exists('WooCommerce') ){
                $notice = '
                    <div class="notice notice-error is-dismissible">
                        <p>WC Header Search requires WooCommerce to be installed and activated. Please activate WooCommerce to use this plugin.</p>
                    </div>
                ';
                echo $notice;
            }
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

// Retrieve the checkbox value in the frontend
function get_wc_header_search_enable() {
    return get_option('wc_header_search_enable');
}
