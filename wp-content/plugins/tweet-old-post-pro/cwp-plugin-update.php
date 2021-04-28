<?php

if (!defined('EDD_SL_STORE_URL')) {

    define( 'EDD_SL_STORE_URL', 'http://themeisle.com' );
}


add_action( 'admin_init', 'tweet_old_post_pro_activate_license' );
add_action( 'admin_init', 'tweet_old_post_pro_register_settings' );

function tweet_old_post_pro_register_settings()
{

    $data = get_plugin_data(dirname(__FILE__)."/".basename(dirname(__FILE__)).".php");
    add_settings_field(
        'tweet_old_post_pro_license',
        $data['Name']." license",
        'tweet_old_post_pro_license_view',
        'general'
    );
}
function tweet_old_post_pro_license_view(){
    $status =  tweet_old_post_pro_get_status();
    $value = tweet_old_post_pro_get_license();

    echo '<p ><input '.(($status === 'valid') ? ('style="border:1px solid #7ad03a; "') : '').' type="text" id="tweet_old_post_pro_license" name="tweet_old_post_pro_license" value="' . $value . '" /><a '.(($status === 'valid') ? ('style="color:#fff;background:  #7ad03a; display: inline-block;text-decoration: none;font-size: 13px;line-height: 26px;height: 26px; margin-left:5px; padding: 0 10px 1px;  -webkit-border-radius: 3px;border-radius: 3px; ">Valid') : ('style="color:#fff;background:  #dd3d36; display: inline-block;text-decoration: none;font-size: 13px;line-height: 26px;height: 26px; margin-left:5px; padding: 0 10px 1px;  -webkit-border-radius: 3px;border-radius: 3px; ">Invalid')).' </a></p><p class="description">Enter your license from <a  href="https://themeisle.com/purchase-history">themeisle.com</a> in order to get plugin updates</p>';


}
function tweet_old_post_pro_get_status(){

    $license_data = get_option( 'tweet_old_post_pro_license_data', '' );

    if($license_data !== ''){
        return isset($license_data->license) ? $license_data->license : get_option( 'tweet_old_post_pro_license_status','' ) ;
    }else{
        return get_option( 'tweet_old_post_pro_license_status','' ) ;
    }
}

function tweet_old_post_pro_get_license(){

    $license_data = get_option( 'tweet_old_post_pro_license_data', '' );
    if($license_data !== ''){
        return isset($license_data->key) ? $license_data->key: get_option( 'tweet_old_post_pro_license', '' ) ;
    }else{
        return get_option( 'tweet_old_post_pro_license','' ) ;
    }
}
function tweet_old_post_pro_check_activation(){
    $license_data = get_option( 'tweet_old_post_pro_license_data', '' );

    if($license_data !== ''){
        return isset($license_data->error) ? ($license_data->error == 'no_activations_left') : false;
    }
    return false;
}
function tweet_old_post_pro_check_expiration(){

    $license_data = get_option( 'tweet_old_post_pro_license_data', '' );

    if($license_data !== ''){
        if(isset($license_data->expires)) {
            if( strtotime($license_data->expires) - time() < 30 * 24 * 3600) {
                return true;
            }
        }
    }
    return false;
}
function tweet_old_post_pro_check_hide($hide){
    if(isset($_GET['tweet_old_post_pro_hide_'.$hide])){
        if($_GET['tweet_old_post_pro_hide_'.$hide]==='yes') {
            $license = get_option( 'tweet_old_post_pro_license_data', '' );
            $license->{'hide_'.$hide} = true;
            update_option( 'tweet_old_post_pro_license_data', $license );
            return false;
        }
    }else{
        $license =
        $license = get_option( 'tweet_old_post_pro_license_data', '' ); ;
        if($license !== ''){
            if(isset($license->{'hide_'.$hide})){
                return false;
            }
        }
    }
    return true;
}
function tweet_old_post_pro_notice() {
    $status 	= tweet_old_post_pro_get_status();
    $data = get_plugin_data(dirname(__FILE__)."/".basename(dirname(__FILE__)).".php");
    $admin_url = admin_url("options-general.php");

    if($status != 'valid')  {
        if(tweet_old_post_pro_check_activation()){
            if(tweet_old_post_pro_check_hide('activation')){
                ?>
                <div class="error">
                    <p><strong>No activations left for <?php echo  $data['Name']; ?>  !!!. You need to upgrade your plan in order to use <?php echo  $data['Name']; ?> on more websites. Please <a href="mailto:friends@themeisle.com">contact</a> the ThemeIsle Staff for more details.</strong>| <a href="<?php echo $admin_url; ?>?tweet_old_post_pro_hide_activation=yes">Hide Notice</a></p>
                </div>
                <?php
                return false;
            }
        }
        ?>
        <?php if(tweet_old_post_pro_check_hide('valid') ): ?>
            <div class="error">
                <p><strong>You do not have a valid license for <?php echo  $data['Name']; ?>  plugin !!!. You can get the license code from your purchase history on <a href="https://themeisle.com/purchase-history" >themeisle.com</a> and validate it <a href="<?php echo $admin_url; ?>#tweet_old_post_pro_license">here</a> </strong>  | <a href="<?php echo $admin_url; ?>?tweet_old_post_pro_hide_valid=yes">Hide Notice</a></p>
            </div>
        <?php endif; ?>
    <?php
    }else{

        if(tweet_old_post_pro_check_expiration()){
            if(tweet_old_post_pro_check_hide('expiration')){
                ?>
                <div class="update-nag">
                    <p><strong>Your license is about to expire for <?php echo  $data['Name']; ?>   plugin !!!. You can renew it  <a target="_blank" href="<?php echo tweet_old_post_pro_renew_url(); ?>" >here</a>.</strong>| <a href="<?php echo $admin_url; ?>?tweet_old_post_pro_hide_expiration=yes">Hide Notice</a></p>
                </div>
            <?php
            }
        }
    }
}
function tweet_old_post_pro_renew_url(){

    $license_data = get_option( 'tweet_old_post_pro_license_data', '' );

    if($license_data !== ''){
        if(isset($license_data->download_id) && isset($license_data->key)){
            return "https://themeisle.com/checkout/?edd_license_key=".$license_data->key."&download_id=".$license_data->download_id;
        }
    }

    return " https://themeisle.com/";
}
add_action( 'admin_notices', 'tweet_old_post_pro_notice' );

