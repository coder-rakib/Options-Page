<?php
/**
 * Plugin Name: Test
 * Plugin URI:  
 * Description: Kaku settings API
 * Version:     1.0.0
 * Author:      Rakib
 * Author URI:  
 * License:     GPL v2 or later
 * Text Domain: test
 * Domain Path: /languages
 */
function test_load_textdomai(){
    load_plugin_textdomain('test', false, dirname(__FILE__)."/languages");
}
add_action('plugins_loaded','test_load_textdomai');

/**
 * Create admin menu page
 **/
function kaku_admin_menu(){

    //Create parent menu page
    add_menu_page( __('Kaku Theme Options','test'), __('Kaku Theme Options','test'), 'manage_options', 'home_settings', 'homepage_callback' );

    //Create child menu page
    add_submenu_page( 'home_settings', __('Sub Menu Options','test'), __('Sub menu settings','test'), 'manage_options', 'submenu_settings', 'submenu_callback' );

/**
* Register Settings API
*/
add_action('admin_init','kaku_settings_api');
}

add_action('admin_menu', 'kaku_admin_menu');


/**
 * Register and create options field 
 */
function kaku_settings_api(){
    /**
     * Section One options
     */
    add_settings_section( 'kaku_sidebar_options', __( 'Section one','test'), 'kaku_sidebar_options_callback','home_settings' );

    //Register settings for section one
    register_setting('kaku_settings_group', 'f_name');
    register_setting('kaku_settings_group', 'l_name');
    register_setting('kaku_settings_group','twitter_url');
    register_setting('kaku_settings_group','kaku_countries');

    // Add settings field for section one
    add_settings_field( 'f_name', __('Full Name','test'), 'name_callback', 'home_settings', 'kaku_sidebar_options' );
    add_settings_field( 'twitter_url', __('Twitter URL','test'), 'twitter_callback', 'home_settings', 'kaku_sidebar_options' );
    add_settings_field( 'kaku_drowpdown', __('Select country','test'), 'kaku_drowpdown_callback', 'home_settings', 'kaku_sidebar_options' );


    /**
     * Section Two options
     */
    add_settings_section( 'section_two_options', __( 'Section Two','test'), 'section_two_options_callback','home_settings' );

    //Register settings for section two
    register_setting('kaku_settings_group','kaku_checkbox');
    register_setting('kaku_settings_group','kaku_radio');
    register_setting('kaku_settings_group','kaku_image', 'handle_file_upload');

    // Add settings field for section two
    add_settings_field( 'kaku_checkbox', __('Select country','test'), 'kaku_checkbox_callback', 'home_settings', 'section_two_options' );
    add_settings_field( 'kaku_radio', __('Choose options','test'), 'kaku_radio_callback', 'home_settings', 'section_two_options' );
    add_settings_field( 'kaku_image', __('Upload Image','test'), 'kaku_image_callback', 'home_settings', 'section_two_options' );
}

/**
 * callback function for image field
 */
function kaku_image_callback(){
    $image_id = esc_attr(get_option('custom_image_id'));
    $image_url = esc_attr(get_option('custom_image_url')); ?>

    <input type="button" class="button button-secondary" value="Upload Image" id="upload_image_button">
    <input type="hidden" id="custom_image_id" name="custom_image_id" value="<?php echo $image_id; ?>" />
    <input type="hidden" id="custom_image_url" name="custom_image_url" value="<?php echo $image_url; ?>" />
    <div style="margin-top: 10px;" id="image_preview">
    </div>

<?php }

/**
 * Save custom[Image] option data
 * */
function save_custom_options() {
    if (isset($_POST['custom_image_id'])) {
        $img_id = sanitize_text_field($_POST['custom_image_id']);
        update_option('custom_image_id', $img_id);
    }
    if (isset($_POST['custom_image_url'])) {
        $img_url = sanitize_text_field($_POST['custom_image_url']);
        update_option('custom_image_url', $img_url);
    }
}
add_action('admin_init', 'save_custom_options');


/**
 * callback function for radio button
 */
