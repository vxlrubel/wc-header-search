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
            add_action( 'wp_enqueue_script', [ $this, 'enqueue_script' ] );

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
            wp_enqueue_style( 'wc-header-search-style', plugins_url( 'assets/css/main.css', __FILE__ ) );
            wp_enqueue_script( 'wc-header-search-script', plugins_url( 'assets/js/custom.js', __FILE__ ), ['jquery'], '1.0', true );
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