function tweet_old_post_pro_activate_license() {

    // listen for our activate button to be clicked
    if( isset( $_POST['tweet_old_post_pro_license'] ) ) {


        $data = get_plugin_data(dirname(__FILE__)."/".basename(dirname(__FILE__)).".php");
        // retrieve the license from the database
        $license = $_POST['tweet_old_post_pro_license'];


        // data to send in our API request
        $api_params = array(
            'edd_action'=> 'activate_license',
            'license' 	=> urlencode($license),
            'item_name' => urlencode( $data['Name'] ),
            'url'       => home_url()
        );
        $response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ) );


        // make sure the response came back okay
        if ( is_wp_error( $response ) )
        {
            $license_data = new stdClass();
            $license_data -> license = "valid";

        }else{
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            if(!is_object($license_data)){
                $license_data = new stdClass();
                $license_data -> license = "valid";
            }
        }
        update_option( 'tweet_old_post_pro_license_data', $license_data );
        delete_transient( 'tweet_old_post_pro_license_data');
        set_transient( 'tweet_old_post_pro_license_data', $license_data, 12 * HOUR_IN_SECONDS  );
    }
}


function tweet_old_post_pro_plugin_updater() {

    // retrieve our license key from the DB
    $license_key = trim( tweet_old_post_pro_get_license() );

    $data = get_plugin_data(dirname(__FILE__).DIRECTORY_SEPARATOR.basename(dirname(__FILE__)).".php");
    // setup the updater
    $edd_updater = new EDD_SL_Plugin_Updater( EDD_SL_STORE_URL, dirname(__FILE__).DIRECTORY_SEPARATOR.basename(dirname(__FILE__)).".php", array(
            'version' 	=> $data['Version'],
            'license' 	=> urlencode($license_key),
            'item_name' => $data['Name'],
            'author' 	=> 'ThemeIsle'
        )
    );

}
add_action( 'admin_init', 'tweet_old_post_pro_plugin_updater' );
add_action( 'admin_init', 'tweet_old_post_pro_plugin_valid',9999999 );
function tweet_old_post_pro_plugin_valid( ){
    if ( false === ( $license = get_transient( 'tweet_old_post_pro_license_data' ) ) ) {
        $license = tweet_old_post_pro_check_license();
        set_transient( 'tweet_old_post_pro_license_data', $license, 12 * HOUR_IN_SECONDS   );
        update_option( 'tweet_old_post_pro_license_data', $license );
    }
}
function tweet_old_post_pro_check_license() {

    global $wp_version;


    $data = get_plugin_data(dirname(__FILE__)."/".basename(dirname(__FILE__)).".php");
    $license = trim( tweet_old_post_pro_get_license() );
    $api_params = array(
        'edd_action' => 'check_license',
        'license' => urlencode($license),
        'item_name' => urlencode( $data['Name']),
        'url'       => home_url()
    );
    // Call the custom API.
    $response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


    if ( is_wp_error( $response ) )
    {
        $license_data = new stdClass();
        $license_data -> license = "valid";

    }else{

        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
        if(!is_object($license_data)){
            $license_data = new stdClass();
            $license_data -> license = "valid";
        }
    }


    $license_old = get_option( 'tweet_old_post_pro_license_data', '' );
    if(isset($license_old->hide_valid)) $license_data->hide_valid = true;
    if(isset($license_old->hide_expiration)) $license_data->hide_expiration = true;
    if(isset($license_old->hide_activation)) $license_data->hide_activation = true;
    return $license_data;



}

