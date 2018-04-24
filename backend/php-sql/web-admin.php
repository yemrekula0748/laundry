<?php
/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright AnaskiNet 2018
 * @package laundry_service
 * 
 * 
 * Created using IMA Builder
 * http://codecanyon.net/item/ionic-mobile-app-builder/15716727
 */


/** CONFIG:START **/
/** database **/
$config["host"]			= "89.19.30.15" ; 		//host
$config["user"]			= "u7970538_kulamobil" ; 		//Username SQL
$config["pass"]			= "GHac67A0" ; 		//Password SQL
$config["dbase"]			= "u7970538_kulamobil" ; 		//Database
/** admin **/
$config["email"]			= "admin@localhost" ; 		//email for login
$config["password"]			= "d033e22ae348aeb5660fc2140aec35850c4da997" ; 		// sha1(password)
$config["utf8"]			= true; 		
$config["theme"]			= "paper" ;		// theme name you can get here https://www.bootstrapcdn.com/bootswatch/
$config["navbar"]		= "nav-stacked" ; 		// nav-bar or nav-stacked
/** Fix Input Format **/
$config["input_datetime"]		= "";
$config["input_date"]		= "";
/** file **/
$config["image_allowed"]		= array("jpeg", "jpg", "png", "gif");
$config["media_allowed"]		= array("mp3", "mp4", "avi", "wav");
$config["file_allowed"]		= array("zip");
$config["kcfinder"]		= false;		// download kcfinder from from http://kcfinder.sunhater.com then unzip with filename `kcfinder`, no need configuration (the key folder name `kcfinder` is exist)
/** CONFIG:END **/


/** LANGGUAGE:START **/
define("LANG_ORDER","Orders");
define("LANG_USER","Users");
define("LANG_ID","Ids");
define("LANG_DATE","Dates");
define("LANG_SERVICE","Services");
define("LANG_UNAME","Unames");
define("LANG_STATUS","Statuss");
define("LANG_NOTE","Notes");
define("LANG_FULLNAME","Fullnames");
define("LANG_PWD","Pwds");
define("LANG_ADDRESS","Addresss");
define("LANG_TYPE_IC","Type Ics");
define("LANG_NO_IC","No Ics");
define("LANG_PHONE","Phones");
define("LANG_EMAIL","Emails");
/** default constant **/
if(!defined("LANG_FILE_BROWSER")){
	define("LANG_FILE_BROWSER","File Browser");
}
if(!defined("LANG_IMAGES")){
	define("LANG_IMAGES","Images");
}
if(!defined("LANG_FILES")){
	define("LANG_FILES","Files");
}
if(!defined("LANG_MEDIAS")){
	define("LANG_MEDIAS","Media");
}
if(!defined("LANG_HOME")){
	define("LANG_HOME","Home");
}
if(!defined("LANG_ADD")){
	define("LANG_ADD","Add");
}
if(!defined("LANG_DELETE")){
	define("LANG_DELETE","Delete");
}
if(!defined("LANG_LIST")){
	define("LANG_LIST","List");
}
if(!defined("LANG_EDIT")){
	define("LANG_EDIT","Edit");
}
if(!defined("LANG_MORE")){
	define("LANG_MORE","More");
}
if(!defined("LANG_PUSH_NOTIFICATION")){
	define("LANG_PUSH_NOTIFICATION","Push Notifications");
}
/** LANGGUAGE:END **/


session_start();
$_SESSION["KCFINDER"]["disabled"] = true; //terminate filebrowser
if(!isset($_SESSION["IS_ADMIN"])){
	$_SESSION["IS_ADMIN"] = false;
}
if(file_exists("kcfinder/browse.php")){

$config["kcfinder"]		= true;
}
if($config["navbar"] == "nav-bar"){
	$sidebaleft = 12;
	$sidebaright = 12;
}else{
	$config["navbar"] = "nav-stacked";
	$sidebaleft = 3;
	$sidebaright = 9;
}
//filebrowser enable if admin
if($_SESSION["IS_ADMIN"] == true){
	$_SESSION["KCFINDER"] = array();
	$_SESSION["KCFINDER"]["disabled"] = false;
	$_SESSION["KCFINDER"]["cookieDomain"] = parse_url($_SERVER["HTTP_HOST"],PHP_URL_HOST);
	$_SESSION["KCFINDER"]["uploadURL"] = "../media";
	$_SESSION["KCFINDER"]["filenameChangeChars"] = array(" "=>"-",":"=>".");
	$_SESSION["KCFINDER"]["dirnameChangeChars"] = array(" "=>"-",":"=>".");
	$_SESSION["KCFINDER"]["denyUpdateCheck"] = true;
	$_SESSION["KCFINDER"]["denyExtensionRename"] = true;
	$_SESSION["KCFINDER"]["types"]["media"] = implode(" ",$config["media_allowed"]);
	$_SESSION["KCFINDER"]["types"]["image"] = implode(" ",$config["image_allowed"]);
	$_SESSION["KCFINDER"]["types"]["file"] = implode(" ",$config["file_allowed"]);
}else{
//terminate filebrowser
	$_SESSION["KCFINDER"]["disabled"] = true;
}
$js_for_relation ="";
	$get_dir = explode("/", $_SERVER["PHP_SELF"]);
	unset($get_dir[count($get_dir)-1]);
	$main_url = "http://" . $_SERVER["HTTP_HOST"] ;
	$full_url = $main_url . implode("/",$get_dir);
