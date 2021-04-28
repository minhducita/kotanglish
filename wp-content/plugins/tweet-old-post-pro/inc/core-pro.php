<?php
if (!class_exists('CWP_TOP_Core_PRO')) {
	class CWP_TOP_Core_PRO  {
		public $notices;
		function __construct(){

			$this->topLoadHooks();

		}
		function toppro_admin_notice() {
			global $current_user ;
			$user_id = $current_user->ID;
			/* Check that the user hasn't already clicked to ignore the message */
			if ( ! class_exists('CWP_TOP_Core') ) {
				echo '<div class="error"> This is just a pro addon so you will need to install the Revive Old Post free plugin from the WordPress repository';

				echo "</p></div>";
			}
		}
		public static  function addNotice($msg,$type){

			CWP_TOP_Core::addNotice($msg,$type);
		}

		public function tweetPostPro($tweet,$network,$post,$user){
				if($network == $user['service']  ) {

					switch ( $user['service'] ) {
						case 'xing':

							$connection = new RopTwitterOAuth( $user['consumer_key'],$user['consumer_secret'], $user['oauth_token'], $user['oauth_token_secret'] );
							$connection->oauth_host = "https://api.xing.com/v1/";
							$args       = array( 'uri' => $tweet['link'] ,"text" =>$tweet['message'] );
                            $response = $connection->post( 'https://api.xing.com/v1/users/me/share/link', $args );
							if ( $response !== false ) {

								if ( $connection->http_code == 201 ) {
									self::addNotice( "Post " . $post->post_title . " has been successfully sent to XING.", 'notice' );
								}
							}
							break;
						case 'tumblr':

							$connection = new RopTwitterOAuth( $user['consumer_key'],$user['consumer_secret'], $user['oauth_token'], $user['oauth_token_secret'] );
							$connection->oauth_host = "http://www.tumblr.com/oauth/";
							global $CWP_TOP_Core;
							$args = array();
							if($CWP_TOP_Core->isPostWithImageEnabled($network) && CWP_TOP_PRO) {
								$args["type"] = "photo";
								$args["link"] = $tweet['link'];
								$args["caption"] = $tweet['message'];
								$image = $this->getPostImage($post->ID);
								if(!empty($image))
									$args["source"] = $this->strip_https($image);
							}else{
								$args['type'] = "link";
								$args['url'] = $tweet['link'];
								$args['title'] = $tweet['message'];
							}
                            $response = $connection->post( "http://api.tumblr.com/v2/blog/".$user['consumer_url']."/post", $args );
							if ( $response !== false ) {

								if ( $connection->http_code == 201 ) {
									self::addNotice( "Post " . $post->post_title . " has been successfully sent to Tumblr.", 'notice' );
								}
							}
							break;

					}
				}

		}
		public function afterCheckPro(){
			$cnetwork = CWP_TOP_Core::getCurrentNetwork();
			if(isset($_REQUEST['oauth_token']) &&  $cnetwork == 'xing'  ) {


				$xing = new RopTwitterOAuth(get_option("cwp_top_consumer_key_xing"), get_option("cwp_top_consumer_secret_xing"), get_option("cwp_top_oauth_token_xing"), get_option("cwp_top_oauth_token_secret_xing"));
				$xing->oauth_host = "https://api.xing.com/v1/";
				$access_token = $xing->getAccessToken($_REQUEST['oauth_verifier'],"POST");
				//print_r($access_token);
				//die();
				$user_details = $xing->get('https://api.xing.com/v1/users/me');
				$user_details = $user_details->users[0];
				$user_details->name = $user_details->display_name;
				$user_details->profile_image_url = $user_details->photo_urls->thumb;

				$newUser = array(
					'user_id'				=> $user_details->id,
					'oauth_token'			=> $access_token['oauth_token'],
					'oauth_token_secret'	=> $access_token['oauth_token_secret'],
					'oauth_user_details'	=> $user_details,
					'consumer_key'	=> get_option("cwp_top_consumer_key_xing"),
					'consumer_secret'	=> get_option("cwp_top_consumer_secret_xing"),
					'service'				=> 'xing'
				);

				$loggedInUsers = get_option('cwp_top_logged_in_users');
				if(empty($loggedInUsers)) { $loggedInUsers = array(); }

				if(in_array($newUser, $loggedInUsers)) {
					 $this->addNotice("You already added that user",'error');
				} else {
					array_push($loggedInUsers, $newUser);
					update_option('cwp_top_logged_in_users', $loggedInUsers);
				}

				header("Location: " . top_settings_url());
				exit;
			}
			if(isset($_REQUEST['oauth_token']) &&  $cnetwork == 'tumblr'  ) {


				$tumblr = new RopTwitterOAuth(get_option("cwp_top_consumer_key_tumblr"), get_option("cwp_top_consumer_secret_tumblr"), get_option("cwp_top_oauth_token_tumblr"), get_option("cwp_top_oauth_token_secret_tumblr"));

				$tumblr->oauth_host = "http://www.tumblr.com/oauth/";
				$access_token = $tumblr->getAccessToken($_REQUEST['oauth_verifier'],"GET");

				$user_details = $tumblr->get('http://api.tumblr.com/v2/blog/'.get_option("cwp_top_consumer_url_tumblr").'/avatar/64');
				$user_details->name = get_option("cwp_top_consumer_url_tumblr");
				$user_details->profile_image_url = $user_details->response->avatar_url;
				$newUser = array(
					'user_id'				=> md5(get_option("cwp_top_consumer_url_tumblr")),
					'oauth_token'			=> $access_token['oauth_token'],
					'oauth_token_secret'	=> $access_token['oauth_token_secret'],
					'oauth_user_details'	=> $user_details,
					'consumer_key'	=> get_option("cwp_top_consumer_key_tumblr"),
					'consumer_secret'	=> get_option("cwp_top_consumer_secret_tumblr"),
					'consumer_url'	=> get_option("cwp_top_consumer_url_tumblr"),
					'service'				=> 'tumblr'
				);

				$loggedInUsers = get_option('cwp_top_logged_in_users');
				if(empty($loggedInUsers)) { $loggedInUsers = array(); }

				if(in_array($newUser, $loggedInUsers)) {
					 $this->addNotice("You already added that user",'error');
				} else {
					array_push($loggedInUsers, $newUser);
					update_option('cwp_top_logged_in_users', $loggedInUsers);
				}

				header("Location: " . top_settings_url());
				exit;
			}
		}


		function topProAddNewAccount(){

			if(!is_admin()) return false;
			$social_network = $_POST['social_network'];
			$response = array();
			switch ($social_network) {
				case 'linkedin':
					if ( empty( $_POST['extra']['app_id'] ) ) {
						self::addNotice( __( "Could not connect to Linkedin! You need to add the App ID", CWP_TEXTDOMAIN ), 'error' );
					} else if ( empty( $_POST['extra']['app_secret'] ) ) {
						self::addNotice( __( "Could not connect to Linkedin! You need to add the App Secret", CWP_TEXTDOMAIN ), 'error' );

					} else {
						$top_session_state = uniqid( '', true );
						$url               = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=' . $_POST['extra']["app_id"] . '&scope=rw_nus&state=' . $top_session_state . '&redirect_uri=' . top_settings_url();

						update_option( 'top_lk_session_state', $top_session_state );
						update_option( 'cwp_top_lk_app_id', $_POST['extra']['app_id'] );
						update_option( 'cwp_top_lk_app_secret', $_POST['extra']['app_secret'] );

						$response['url'] = $url;


					}


					break;

				case 'xing':
					if ( empty( $_POST['extra']['app_id'] ) ) {
						self::addNotice( __( "Could not connect to XING! You need to add the Consumer Key", CWP_TEXTDOMAIN ), 'error' );
					} else if ( empty( $_POST['extra']['app_secret'] ) ) {
						self::addNotice( __( "Could not connect to XING! You need to add the Consumer Secret", CWP_TEXTDOMAIN ), 'error' );

					} else {
						$this->oAuthCallback = $_POST['currentURL'];
						$xing = new RopTwitterOAuth(trim($_POST['extra']['app_id']), trim($_POST['extra']['app_secret']));
						$xing->oauth_host = "https://api.xing.com/v1/";
						$requestToken = $xing->getRequestToken($this->oAuthCallback );

						update_option('cwp_top_oauth_token_xing', $requestToken['oauth_token']);
						update_option('cwp_top_oauth_token_secret_xing', $requestToken['oauth_token_secret']);
						update_option('cwp_top_consumer_key_xing', $_POST['extra']['app_id']);
						update_option('cwp_top_consumer_secret_xing', $_POST['extra']['app_secret']);

						switch ($xing->http_code) {
							case 201:
								$url = $xing->getAuthorizeURL($requestToken['oauth_token'],"");
								$response['url'] = $url;
								break;

							default:
								self::addNotice(__("Could not connect to XING!"),CWP_TEXTDOMAIN);

								break;
						}
						break;


					}
					break;
				case 'tumblr':
					if ( empty( $_POST['extra']['app_id'] ) ) {
						self::addNotice( __( "Could not connect to Thumblr! You need to add the Consumer Key", CWP_TEXTDOMAIN ), 'error' );
					} else if ( empty( $_POST['extra']['app_secret'] ) ) {
						self::addNotice( __( "Could not connect to Thumblr! You need to add the Consumer Secret", CWP_TEXTDOMAIN ), 'error' );

					} else if ( empty( $_POST['extra']['app_url'] ) ) {
						self::addNotice( __( "Could not connect to Thumblr! You need to add the Tumblr Url", CWP_TEXTDOMAIN ), 'error' );

					} else {
						$_POST['extra']['app_id'] = trim($_POST['extra']['app_id']);
						$_POST['extra']['app_secret'] =  trim($_POST['extra']['app_secret']);
						$this->oAuthCallback = $_POST['currentURL'];
						$url = parse_url($_POST['extra']['app_url']);
						$url = isset($url['host']) ? $url['host'] : $_POST['extra']['app_url'];
						$tumblr = new RopTwitterOAuth($_POST['extra']['app_id'], $_POST['extra']['app_secret']);
						$tumblr->oauth_host = "http://www.tumblr.com/oauth/";
						$requestToken = $tumblr->getRequestToken($this->oAuthCallback );

						update_option('cwp_top_oauth_token_tumblr', $requestToken['oauth_token']);
						update_option('cwp_top_oauth_token_secret_tumblr', $requestToken['oauth_token_secret']);
						update_option('cwp_top_consumer_key_tumblr', $_POST['extra']['app_id']);
						update_option('cwp_top_consumer_secret_tumblr', $_POST['extra']['app_secret']);
						update_option('cwp_top_consumer_url_tumblr', $url);

						switch ($tumblr->http_code) {
							case 200:
								$url = $tumblr->getAuthorizeURL($requestToken['oauth_token'],"");
								$response['url'] = $url;
								break;

							default:
								self::addNotice(__("Could not connect to Thumblr!"),CWP_TEXTDOMAIN);

								break;
						}
						break;


					}
					break;
			}
			echo json_encode($response);

			die();
				/*$twp->oAuthCallback = $_POST['currentURL'];

				$twitter = new TwitterOAuth($twp->consumer, $twp->consumerSecret);
				$requestToken = $twitter->getRequestToken($twp->oAuthCallback);

				update_option('cwp_top_oauth_token', $requestToken['oauth_token']);
				update_option('cwp_top_oauth_token_secret', $requestToken['oauth_token_secret']);



				switch ($twitter->http_code) {
					case 200:
						$url = $twitter->getAuthorizeURL($requestToken['oauth_token']);
						echo $url;
						break;

					default:
						return __("Could not connect to Twitter!", CWP_TEXTDOMAIN);
						break;
				}

				die(); // Required*/

		}

		function url_get_contents ($Url) {
			$Url = $this->strip_https($Url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $Url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			if($output === false || $output === "") {
				self::addNotice("Problem fetching image: ".$Url. " : ".curl_error($ch),"error");
			}

			curl_close($ch);
			return $output;
		}

		function topProGetCustomCategories($postQuery, $maximum_hashtag_length){
			$taxonomi = get_object_taxonomies( $postQuery->post_type, 'objects' );
			$newHashtags = "";
			foreach ($taxonomi as $key => $value) {
				if (strpos($key,"category")) {
					$postCategories = get_the_terms($postQuery->ID,$key);

					foreach ($postCategories as $category) {
						if(strlen($category->slug.$newHashtags) <= $maximum_hashtag_length || $maximum_hashtag_length == 0) {
							$newHashtags = $newHashtags . " #" . preg_replace('/-/','',strtolower($category->slug));
						}
					}
				}
			}
			return $newHashtags;
		}

		function strip_https($url){

			return str_replace("https://","http://",$url);
		}
		function getPostImage($id){
			$image = '';
			if (has_post_thumbnail($id)) :
				$image_array = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
				$image = $image_array;
			else :
				$post = get_post($id);
				$image = '';
				ob_start();
				ob_end_clean();
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);

				if(isset($matches [1] [0]))
					$image = $matches [1] [0];
			endif;
			return $image;
		}
		function topProImage($connection, $finalTweet, $id,$service='twitter') {
			//global $post, $posts;
			//$plugin_data = get_plugin_data( PLUGINPATH.'/tweet-old-post.php', $markup = true, $translate = true );
			//print_r($post);
			//print_r($plugin_data);

			//if ($plugin_data['Version']=='6.7.7'&&$plugin_data['Version']=='6.7.8'&&$plugin_data['Version']=='6.7.9'&&$plugin_data['Version']=='6.8'){
			//	$fullTweet = $finalTweet;
			//	$finalTweet = $finalTweet['message'];
			//}
			//echo has_post_thumbnail( $id );
			$image = $this->getPostImage($id);
			$args = array();
			if(is_null($image)) $image = '';
			switch($service){
				case 'twitter':
						$message = isset($finalTweet['link']) ? ($finalTweet['message']." ".$finalTweet['link']) : ($finalTweet['message']);
						if ($image == ''){
							$args =     array('status' => $message);
						}else{
							$args =     array('status' => $message,'media[]' => $this->url_get_contents($image));
						}
					break;
				case 'facebook':
					if ($image == ''){
						     $args =  array(

								'body' => array( 'message' => $finalTweet['message'],'link' => $finalTweet['link']),

							);
						}else{
							$args =  array(

								'body' => array( 'message' => $finalTweet['message'],'link' => $finalTweet['link'],'picture'=>$image),

							);
					}
					break;
				case 'thumblr':
					 return $image;
					break;
			}
			return $args;
		}

		function topLoadStyles()
		{
			global $cwp_top_settings; // Global Tweet Old Post Settings

			// Enqueue and register all scripts on plugin's page
			if(isset($_GET['page'])) {
				if ($_GET['page'] == $cwp_top_settings['slug'] || $_GET['page'] == "ExcludePosts") {

					// Enqueue and Register Main CSS File
					wp_register_style( 'cwp_top_pro_stylesheet', plugins_url( 'css/style.css', dirname(__FILE__) ), false, time() );
					wp_enqueue_style( 'cwp_top_pro_stylesheet' );


				}
			}

		}

		function topLoadHooks()
		{
			add_action('admin_enqueue_scripts', array($this,'topLoadStyles'));
			add_action('admin_notices', array($this,'toppro_admin_notice'));
			add_action('admin_init', array($this,'download_file'));
			add_action( 'admin_notices', array($this, 'adminNotice') );

		}

		public function adminNotice(){
			if(is_array($this->notices)){
				foreach($this->notices as $n){
					?>
					<div class="error">
	                         <p><?php _e( $n, CWP_TEXTDOMAIN ); ?></p>
	                 </div>
				<?php
				}
			}

		}
		function download_file(){
			if(!defined('CWP_TOP_PRO')){

				$this->notices[] =  "You need to have the latest version of the Revive Old Post plugin in order to have the updated features available !";
			}
		}


		function updateTopProAjax(){
			global $CWP_TOP_Core;
			if( method_exists($CWP_TOP_Core,"getFormatFields"))
			{
				$cwp_top_networks = $CWP_TOP_Core->getFormatFields();
			}
			else
			{
				global $cwp_top_networks;
			}
			$dataSent = $_POST['dataSent']['dataSent'];

			$options = array();
			parse_str($dataSent, $options);
			$optionsdb = array();
			foreach($cwp_top_networks as $n=>$d){
				if($options[$n.'_schedule_type_selected'] == 'each'){

					$optionsdb[$n.'_schedule_type_selected'] = 'each';
					$optionsdb[$n.'_top_opt_interval']  = $options[$n."_top_opt_interval"];
				}else{

					$optionsdb[$n.'_schedule_type_selected'] = 'custom';
					$optionsdb[$n.'_top_opt_interval']["days"] = $options[$n."_top_schedule_days"];
					$optionsdb[$n.'_top_opt_interval']['times'] = array();

					foreach($options[$n."_time_choice_min"] as $k=>$min){
						$optionsdb[$n.'_top_opt_interval']['times'][] = array("minute"=>$min,"hour"=>$options[$n."_time_choice_hour"][$k]);
					}
					$mins = array();
					$hour = array();
					foreach ($optionsdb[$n.'_top_opt_interval']['times'] as $key => $row) {
						$hour[$key]  = $row['hour'];
						$mins[$key] = $row['minute'];
					}
					array_multisort($hour, SORT_ASC, $mins, SORT_ASC, $optionsdb[$n.'_top_opt_interval']['times']);
					if(count($optionsdb[$n.'_top_opt_interval']['times']) == 0){

						$optionsdb[$n.'_schedule_type_selected'] = 'each';
						$optionsdb[$n.'_top_opt_interval']  = $options[$n."_top_opt_interval"];

					}

				}

			}
			update_option("cwp_top_global_schedule",$optionsdb);
		}
	}
}
if(class_exists('CWP_TOP_Core_PRO')) {
	$CWP_TOP_Core_PRO = new CWP_TOP_Core_PRO;
}