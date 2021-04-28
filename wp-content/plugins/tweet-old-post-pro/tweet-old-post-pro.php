<?php
#     /*
#     Plugin Name: Revive old post Pro Add-on
#     Plugin URI: https://themeisle.com/plugins/tweet-old-post-pro/
#     Description: This addon enable the pro functions of Revive Old Post plugin.For questions, comments, or feature requests, <a href="http://themeisle.com/contact/">contact </a> us!
#     Author: ThemeIsle
#     Version: 1.5.3
#     Author URI: https://themeisle.com/
#     */

if(!defined("CWP_TEXTDOMAIN")){
	define("CWP_TEXTDOMAIN","tweet-old-post");
}
define("ROPPROPLUGINPATH", realpath(dirname(__FILE__)));
define("VERSION_CHECK", TRUE);
define("ROP_IMAGE_CHECK", TRUE);
define("ROP_PRO_1_5", TRUE);
define("ROP_PRO_VERSION", "1.5.2");
require_once(ROPPROPLUGINPATH."/inc/core-pro.php");


 require 'cwp-plugin-update.php'; 

