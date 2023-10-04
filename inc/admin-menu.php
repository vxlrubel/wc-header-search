<?php

// directly access denied
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
    <h2>WC Header Search Settings</h2>


    <div class="wfd-wrap">
        <div class="side-left">
            <form method="post" action="options.php">
                <?php
                // show save changes message
                settings_errors();

                settings_fields('wc_header_search');
                do_settings_sections('wc-header-search');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Header Search:</th>
                        <td>
                            <label for="wc_header_search_enable">
                                <input type="checkbox" name="wc_header_search_enable" id="wc_header_search_enable" value="1" <?php checked(get_option('wc_header_search_enable'), 1); ?>>
                                Check this box to enable header search in frontend.
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Whats App:</th>
                        <td>

                            <input type="text" name="wc_whatsapp_number" id="wc_whatsapp_number" value="<?php echo esc_attr(get_option('wc_whatsapp_number')); ?>" />

                            <p class="description">Enter Your WhatsApp Number</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Color:</th>
                        <td>

                            <input type="color" name="wc_whatsapp_color" id="wc_whatsapp_color" value="<?php echo esc_attr(get_option('wc_whatsapp_color')); ?>" />

                            <p class="description">Chnage the color</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Background:</th>
                        <td>

                            <input type="color" name="wc_whatsapp_background" id="wc_whatsapp_background" value="<?php echo esc_attr(get_option('wc_whatsapp_background')); ?>" />

                            <p class="description">Chnage the background of WhatsApp Number</p>
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>
            </form> 
        </div>
        <div class="side-right">
            <div class="author-box">
                <div class="thumb">
                    <?php
                        $src = plugin_dir_url(__FILE__) . '../assets/img/author-image.jpg';
                        echo "<img src=\"{$src}\" alt=\"author-image\">";
                    ?>
                </div>
                <div class="desc">
                    <h3>Rubel Mahmud</h3>
                    <p>
                    I'm a 5-year experienced WordPress developer. I specialize in creating stunning, fast, and secure websites that align with your vision. Let's work together to bring your web projects to life!
                    </p>
                    <h4>Social Share:</h4>
                    <div class="social">
                        <a href="https://www.facebook.com/vxlrubel" target="_blank">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/vxlrubel?t=0Lpyc-zIA83HxTbgdd5L_w&s=09" target="_blank">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://instagram.com/vxlrubel?igshid=MzMyNGUyNmU2YQ==" target="_blank">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/vxlrubel" target="_blank">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.reddit.com/user/vxlrubel" target="_blank">
                            <i class="fa-brands fa-reddit-alien"></i>
                        </a>
                        <a href="https://github.com/vxlrubel" target="_blank">
                            <i class="fa-brands fa-github-alt"></i>
                        </a>
                    </div>
                    <div class="donate">
                        <a href="https://www.reddit.com/user/vxlrubel" target="_blank">Donate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    

</div>