function kaku_radio_callback(){
    $radio = get_option('kaku_radio');
    $radio_options = ['Yes', 'No'];
    foreach ($radio_options as $radio_option) { 
        $selected = $radio == $radio_option ? $selected = 'checked' : '';
        ?>
        <p>
            <input type="radio" name="kaku_radio" id="<?php echo $radio_option; ?>" value="<?php echo $radio_option; ?>" <?php echo $selected; ?> >
            <label for="<?php echo $radio_option; ?>"><?php echo $radio_option; ?></label>
        </p>
    <?php }
}


/**
 * callback function for checkbox
 */
function kaku_checkbox_callback(){
    $kaku_checkbox = get_option('kaku_checkbox');
    $countries = ['Bangladesh','India','Nepal','Bhutan','Pakistan']; 

    foreach ($countries as $country) { 
        $selected = is_array($kaku_checkbox) && in_array($country, $kaku_checkbox) ? $selected = 'checked' : '';
        ?>
        <p>
            <input type="checkbox" id="<?php echo $country ?>" name='<?php echo "kaku_checkbox[]" ?>' value="<?php echo $country ?>" <?php echo $selected ?>>
            <label for="<?php echo $country ?>"><?php echo $country ?></label>
        </p>
    <?php }
}


/**
 * callback function for drowpdown
 */
function kaku_drowpdown_callback(){
    $selected_country = get_option('kaku_countries');
    $countries = ['Bangladesh','India','Nepal','Bhutan','Pakistan']; ?>

    <select name="kaku_countries" id="kaku_countries">
        <?php 
        foreach ($countries as $country) { 
            $selected = $country == $selected_country ? $selected = 'selected' : '';
            ?>
            <option value="<?php echo $country; ?>" <?php echo $selected; ?>>
                <?php echo $country; ?>
            </option>
        <?php } ?>
    </select>
    <?php }


/**
 * callback function for twitter
 */
function twitter_callback(){
    $twitter = get_option('twitter_url'); ?>
    <input type="url" class="regular-text code" name="twitter_url" placeholder="Twitter url" value="<?php echo $twitter; ?>">
    <?php
}


/**
 * callback function for name field
 */
function name_callback(){
    $f_name = get_option('f_name');
    $l_name = get_option('l_name'); ?>
    <input type="text" class="regular-text" name="f_name" placeholder="First Name" value="<?php echo $f_name; ?>">
    <input type="text" class="regular-text" name="l_name" placeholder="Last Name" value="<?php echo $l_name; ?>">
    <?php
}


/**
 * callback function for section one
 */
function kaku_sidebar_options_callback(){ ?>
    <p><?php _e('Customize your sidebar','test'); ?></p>
<?php 
}


/**
 * callback function for section two
 */
function section_two_options_callback(){ ?>
    <p><?php _e('Customize your section two options') ?></p>
<?php }


/**
 * callback function for main settings page
 */
function homepage_callback(){ ?>
    <h1><?php echo __('Kaku Theme Options','test'); ?></h1>
    <form enctype="multipart/form-data" method="post" action="options.php">
        <?php
        settings_fields('kaku_settings_group'); // register_setting ID
        do_settings_sections('home_settings'); // add menu OR submenu slug
        submit_button();
        ?>
    </form>
<?php
}

/**
 * callback function for Sub menu settings page
 **/
function submenu_callback(){
    ?>

    <h2>Sub Menu</h2>
    <form enctype="multipart/form-data" method="post" action="options.php">
        <?php

        /**
         * If you want to create separate settings field then you need to register settings and create settings fields.  
         * */
        settings_fields('kaku_settings_group'); // register_setting ID
        do_settings_sections('home_settings'); // add menu OR submenu slug
        submit_button();
        ?>
    </form>
    <?php

}

/**
 * ecqueue important scripts
 */
function my_admin_scripts($screen) {
    if( ('toplevel_page_home_settings' == $screen) || ('kaku-theme-options_page_submenu_settings' == $screen) ){
        wp_enqueue_media();
        wp_enqueue_script('custom-media', plugin_dir_url( __FILE__ ) . '/assets/js/my-script.js', array('jquery'), '1.0', true);

        // Send translatable sting to JS
        $translation_array = array(
            'upload_image_text' => __('Upload Image', 'test'),
            'select_image_text' => __('Select Image', 'test')
        );
        wp_localize_script( "custom-media", "custom_script_vars", $translation_array );
    }
 }
 
add_action('admin_enqueue_scripts', 'my_admin_scripts');
