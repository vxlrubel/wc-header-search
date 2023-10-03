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