if(isset($_POST["email"]) && isset($_POST["password"])){
	if(($_POST["email"]==$config["email"]) && ( sha1($_POST["password"]) ==$config["password"])){
		$_SESSION["IS_ADMIN"] = true;
		header("Location: ?login=ok");
	}else{
		header("Location: ?login=error");
	}
}
if(!isset($_GET["table"])){
	$_GET["table"] = "home";
}
if(!isset($_GET["login"])){
	$_GET["login"] = "ok";
}
if(isset($_GET["logout"])){
	$_SESSION["IS_ADMIN"] = false;
	$_SESSION["KCFINDER"]["disabled"] = true; //terminate filebrowser
	header("Location: ?login=reset");
}
$tags_html = $error_notice = null;
if($_SESSION["IS_ADMIN"]==true){
	/** connect to mysql **/
	$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);
	if (mysqli_connect_errno()){
		die(mysqli_connect_error());
	}
	
	if($config["utf8"]==true){
		$mysql->set_charset("utf8");
	}
	
	/** prepare notice **/
	$notice = null;
	
	/** no action **/
	if(!isset($_GET["action"])){
		$_GET["action"] = "list";
	}
	
	/** create ionicon **/
	$ionicons = "alert,alert-circled,android-add,android-add-circle,android-alarm-clock,android-alert,android-apps,android-archive,android-arrow-back,android-arrow-down,android-arrow-dropdown,android-arrow-dropdown-circle,android-arrow-dropleft,android-arrow-dropleft-circle,android-arrow-dropright,android-arrow-dropright-circle,android-arrow-dropup,android-arrow-dropup-circle,android-arrow-forward,android-arrow-up,android-attach,android-bar,android-bicycle,android-boat,android-bookmark,android-bulb,android-bus,android-calendar,android-call,android-camera,android-cancel,android-car,android-cart,android-chat,android-checkbox,android-checkbox-blank,android-checkbox-outline,android-checkbox-outline-blank,android-checkmark-circle,android-clipboard,android-close,android-cloud,android-cloud-circle,android-cloud-done,android-cloud-outline,android-color-palette,android-compass,android-contact,android-contacts,android-contract,android-create,android-delete,android-desktop,android-document,android-done,android-done-all,android-download,android-drafts,android-exit,android-expand,android-favorite,android-favorite-outline,android-film,android-folder,android-folder-open,android-funnel,android-globe,android-hand,android-hangout,android-happy,android-home,android-image,android-laptop,android-list,android-locate,android-lock,android-mail,android-map,android-menu,android-microphone,android-microphone-off,android-more-horizontal,android-more-vertical,android-navigate,android-notifications,android-notifications-none,android-notifications-off,android-open,android-options,android-people,android-person,android-person-add,android-phone-landscape,android-phone-portrait,android-pin,android-plane,android-playstore,android-print,android-radio-button-off,android-radio-button-on,android-refresh,android-remove,android-remove-circle,android-restaurant,android-sad,android-search,android-send,android-settings,android-share,android-share-alt,android-star,android-star-half,android-star-outline,android-stopwatch,android-subway,android-sunny,android-sync,android-textsms,android-time,android-train,android-unlock,android-upload,android-volume-down,android-volume-mute,android-volume-off,android-volume-up,android-walk,android-warning,android-watch,android-wifi,aperture,archive,arrow-down-a,arrow-down-b,arrow-down-c,arrow-expand,arrow-graph-down-left,arrow-graph-down-right,arrow-graph-up-left,arrow-graph-up-right,arrow-left-a,arrow-left-b,arrow-left-c,arrow-move,arrow-resize,arrow-return-left,arrow-return-right,arrow-right-a,arrow-right-b,arrow-right-c,arrow-shrink,arrow-swap,arrow-up-a,arrow-up-b,arrow-up-c,asterisk,at,backspace,backspace-outline,bag,battery-charging,battery-empty,battery-full,battery-half,battery-low,beaker,beer,bluetooth,bonfire,bookmark,bowtie,briefcase,bug,calculator,calendar,camera,card,cash,chatbox,chatbox-working,chatboxes,chatbubble,chatbubble-working,chatbubbles,checkmark,checkmark-circled,checkmark-round,chevron-down,chevron-left,chevron-right,chevron-up,clipboard,clock,close,close-circled,close-round,closed-captioning,cloud,code,code-download,code-working,coffee,compass,compose,connection-bars,contrast,crop,cube,disc,document,document-text,drag,earth,easel,edit,egg,eject,email,email-unread,erlenmeyer-flask,erlenmeyer-flask-bubbles,eye,eye-disabled,female,filing,film-marker,fireball,flag,flame,flash,flash-off,folder,fork,fork-repo,forward,funnel,gear-a,gear-b,grid,hammer,happy,happy-outline,headphone,heart,heart-broken,help,help-buoy,help-circled,home,icecream,image,images,information,information-circled,ionic,ios-alarm,ios-alarm-outline,ios-albums,ios-albums-outline,ios-americanfootball,ios-americanfootball-outline,ios-analytics,ios-analytics-outline,ios-arrow-back,ios-arrow-down,ios-arrow-forward,ios-arrow-left,ios-arrow-right,ios-arrow-thin-down,ios-arrow-thin-left,ios-arrow-thin-right,ios-arrow-thin-up,ios-arrow-up,ios-at,ios-at-outline,ios-barcode,ios-barcode-outline,ios-baseball,ios-baseball-outline,ios-basketball,ios-basketball-outline,ios-bell,ios-bell-outline,ios-body,ios-body-outline,ios-bolt,ios-bolt-outline,ios-book,ios-book-outline,ios-bookmarks,ios-bookmarks-outline,ios-box,ios-box-outline,ios-briefcase,ios-briefcase-outline,ios-browsers,ios-browsers-outline,ios-calculator,ios-calculator-outline,ios-calendar,ios-calendar-outline,ios-camera,ios-camera-outline,ios-cart,ios-cart-outline,ios-chatboxes,ios-chatboxes-outline,ios-chatbubble,ios-chatbubble-outline,ios-checkmark,ios-checkmark-empty,ios-checkmark-outline,ios-circle-filled,ios-circle-outline,ios-clock,ios-clock-outline,ios-close,ios-close-empty,ios-close-outline,ios-cloud,ios-cloud-download,ios-cloud-download-outline,ios-cloud-outline,ios-cloud-upload,ios-cloud-upload-outline,ios-cloudy,ios-cloudy-night,ios-cloudy-night-outline,ios-cloudy-outline,ios-cog,ios-cog-outline,ios-color-filter,ios-color-filter-outline,ios-color-wand,ios-color-wand-outline,ios-compose,ios-compose-outline,ios-contact,ios-contact-outline,ios-copy,ios-copy-outline,ios-crop,ios-crop-strong,ios-download,ios-download-outline,ios-drag,ios-email,ios-email-outline,ios-eye,ios-eye-outline,ios-fastforward,ios-fastforward-outline,ios-filing,ios-filing-outline,ios-film,ios-film-outline,ios-flag,ios-flag-outline,ios-flame,ios-flame-outline,ios-flask,ios-flask-outline,ios-flower,ios-flower-outline,ios-folder,ios-folder-outline,ios-football,ios-football-outline,ios-game-controller-a,ios-game-controller-a-outline,ios-game-controller-b,ios-game-controller-b-outline,ios-gear,ios-gear-outline,ios-glasses,ios-glasses-outline,ios-grid-view,ios-grid-view-outline,ios-heart,ios-heart-outline,ios-help,ios-help-empty,ios-help-outline,ios-home,ios-home-outline,ios-infinite,ios-infinite-outline,ios-information,ios-information-empty,ios-information-outline,ios-ionic-outline,ios-keypad,ios-keypad-outline,ios-lightbulb,ios-lightbulb-outline,ios-list,ios-list-outline,ios-location,ios-location-outline,ios-locked,ios-locked-outline,ios-loop,ios-loop-strong,ios-medical,ios-medical-outline,ios-medkit,ios-medkit-outline,ios-mic,ios-mic-off,ios-mic-outline,ios-minus,ios-minus-empty,ios-minus-outline,ios-monitor,ios-monitor-outline,ios-moon,ios-moon-outline,ios-more,ios-more-outline,ios-musical-note,ios-musical-notes,ios-navigate,ios-navigate-outline,ios-nutrition,ios-nutrition-outline,ios-paper,ios-paper-outline,ios-paperplane,ios-paperplane-outline,ios-partlysunny,ios-partlysunny-outline,ios-pause,ios-pause-outline,ios-paw,ios-paw-outline,ios-people,ios-people-outline,ios-person,ios-person-outline,ios-personadd,ios-personadd-outline,ios-photos,ios-photos-outline,ios-pie,ios-pie-outline,ios-pint,ios-pint-outline,ios-play,ios-play-outline,ios-plus,ios-plus-empty,ios-plus-outline,ios-pricetag,ios-pricetag-outline,ios-pricetags,ios-pricetags-outline,ios-printer,ios-printer-outline,ios-pulse,ios-pulse-strong,ios-rainy,ios-rainy-outline,ios-recording,ios-recording-outline,ios-redo,ios-redo-outline,ios-refresh,ios-refresh-empty,ios-refresh-outline,ios-reload,ios-reverse-camera,ios-reverse-camera-outline,ios-rewind,ios-rewind-outline,ios-rose,ios-rose-outline,ios-search,ios-search-strong,ios-settings,ios-settings-strong,ios-shuffle,ios-shuffle-strong,ios-skipbackward,ios-skipbackward-outline,ios-skipforward,ios-skipforward-outline,ios-snowy,ios-speedometer,ios-speedometer-outline,ios-star,ios-star-half,ios-star-outline,ios-stopwatch,ios-stopwatch-outline,ios-sunny,ios-sunny-outline,ios-telephone,ios-telephone-outline,ios-tennisball,ios-tennisball-outline,ios-thunderstorm,ios-thunderstorm-outline,ios-time,ios-time-outline,ios-timer,ios-timer-outline,ios-toggle,ios-toggle-outline,ios-trash,ios-trash-outline,ios-undo,ios-undo-outline,ios-unlocked,ios-unlocked-outline,ios-upload,ios-upload-outline,ios-videocam,ios-videocam-outline,ios-volume-high,ios-volume-low,ios-wineglass,ios-wineglass-outline,ios-world,ios-world-outline,ipad,iphone,ipod,jet,key,knife,laptop,leaf,levels,lightbulb,link,load-a,load-b,load-c,load-d,location,lock-combination,locked,log-in,log-out,loop,magnet,male,man,map,medkit,merge,mic-a,mic-b,mic-c,minus,minus-circled,minus-round,model-s,monitor,more,mouse,music-note,navicon,navicon-round,navigate,network,no-smoking,nuclear,outlet,paintbrush,paintbucket,paper-airplane,paperclip,pause,person,person-add,person-stalker,pie-graph,pin,pinpoint,pizza,plane,planet,play,playstation,plus,plus-circled,plus-round,podium,pound,power,pricetag,pricetags,printer,pull-request,qr-scanner,quote,radio-waves,record,refresh,reply,reply-all,ribbon-a,ribbon-b,sad,sad-outline,scissors,search,settings,share,shuffle,skip-backward,skip-forward,social-android,social-android-outline,social-angular,social-angular-outline,social-apple,social-apple-outline,social-bitcoin,social-bitcoin-outline,social-buffer,social-buffer-outline,social-chrome,social-chrome-outline,social-codepen,social-codepen-outline,social-css3,social-css3-outline,social-designernews,social-designernews-outline,social-dribbble,social-dribbble-outline,social-dropbox,social-dropbox-outline,social-euro,social-euro-outline,social-facebook,social-facebook-outline,social-foursquare,social-foursquare-outline,social-freebsd-devil,social-github,social-github-outline,social-google,social-google-outline,social-googleplus,social-googleplus-outline,social-hackernews,social-hackernews-outline,social-html5,social-html5-outline,social-instagram,social-instagram-outline,social-javascript,social-javascript-outline,social-linkedin,social-linkedin-outline,social-markdown,social-nodejs,social-octocat,social-pinterest,social-pinterest-outline,social-python,social-reddit,social-reddit-outline,social-rss,social-rss-outline,social-sass,social-skype,social-skype-outline,social-snapchat,social-snapchat-outline,social-tumblr,social-tumblr-outline,social-tux,social-twitch,social-twitch-outline,social-twitter,social-twitter-outline,social-usd,social-usd-outline,social-vimeo,social-vimeo-outline,social-whatsapp,social-whatsapp-outline,social-windows,social-windows-outline,social-wordpress,social-wordpress-outline,social-yahoo,social-yahoo-outline,social-yen,social-yen-outline,social-youtube,social-youtube-outline,soup-can,soup-can-outline,speakerphone,speedometer,spoon,star,stats-bars,steam,stop,thermometer,thumbsdown,thumbsup,toggle,toggle-filled,transgender,trash-a,trash-b,trophy,tshirt,tshirt-outline,umbrella,university,unlocked,upload,usb,videocamera,volume-high,volume-low,volume-medium,volume-mute,wand,waterdrop,wifi,wineglass,woman,wrench,xbox";
	$ionicon_dialog = null;
	$ionicon_dialog .= "<div id=\"ionicons\" class=\"modal fade\" aria-labelledby=\"Ionicons\" aria-hidden=\"true\">"."\r\n";
	$ionicon_dialog .= "<div class=\"modal-dialog\">"."\r\n";
	$ionicon_dialog .= "<div class=\"modal-content\">"."\r\n";
	$ionicon_dialog .= "<div class=\"modal-header\">"."\r\n";
	$ionicon_dialog .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";
	$ionicon_dialog .= "<h4 class=\"modal-title\">Ionicons</h4>"."\r\n";
	$ionicon_dialog .= "</div>"."\r\n";
	$ionicon_dialog .= "<div class=\"modal-body\">"."\r\n";
	$ionicon_dialog .= "<div style=\"width:100%;height:360px;overflow-x:scroll;\">"."\r\n";
	$ionicon_dialog .= "<div class=\"width:99%;\" >"."\r\n";
	foreach(explode(",",$ionicons) as $icon){
		$ionicon_dialog .= "<div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1\"><a href=\"#ion-".$icon."\" onclick=\"ionicons('ion-".$icon."');\" style=\"font-size:28px\" ><i class=\"ion ion-".$icon."\"></i></a></div>"."\r\n";
	}
	$ionicon_dialog .="</div>"."\r\n";
	$ionicon_dialog .="</div>"."\r\n";
	$ionicon_dialog .="</div>"."\r\n";
	$ionicon_dialog .="</div>"."\r\n";
	$ionicon_dialog .="</div>"."\r\n";
	$ionicon_dialog .="</div>"."\r\n";
	
	$tags_html .= "<div class=\"header\">" ;
	$tags_html .= "<div class=\"container-fluid\">" ;
	$tags_html .= "<ul class=\"nav nav-pills pull-right\">" ;
	$tags_html .= "<li><a href=\"?logout\">Logout</a></li>" ;
	$tags_html .= "</ul>" ;
	$tags_html .= "<h1 class=\"text-muted\">Laundry Service</h1>" ;
	$tags_html .= "<p>Created using Page Builder -> Eazy Setup Membership</p>" ;
	$tags_html .= "</div>" ;
	$tags_html .= "</div>" ;
	$tags_html .= "<div class=\"container-fluid\">" ;
	$tags_html .= "<div class=\"row\">" ;
	$tags_html .= "<div class=\"col-md-".$sidebaleft."\">" ;
	$tags_html .= "<div class=\"panel panel-default\">" ;
	$tags_html .= "<div class=\"panel-body\">" ;
	$tags_html .= "<ul class=\"nav nav-pills ".$config["navbar"]."\">" ;
	if($_GET["table"]=="home"){
		$tags_html .= "<li class=\"active\"><a href='?table=home'>".@LANG_HOME."</a></li>" ;
	}else{
		$tags_html .= "<li><a href='?table=home'>".@LANG_HOME."</a></li>" ;
	}
	if($_GET["table"]=="orders"){
		$tags_html .= "<li class=\"active\"><a href='?table=orders'>". @LANG_ORDER."</a></li>" ;
	}else{
		$tags_html .= "<li><a href='?table=orders'>". @LANG_ORDER."</a></li>" ;
	}
	if($_GET["table"]=="users"){
		$tags_html .= "<li class=\"active\"><a href='?table=users'>". @LANG_USER."</a></li>" ;
	}else{
		$tags_html .= "<li><a href='?table=users'>". @LANG_USER."</a></li>" ;
	}
	if($config["kcfinder"]==true){
		if($_GET["table"]=="filebrowser"){
			$tags_html .= "<li class=\"active\"><a href='?table=filebrowser'>".@LANG_FILE_BROWSER."</a></li>" ;
		}else{
			$tags_html .= "<li><a href='?table=filebrowser'>".@LANG_FILE_BROWSER."</a></li>" ;
		}
	}
	$tags_html .= "</ul>" ;
	$tags_html .= "</div>" ;
	$tags_html .= "</div>" ;
	$tags_html .= "</div>" ;
	$tags_html .= "<div class=\"col-md-".$sidebaright."\">" ;
	$tags_html .= "<div class=\"panel panel-default\">" ;
	$tags_html .= "<div class=\"panel-body\">" ;
	switch($_GET["table"]){
		case "home":
					$tags_html .= "<h4 class=\"page-title\">Laundry Service</h4>" ;
					$tags_html .= "<div class=\"row\">" ;
					$tags_html .= "<div class=\"col-md-4\">" ;
					$tags_html .= "<div class=\"panel panel-primary\" >" ;
					$tags_html .= "<div class=\"panel-heading\">" ;
					$tags_html .= "<h4  class=\"panel-title\"><span class=\"glyphicon glyphicon-list-alt\"></span> ". @LANG_ORDER ."</h4>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "<div class=\"panel-body\" style=\"min-height: 150px;\">" ;
					$tags_html .= "<ul class=\"list\">" ;
					/** fetch data from mysql **/
					$sql_query = "SELECT * FROM `order` ORDER BY `id`  DESC LIMIT 0 , 5" ;
					if($result = $mysql->query($sql_query)){
						while ($data = $result->fetch_array()){
							$tags_html .= "<li>" . htmlentities(stripslashes($data["service"])) . "</li>" ;
						}
					}
					$tags_html .= "</ul>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "<div class=\"panel-footer text-right\"><a href=\"?table=orders\" class=\"btn btn-sm btn-primary\">".LANG_MORE."</a></div>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "<div class=\"col-md-4\">" ;
					$tags_html .= "<div class=\"panel panel-success\" >" ;
					$tags_html .= "<div class=\"panel-heading\">" ;
					$tags_html .= "<h4  class=\"panel-title\"><span class=\"glyphicon glyphicon-list-alt\"></span> ". @LANG_USER ."</h4>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "<div class=\"panel-body\" style=\"min-height: 150px;\">" ;
					$tags_html .= "<ul class=\"list\">" ;
					/** fetch data from mysql **/
					$sql_query = "SELECT * FROM `user` ORDER BY `id`  DESC LIMIT 0 , 5" ;
					if($result = $mysql->query($sql_query)){
						while ($data = $result->fetch_array()){
							$tags_html .= "<li>" . htmlentities(stripslashes($data["fullname"])) . "</li>" ;
						}
					}
					$tags_html .= "</ul>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "<div class=\"panel-footer text-right\"><a href=\"?table=users\" class=\"btn btn-sm btn-success\">".LANG_MORE."</a></div>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "</div>" ;
					$tags_html .= "</div>" ;
		break;
		// TODO: Orders
		case "orders":
					$tags_html .= "<h4>". @LANG_ORDER ."</h4>" ;
				switch($_GET["action"]){
					// TODO: ---- listing
					case "list":
					$tags_html .= "<ul class=\"nav nav-tabs\">" ;
					$tags_html .= "<li class=\"active\"><a href=\"?table=orders\">".@LANG_LIST."</a></li>" ;
					$tags_html .= "<li><a href=\"?table=orders&action=add\">".@LANG_ADD."</a></li>" ;
					$tags_html .= "</ul>" ;
					$tags_html .= "<br/>" ;
					$tags_html .= "<div class=\"table-responsive\">" ;
					$table_html = "<table id=\"datatable\" class=\"table table-striped table-hover\">" ;
					$table_html .= "<thead>" ;
					$table_html .= "<tr>" ;
					$table_html .= "<th>".@LANG_DATE."</th>" ;
					$table_html .= "<th>".@LANG_SERVICE."</th>" ;
					$table_html .= "<th>".@LANG_UNAME."</th>" ;
					$table_html .= "<th>".@LANG_STATUS."</th>" ;
					$table_html .= "<th>".@LANG_NOTE."</th>" ;
					$table_html .= "<th style=\"width:100px;\">Action</th>" ;
					$table_html .= "</tr>" ;
					$table_html .= "</thead>" ;
					$table_html .= "<tbody>" ;
					/** fetch data from mysql **/
					$sql_query = "SELECT * FROM `order`" ;
					if($result = $mysql->query($sql_query)){
						while ($data = $result->fetch_array()){
							$table_html .= "<tr>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["date"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["service"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["uname"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["status"])) . "</td>" ;
							$content_html = substr( strip_tags($data["note"]),0,50);
							$table_html .= "<td>" . stripslashes($content_html). "...</td>" ;
							$table_html .= "<td>" ;
							$table_html .= "<div class=\"btn-group\" >" ;
							$table_html .= "<a class=\"btn btn-sm btn-warning\" href=\"?table=orders&action=edit&id=". $data["id"]. "\"><span class=\"glyphicon glyphicon-pencil\"></span></a> " ;
							$table_html .= "<a onclick=\"return confirm('Are you sure you want to delete ID#". $data["id"]. "')\" class=\"btn btn-sm btn-danger\" href=\"?table=orders&action=delete&id=". $data["id"]. "\"><span class=\"glyphicon glyphicon-trash\"></span></a> " ;
							$table_html .= "</div>" ;
							$table_html .= "</td>" ;
							$table_html .= "</tr>" ;
							$table_html .= "\r\n" ;
						}
						$result->close();
					}
				$table_html .= "</tbody>" ;
				$table_html .= "</table>" ;
				$table_html .= "</div>" ;
				$tags_html .= $table_html;
				break;
			case "add":
				// TODO: ---- add
				$tags_html .= "<ul class=\"nav nav-tabs\">" ;
				$tags_html .= "<li><a href=\"?table=orders&action=list\">".@LANG_LIST."</a></li>" ;
				$tags_html .= "<li class=\"active\"><a href=\"?table=orders&action=add\">".@LANG_ADD."</a></li>" ;
				$tags_html .= "</ul>" ;
				$tags_html .= "<br/>" ;
	
				/** push button **/
				if(isset($_POST["add"])){
	
					/** avoid error **/
					$data_date = "";
					$data_service = "";
					$data_uname = "";
					$data_status = "";
					$data_note = "";
	
					/** get input **/
					if(isset($_POST["date"])){
						$data_date = addslashes($_POST["date"]);
					}
					if(isset($_POST["service"])){
						$data_service = addslashes($_POST["service"]);
					}
					if(isset($_POST["uname"])){
						$data_uname = addslashes($_POST["uname"]);
					}
					if(isset($_POST["status"])){
						$data_status = addslashes($_POST["status"]);
					}
					if(isset($_POST["note"])){
						$data_note = addslashes($_POST["note"]);
					}
	
					/** prepare save to mysql **/
					$sql_query = "INSERT INTO `order` (`date`,`service`,`uname`,`status`,`note`) VALUES ('$data_date','$data_service','$data_uname','$data_status','$data_note')" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=orders&action=list");
				}
	
	
				/** Create Form **/
				$tags_html .= '
				<form id="order" action="" method="post" enctype="multipart/form-data">
					

<!--
// TODO: ----|-- form : ID
-->


<!--
// TODO: ----|-- form : DATE
-->
<div class="form-group">
	<label for="date">' . @LANG_DATE. '</label>
	<input  id="date" class="form-control input-date" data-type="text" type="text" name="date" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : SERVICE
-->
<div class="form-group">
	<label for="service">' . @LANG_SERVICE. '</label>
	<input  id="service" class="form-control input-service" data-type="heading-1" type="text" name="service" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : UNAME
-->
<div class="form-group">
	<label for="uname">' . @LANG_UNAME. '</label>
	<input  id="uname" class="form-control input-uname" data-type="as_username" type="text" name="uname" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : STATUS
-->
<div class="form-group">
	<label for="status">' . @LANG_STATUS. '</label>
	<input  id="status" class="form-control input-status" data-type="text" type="text" name="status" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : NOTE
-->
<div class="form-group">
	<label for="note">' . @LANG_NOTE. '</label>
	<textarea id="note" class="form-control input-note"  data-type="to_trusted" name="note" ></textarea>
	<p class="help-block"></p>
</div>
					<div class="form-group">
						<label for="add"></label>
						<input class="btn btn-primary" type="submit" name="add" />
					</div>
				</form>
				';
			break;
		case "edit":
			// TODO: ---- edit
			$tags_html .= "<ul class=\"nav nav-tabs\">" ;
			$tags_html .= "<li><a href=\"?table=orders&action=list\">".@LANG_LIST."</a></li>" ;
			$tags_html .= "<li><a href=\"?table=orders&action=add\">".@LANG_ADD."</a></li>" ;
			$tags_html .= "<li class=\"active\"><a href=\"#\">".@LANG_EDIT."</a></li>" ;
			$tags_html .= "</ul>" ;
			$tags_html .= "<br/>" ;
			/** avoid error **/
			if(isset($_GET["id"])){
				/** fix security **/
				$entry_id = (int)$_GET["id"];
	
				/** avoid blank field **/
				$data_date = "";
				$data_service = "";
				$data_uname = "";
				$data_status = "";
				$data_note = "";
	
				/** push button **/
				if(isset($_POST["edit"])){
					/** get input **/
					if(isset($_POST["date"])){
						$data_date = addslashes($_POST["date"]);
					}
					if(isset($_POST["service"])){
						$data_service = addslashes($_POST["service"]);
					}
					if(isset($_POST["uname"])){
						$data_uname = addslashes($_POST["uname"]);
					}
					if(isset($_POST["status"])){
						$data_status = addslashes($_POST["status"]);
					}
					if(isset($_POST["note"])){
						$data_note = addslashes($_POST["note"]);
					}
	
					/** update data to sql **/
					$sql_query = "UPDATE `order` SET `date` = '$data_date',`service` = '$data_service',`uname` = '$data_uname',`status` = '$data_status',`note` = '$data_note' WHERE `id`=$entry_id" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=orders&action=list");
				}
	
			/** fetch current data **/
			$sql_query = "SELECT * FROM `order`  WHERE `id`=$entry_id LIMIT 0,1" ;
			if($result = $mysql->query($sql_query)){
				while ($data = $result->fetch_array()){
					$rows[] = $data;
				}
				$result->close();
			}
	
			/** get single data **/
			if(isset($rows[0]["date"])){
				$data_date = stripslashes($rows[0]["date"]) ;
			}
			if(isset($rows[0]["service"])){
				$data_service = stripslashes($rows[0]["service"]) ;
			}
			if(isset($rows[0]["uname"])){
				$data_uname = stripslashes($rows[0]["uname"]) ;
			}
			if(isset($rows[0]["status"])){
				$data_status = stripslashes($rows[0]["status"]) ;
			}
			if(isset($rows[0]["note"])){
				$data_note = stripslashes($rows[0]["note"]) ;
			}
	
			/** buat form edit **/
			$tags_html .= '
<form action="" method="post" enctype="multipart/form-data" id="order">


<!--
// TODO: ----|-- form : ID
-->


<!--
// TODO: ----|-- form : DATE
-->
<div class="form-group">
	<label for="date">' . @LANG_DATE. '</label>
	<input  id="date" class="form-control input-date" data-type="text" type="text" name="date" placeholder="" value="'.htmlentities($data_date).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : SERVICE
-->
<div class="form-group">
	<label for="service">' . @LANG_SERVICE. '</label>
	<input  id="service" class="form-control input-service" data-type="heading-1" type="text" name="service" placeholder="" value="'.htmlentities($data_service).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : UNAME
-->
<div class="form-group">
	<label for="uname">' . @LANG_UNAME. '</label>
	<input  id="uname" class="form-control input-uname" data-type="as_username" type="text" name="uname" placeholder="" value="'.htmlentities($data_uname).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : STATUS
-->
<div class="form-group">
	<label for="status">' . @LANG_STATUS. '</label>
	<input  id="status" class="form-control input-status" data-type="text" type="text" name="status" placeholder="" value="'.htmlentities($data_status).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : NOTE
-->
<div class="form-group">
	<label for="note">' . @LANG_NOTE. '</label>
	<textarea id="note" class="form-control input-note"  data-type="to_trusted" name="note" >'.htmlentities($data_note).'</textarea>
	<p class="help-block"></p>
</div>
<div class="form-group">
	<label for="edit"></label>
	<input class="btn btn-primary" type="submit" name="edit" />
</div>
</form>
				';
			};
			break;
			case "delete":
			// TODO: ---- delete
				/** avoid error **/
				if(isset($_GET["id"])){
					/** fix security **/
					$entry_id = (int)$_GET["id"];
					/** delete item in sql **/
					$sql_query = "DELETE FROM `order` WHERE `id`=$entry_id" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=orders&action=list");
				};
				break;
			}
			break;
		// TODO: Users
		case "users":
					$tags_html .= "<h4>". @LANG_USER ."</h4>" ;
				switch($_GET["action"]){
					// TODO: ---- listing
					case "list":
					$tags_html .= "<ul class=\"nav nav-tabs\">" ;
					$tags_html .= "<li class=\"active\"><a href=\"?table=users\">".@LANG_LIST."</a></li>" ;
					$tags_html .= "<li><a href=\"?table=users&action=add\">".@LANG_ADD."</a></li>" ;
					$tags_html .= "</ul>" ;
					$tags_html .= "<br/>" ;
					$tags_html .= "<div class=\"table-responsive\">" ;
					$table_html = "<table id=\"datatable\" class=\"table table-striped table-hover\">" ;
					$table_html .= "<thead>" ;
					$table_html .= "<tr>" ;
					$table_html .= "<th>".@LANG_FULLNAME."</th>" ;
					$table_html .= "<th>".@LANG_UNAME."</th>" ;
					$table_html .= "<th>".@LANG_PWD."</th>" ;
					$table_html .= "<th>".@LANG_ADDRESS."</th>" ;
					$table_html .= "<th>".@LANG_TYPE_IC."</th>" ;
					$table_html .= "<th>".@LANG_NO_IC."</th>" ;
					$table_html .= "<th>".@LANG_PHONE."</th>" ;
					$table_html .= "<th>".@LANG_EMAIL."</th>" ;
					$table_html .= "<th style=\"width:100px;\">Action</th>" ;
					$table_html .= "</tr>" ;
					$table_html .= "</thead>" ;
					$table_html .= "<tbody>" ;
					/** fetch data from mysql **/
					$sql_query = "SELECT * FROM `user`" ;
					if($result = $mysql->query($sql_query)){
						while ($data = $result->fetch_array()){
							$table_html .= "<tr>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["fullname"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["uname"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["pwd"])) . "</td>" ;
							$content_html = substr( strip_tags($data["address"]),0,50);
							$table_html .= "<td>" . stripslashes($content_html). "...</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["type_ic"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["no_ic"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["phone"])) . "</td>" ;
							$table_html .= "<td>" . htmlentities(stripslashes($data["email"])) . "</td>" ;
							$table_html .= "<td>" ;
							$table_html .= "<div class=\"btn-group\" >" ;
							$table_html .= "<a class=\"btn btn-sm btn-warning\" href=\"?table=users&action=edit&id=". $data["id"]. "\"><span class=\"glyphicon glyphicon-pencil\"></span></a> " ;
							$table_html .= "<a onclick=\"return confirm('Are you sure you want to delete ID#". $data["id"]. "')\" class=\"btn btn-sm btn-danger\" href=\"?table=users&action=delete&id=". $data["id"]. "\"><span class=\"glyphicon glyphicon-trash\"></span></a> " ;
							$table_html .= "</div>" ;
							$table_html .= "</td>" ;
							$table_html .= "</tr>" ;
							$table_html .= "\r\n" ;
						}
						$result->close();
					}
				$table_html .= "</tbody>" ;
				$table_html .= "</table>" ;
				$table_html .= "</div>" ;
				$tags_html .= $table_html;
				break;
			case "add":
				// TODO: ---- add
				$tags_html .= "<ul class=\"nav nav-tabs\">" ;
				$tags_html .= "<li><a href=\"?table=users&action=list\">".@LANG_LIST."</a></li>" ;
				$tags_html .= "<li class=\"active\"><a href=\"?table=users&action=add\">".@LANG_ADD."</a></li>" ;
				$tags_html .= "</ul>" ;
				$tags_html .= "<br/>" ;
	
				/** push button **/
				if(isset($_POST["add"])){
	
					/** avoid error **/
					$data_fullname = "";
					$data_uname = "";
					$data_pwd = "";
					$data_address = "";
					$data_type_ic = "";
					$data_no_ic = "";
					$data_phone = "";
					$data_email = "";
	
					/** get input **/
					if(isset($_POST["fullname"])){
						$data_fullname = addslashes($_POST["fullname"]);
					}
					if(isset($_POST["uname"])){
						$data_uname = addslashes($_POST["uname"]);
					}
					if(isset($_POST["pwd"])){
						$data_pwd = addslashes($_POST["pwd"]);
					}
					if(isset($_POST["address"])){
						$data_address = addslashes($_POST["address"]);
					}
					if(isset($_POST["type_ic"])){
						$data_type_ic = addslashes($_POST["type_ic"]);
					}
					if(isset($_POST["no_ic"])){
						$data_no_ic = addslashes($_POST["no_ic"]);
					}
					if(isset($_POST["phone"])){
						$data_phone = addslashes($_POST["phone"]);
					}
					if(isset($_POST["email"])){
						$data_email = addslashes($_POST["email"]);
					}
	
					/** prepare save to mysql **/
					$sql_query = "INSERT INTO `user` (`fullname`,`uname`,`pwd`,`address`,`type_ic`,`no_ic`,`phone`,`email`) VALUES ('$data_fullname','$data_uname','$data_pwd','$data_address','$data_type_ic','$data_no_ic','$data_phone','$data_email')" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=users&action=list");
				}
	
	
				/** Create Form **/
				$tags_html .= '
				<form id="user" action="" method="post" enctype="multipart/form-data">
					

<!--
// TODO: ----|-- form : ID
-->


<!--
// TODO: ----|-- form : FULLNAME
-->
<div class="form-group">
	<label for="fullname">' . @LANG_FULLNAME. '</label>
	<input  id="fullname" class="form-control input-fullname" data-type="heading-1" type="text" name="fullname" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : UNAME
-->
<div class="form-group">
	<label for="uname">' . @LANG_UNAME. '</label>
	<input  id="uname" class="form-control input-uname" data-type="as_username" type="text" name="uname" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : PWD
-->
<div class="form-group">
	<label for="pwd">' . @LANG_PWD. '</label>
	<input  id="pwd" class="form-control input-pwd" data-type="as_password" type="text" name="pwd" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : ADDRESS
-->
<div class="form-group">
	<label for="address">' . @LANG_ADDRESS. '</label>
	<textarea id="address" class="form-control input-address"  data-type="to_trusted" name="address" ></textarea>
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : TYPE_IC
-->
<div class="form-group">
	<label for="type_ic">' . @LANG_TYPE_IC. '</label>
	<input  id="type_ic" class="form-control input-type_ic" data-type="text" type="text" name="type_ic" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : NO_IC
-->
<div class="form-group">
	<label for="no_ic">' . @LANG_NO_IC. '</label>
	<input  id="no_ic" class="form-control input-no_ic" data-type="text" type="text" name="no_ic" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : PHONE
-->
<div class="form-group">
	<label for="phone">' . @LANG_PHONE. '</label>
	<input  id="phone" class="form-control input-phone" data-type="text" type="text" name="phone" placeholder="" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : EMAIL
-->
<div class="form-group">
	<label for="email">' . @LANG_EMAIL. '</label>
	<input  id="email" class="form-control input-email" data-type="text" type="text" name="email" placeholder="" />
	<p class="help-block"></p>
</div>
					<div class="form-group">
						<label for="add"></label>
						<input class="btn btn-primary" type="submit" name="add" />
					</div>
				</form>
				';
			break;
		case "edit":
			// TODO: ---- edit
			$tags_html .= "<ul class=\"nav nav-tabs\">" ;
			$tags_html .= "<li><a href=\"?table=users&action=list\">".@LANG_LIST."</a></li>" ;
			$tags_html .= "<li><a href=\"?table=users&action=add\">".@LANG_ADD."</a></li>" ;
			$tags_html .= "<li class=\"active\"><a href=\"#\">".@LANG_EDIT."</a></li>" ;
			$tags_html .= "</ul>" ;
			$tags_html .= "<br/>" ;
			/** avoid error **/
			if(isset($_GET["id"])){
				/** fix security **/
				$entry_id = (int)$_GET["id"];
	
				/** avoid blank field **/
				$data_fullname = "";
				$data_uname = "";
				$data_pwd = "";
				$data_address = "";
				$data_type_ic = "";
				$data_no_ic = "";
				$data_phone = "";
				$data_email = "";
	
				/** push button **/
				if(isset($_POST["edit"])){
					/** get input **/
					if(isset($_POST["fullname"])){
						$data_fullname = addslashes($_POST["fullname"]);
					}
					if(isset($_POST["uname"])){
						$data_uname = addslashes($_POST["uname"]);
					}
					if(isset($_POST["pwd"])){
						$data_pwd = addslashes($_POST["pwd"]);
					}
					if(isset($_POST["address"])){
						$data_address = addslashes($_POST["address"]);
					}
					if(isset($_POST["type_ic"])){
						$data_type_ic = addslashes($_POST["type_ic"]);
					}
					if(isset($_POST["no_ic"])){
						$data_no_ic = addslashes($_POST["no_ic"]);
					}
					if(isset($_POST["phone"])){
						$data_phone = addslashes($_POST["phone"]);
					}
					if(isset($_POST["email"])){
						$data_email = addslashes($_POST["email"]);
					}
	
					/** update data to sql **/
					$sql_query = "UPDATE `user` SET `fullname` = '$data_fullname',`uname` = '$data_uname',`pwd` = '$data_pwd',`address` = '$data_address',`type_ic` = '$data_type_ic',`no_ic` = '$data_no_ic',`phone` = '$data_phone',`email` = '$data_email' WHERE `id`=$entry_id" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=users&action=list");
				}
	
			/** fetch current data **/
			$sql_query = "SELECT * FROM `user`  WHERE `id`=$entry_id LIMIT 0,1" ;
			if($result = $mysql->query($sql_query)){
				while ($data = $result->fetch_array()){
					$rows[] = $data;
				}
				$result->close();
			}
	
			/** get single data **/
			if(isset($rows[0]["fullname"])){
				$data_fullname = stripslashes($rows[0]["fullname"]) ;
			}
			if(isset($rows[0]["uname"])){
				$data_uname = stripslashes($rows[0]["uname"]) ;
			}
			if(isset($rows[0]["pwd"])){
				$data_pwd = stripslashes($rows[0]["pwd"]) ;
			}
			if(isset($rows[0]["address"])){
				$data_address = stripslashes($rows[0]["address"]) ;
			}
			if(isset($rows[0]["type_ic"])){
				$data_type_ic = stripslashes($rows[0]["type_ic"]) ;
			}
			if(isset($rows[0]["no_ic"])){
				$data_no_ic = stripslashes($rows[0]["no_ic"]) ;
			}
			if(isset($rows[0]["phone"])){
				$data_phone = stripslashes($rows[0]["phone"]) ;
			}
			if(isset($rows[0]["email"])){
				$data_email = stripslashes($rows[0]["email"]) ;
			}
	
			/** buat form edit **/
			$tags_html .= '
<form action="" method="post" enctype="multipart/form-data" id="user">


<!--
// TODO: ----|-- form : ID
-->


<!--
// TODO: ----|-- form : FULLNAME
-->
<div class="form-group">
	<label for="fullname">' . @LANG_FULLNAME. '</label>
	<input  id="fullname" class="form-control input-fullname" data-type="heading-1" type="text" name="fullname" placeholder="" value="'.htmlentities($data_fullname).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : UNAME
-->
<div class="form-group">
	<label for="uname">' . @LANG_UNAME. '</label>
	<input  id="uname" class="form-control input-uname" data-type="as_username" type="text" name="uname" placeholder="" value="'.htmlentities($data_uname).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : PWD
-->
<div class="form-group">
	<label for="pwd">' . @LANG_PWD. '</label>
	<input  id="pwd" class="form-control input-pwd" data-type="as_password" type="text" name="pwd" placeholder="" value="'.htmlentities($data_pwd).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : ADDRESS
-->
<div class="form-group">
	<label for="address">' . @LANG_ADDRESS. '</label>
	<textarea id="address" class="form-control input-address"  data-type="to_trusted" name="address" >'.htmlentities($data_address).'</textarea>
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : TYPE_IC
-->
<div class="form-group">
	<label for="type_ic">' . @LANG_TYPE_IC. '</label>
	<input  id="type_ic" class="form-control input-type_ic" data-type="text" type="text" name="type_ic" placeholder="" value="'.htmlentities($data_type_ic).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : NO_IC
-->
<div class="form-group">
	<label for="no_ic">' . @LANG_NO_IC. '</label>
	<input  id="no_ic" class="form-control input-no_ic" data-type="text" type="text" name="no_ic" placeholder="" value="'.htmlentities($data_no_ic).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : PHONE
-->
<div class="form-group">
	<label for="phone">' . @LANG_PHONE. '</label>
	<input  id="phone" class="form-control input-phone" data-type="text" type="text" name="phone" placeholder="" value="'.htmlentities($data_phone).'" />
	<p class="help-block"></p>
</div>


<!--
// TODO: ----|-- form : EMAIL
-->
<div class="form-group">
	<label for="email">' . @LANG_EMAIL. '</label>
	<input  id="email" class="form-control input-email" data-type="text" type="text" name="email" placeholder="" value="'.htmlentities($data_email).'" />
	<p class="help-block"></p>
</div>
<div class="form-group">
	<label for="edit"></label>
	<input class="btn btn-primary" type="submit" name="edit" />
</div>
</form>
				';
			};
			break;
			case "delete":
			// TODO: ---- delete
				/** avoid error **/
				if(isset($_GET["id"])){
					/** fix security **/
					$entry_id = (int)$_GET["id"];
					/** delete item in sql **/
					$sql_query = "DELETE FROM `user` WHERE `id`=$entry_id" ;
					$stmt = $mysql->prepare($sql_query);
					$stmt->execute();
					$stmt->close();
					header("Location: ?table=users&action=list");
				};
				break;
			}
			break;
		// TODO: Push Notification
		case "push-notification":
			$tags_html .= "<h4 class=\"page-title\">".@LANG_PUSH_NOTIFICATION."</h4>" ;
			if(!ini_get("allow_url_fopen")) {
				$tags_html .="<p class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Error!</strong> The PHP allow_url_fopen setting is disabled, please edit your <a target=\"_blank\" class=\"btn btn-danger btn-xs\" href=\"http://php.net/allow-url-fopen\">php.ini</a></p>";
			}
			if(!function_exists("curl_version")) {
				$tags_html .="<p class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Error!</strong> cURL is NOT installed in your PHP installation</p>";
			}
			if(isset($_POST["push"])){
				$content = array("en" => $_POST["message"]);
				$fields = array("app_id" => $config["onesignal-appid"],"included_segments" => array("All"),"data" => array("page" =>  $_POST["page"] ), "contents" => $content);
				$fields = json_encode($fields);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8","Authorization: Basic " . $config["onesignal-appkey"]));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$response = json_decode(curl_exec($ch),true);
				curl_close($ch);
				if(isset($response["errors"][0])){
					$tags_html .= "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>".$response["errors"][0]."</div>";
				}else{
					$tags_html .= "<div class=\"alert alert-dismissible alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>ID #".$response["id"]." with ".$response["recipients"]." recipients</div>";
				}
			}
				/** Create Form **/
				$tags_html .= '
				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="message">Message</label>
						<textarea class="form-control" name="message" ></textarea>
					</div>
					<div class="form-group">
						<label for="page">Specific Pages</label>
						<input class="form-control" type="text" name="page" />
					</div>
					<div class="form-group">
						<label for="submit"></label>
						<input class="btn btn-primary" type="submit" name="push" />
					</div>
				</form>
				';
			break;
		// TODO: FileBrowser
		case "filebrowser":
			if(!isset($_GET["type"])){
					$_GET["type"]="image";
			}
			$tags_html .= "<h4 class=\"page-title\">".@LANG_FILE_BROWSER."</h4>" ;
					$tags_html .= "<ul class=\"nav nav-tabs\">" ;
			if($_GET["type"]=="image"){
					$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=image\">".@LANG_IMAGES."</a></li>" ;
				}else{
					$tags_html .= "<li><a href=\"?table=filebrowser&type=image\">".@LANG_IMAGES."</a></li>" ;
				}
			if($_GET["type"]=="file"){
					$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=file\">".@LANG_FILES."</a></li>" ;
				}else{
					$tags_html .= "<li><a href=\"?table=filebrowser&type=file\">".@LANG_FILES."</a></li>" ;
				}
			if($_GET["type"]=="media"){
					$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=media\">".@LANG_MEDIAS."</a></li>" ;
				}else{
					$tags_html .= "<li><a href=\"?table=filebrowser&type=media\">".@LANG_MEDIAS."</a></li>" ;
				}
					$tags_html .= "</ul>" ;
					$tags_html .= "<br/>" ;
			$tags_html .= "<div>" ;
			$tags_html .= "<iframe src=\"kcfinder/browse.php?opener=tinymce4&type=".$_GET["type"]."\" style=\"border:0;padding:0;margin:0;overflow:hidden;min-height:480px;width:100%;\" ></iframe>" ;
			$tags_html .= "</div>" ;
			break;
	}
		$tags_html .= "</div>" ;
		$tags_html .= "</div>" ;
		$tags_html .= "</div>" ;
		$tags_html .= "</div>" ;
		$tags_html .= "</div>" ;
		$tags_html .= "<footer>" ;
		$tags_html .= "<div class=\"container-fluid\">" ;
		$tags_html .= "<div class=\"row\">" ;
		$tags_html .= "<div class=\"col-md-12\"><p class=\"text-left navbar-text\">Copyright &copy; AnaskiNet 2018 - All Rights Reserved</p></div>" ;
		$tags_html .= "</div>" ;
		$tags_html .= $ionicon_dialog ;
		$tags_html .= "</div>" ;
		$tags_html .= "</footer>" ;
	}else{
		
        if($_GET["login"] == "error"){
            $error_notice = "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><p>The email address or password is invalid. Please try to log in again.</p></div>";
        }
        		$tags_html .= '
<div class="container">    
    <div style="max-width: 330px;margin: 0 auto;">    
        <form method="post" enctype="multipart/form-data">
            <h2 class="form-signin-heading">Please Log in</h2>
            '.$error_notice.'
            <div class="form-group">
            	<label for="username">Username</label>
            	<input class="form-control" type="text" name="email" placeholder="Email address" required autofocus />
            </div>
            <div class="form-group">
            	<label for="password">Password</label>
            	<input class="form-control" type="password" name="password" placeholder="Password" required autofocus/>
            </div>
            <input type="submit" class="btn btn-primary" name="log-in"/>
        </form>
    </div>
</div>
' ;
	}
	
	$kcfinder_tinymce = $kcfinder_input = null;
	if($config["kcfinder"]==true){
		$kcfinder_tinymce = '
    file_browser_callback : function(field, url, type, win) {
		tinyMCE.activeEditor.windowManager.open({
			file: "kcfinder/browse.php?opener=tinymce4&cms=ima_builder&field=" + field + "&type=" + type,
			title: "KCFinder",
			width: 640,
			height: 500,
			inline: true,
			close_previous: false
		}, {
			window: win,
			input: field
		});
     return false;
	},' ;
		$kcfinder_input = '
            var KCFinderTarget = "";
            window.KCFinder = {
            	callBack: function(path) {
            		$("#" + KCFinderTarget).val(main_url + path);
            	},
            	Open: function(prop_id, file_type) {
            		KCFinderTarget = prop_id;
            		var newwindow = window.open("./kcfinder/browse.php?type=" + file_type, "Image Editor", "height=480,width=1024");
            		if (window.focus) {
            			newwindow.focus()
            		}
            	}
            }; 
            $("*[data-type=\'images\']").click(function(){
                KCFinder.Open($(this).prop("id"), "image");
            });
            $("*[data-type=\'audio\']").click(function(){
                KCFinder.Open($(this).prop("id"), "media");
            });
            $("*[data-type=\'video\']").click(function(){
                KCFinder.Open($(this).prop("id"), "media");
            });
            ';
	};
echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Laundry Service</title>    
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/'.strtolower($config["theme"]).'/bootstrap.min.css" rel="stylesheet"/>
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"/>
    <link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet" media="screen"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript"> var main_url = "'.$main_url.'";</script>
  </head>
  <body>
    '.$tags_html.'
    <script data-pace-options=\'{"ajax":true}\' src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
     
    <script type="text/javascript">
        window.icon_picker_target = "test"; 
        function readURL(input,target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(target).val(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function ionicons(myclass){
            $("#"+window.icon_picker_target).val(myclass);
            $("#ionicons").modal("hide");
        }
        $(document).ready(function() {
            if($("#datatable").length){
        	   $("#datatable").dataTable();
            }
            tinymce.init({
                selector: "textarea[data-type=\'to_trusted\']",
                plugins: "code textcolor image link table  contextmenu",
                toolbar1: "undo redo | forecolor backcolor  | bold italic underline | alignleft aligncenter alignright alignjustify | image table | numlist bullist | media",              
                toolbar2: "styleselect fontsizeselect",
                force_br_newlines : false,
                force_p_newlines : false,
                forced_root_block : "",
                extended_valid_elements : "*[*]",
                valid_elements : "*[*]",
                link_class_list:[{"text":"None ","value":""},{"text":"Button Light","value":"button button-light ink"},{"text":"Button Stable","value":"button button-stable ink"},{"text":"Button Positive","value":"button button-positive ink"},{"text":"Button Calm","value":"button button-calm ink"},{"text":"Button Balanced","value":"button button-balanced ink"},{"text":"Button Energized","value":"button button-energized ink"},{"text":"Button Assertive","value":"button button-assertive ink"},{"text":"Button Royal","value":"button button-royal ink"},{"text":"Button Dark","value":"button button-dark ink"}],
                target_list : [{text: "None",value: ""},{text: "New window",alue: "_blank"},{text: "Top window",value: "_top"},{text: "Self window",value: "_self"}],
                '.$kcfinder_tinymce.'               
            });
           	$("input[data-type=\'icon\']").click(function(){
           	       window.icon_picker_target = $(this).prop("id");
                   $("#ionicons").modal("show");
            });
            $("input[data-type=\'image-base64\']").change(function(){
                var target = $(this).attr("data-target");
                readURL(this,target);
            });
            '.$kcfinder_input.'                              
        });
        
    /** relation **/    
    '.$js_for_relation.'
    
    /** format **/
    $(function () {
        $("'.$config["input_datetime"].'").datetimepicker({
            format: "YYYY-MM-DD h:mm:ss"
        });
        
        $("'.$config["input_date"].'").datetimepicker({
            format: "YYYY-MM-DD"
        });
                
    });
        
    </script>
    
    </body>
</html>';	
	
?>