if(!class_exists('EDD_SL_Plugin_Updater')) {
    class EDD_SL_Plugin_Updater {
        private $api_url  = '';
        private $api_data = array();
        private $name     = '';
        private $slug     = '';
        private $do_check = false;

        /**
         * Class constructor.
         *
         * @uses plugin_basename()
         * @uses hook()
         *
         * @param string $_api_url The URL pointing to the custom API endpoint.
         * @param string $_plugin_file Path to the plugin file.
         * @param array $_api_data Optional data to send with API calls.
         * @return void
         */
        function __construct( $_api_url, $_plugin_file, $_api_data = null ) {
            $this->api_url  = trailingslashit( $_api_url );
            $this->api_data = urlencode_deep( $_api_data );
            $this->name     = plugin_basename( $_plugin_file );
            $this->slug     = basename( $_plugin_file, '.php');
            $this->version  = $_api_data['version'];

            // Set up hooks.
            $this->hook();
        }

        /**
         * Set up WordPress filters to hook into WP's update process.
         *
         * @uses add_filter()
         *
         * @return void
         */
        private function hook() {
            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient_update_plugins_filter' ) );
            add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
            add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
        }

        /**
         * Check for Updates at the defined API endpoint and modify the update array.
         *
         * This function dives into the update API just when WordPress creates its update array,
         * then adds a custom API call and injects the custom plugin data retrieved from the API.
         * It is reassembled from parts of the native WordPress plugin update code.
         * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
         *
         * @uses api_request()
         *
         * @param array $_transient_data Update array build by WordPress.
         * @return array Modified update array with custom plugin data.
         */
        function pre_set_site_transient_update_plugins_filter( $_transient_data ) {

            if( empty( $_transient_data ) || ! $this->do_check ) {

                // This ensures that the custom API request only runs on the second time that WP fires the update check
                $this->do_check = true;

                return $_transient_data;
            }

            $to_send = array( 'slug' => $this->slug );

            $api_response = $this->api_request( 'plugin_latest_version', $to_send );

            if( false !== $api_response && is_object( $api_response ) && isset( $api_response->new_version ) ) {

                if( version_compare( $this->version, $api_response->new_version, '<' ) ) {
                    $_transient_data->response[$this->name] = $api_response;
                }
            }
            return $_transient_data;
        }


        /**
         * Updates information on the "View version x.x details" page with custom data.
         *
         * @uses api_request()
         *
         * @param mixed $_data
         * @param string $_action
         * @param object $_args
         * @return object $_data
         */
        function plugins_api_filter( $_data, $_action = '', $_args = null ) {
            if ( ( $_action != 'plugin_information' ) || !isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) return $_data;

            $to_send = array( 'slug' => $this->slug );

            $api_response = $this->api_request( 'plugin_information', $to_send );
            if ( false !== $api_response ) $_data = $api_response;

            return $_data;
        }


        /**
         * Disable SSL verification in order to prevent download update failures
         *
         * @param array $args
         * @param string $url
         * @return object $array
         */
        function http_request_args( $args, $url ) {
            // If it is an https request and we are performing a package download, disable ssl verification
            if( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
                $args['sslverify'] = false;
            }
            return $args;
        }

        /**
         * Calls the API and, if successfull, returns the object delivered by the API.
         *
         * @uses get_bloginfo()
         * @uses wp_remote_post()
         * @uses is_wp_error()
         *
         * @param string $_action The requested action.
         * @param array $_data Parameters for the API action.
         * @return false||object
         */
        private function api_request( $_action, $_data ) {

            global $wp_version;

            $data = array_merge( $this->api_data, $_data );

            if( $data['slug'] != $this->slug )
                return;

            if( empty( $data['license'] ) )
                return;

            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => $data['license'],
                'name'       => $data['item_name'],
                'slug'       => $this->slug,
                'author'     => $data['author'],
                'url'        => home_url()
            );
            $request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

            if ( ! is_wp_error( $request ) ):
                $request = json_decode( wp_remote_retrieve_body( $request ) );
                if( $request && isset( $request->sections ) )
                    $request->sections = maybe_unserialize( $request->sections );
                return $request;
            else:
                return false;
            endif;
        }
    }

}
