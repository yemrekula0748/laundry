<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright AnaskiNet 2018
 * @package laundry_service
 * 
 * 
 * Created using Ionic App Builder
 * http://codecanyon.net/item/ionic-mobile-app-builder/15716727
 */


/** CONFIG:START **/
$config["host"] 		= "89.19.30.15" ; 		//host
$config["user"] 		= "u7970538_kulamobil" ; 		//Username SQL
$config["pass"] 		= "GHac67A0" ; 		//Password SQL
$config["dbase"] 		= "u7970538_kulamobil" ; 		//Database
$config["utf8"] 		= true ; 		//turkish charset set false
$config["abs_url_images"] 		= "http://kulamobil.site/panel/media/image/" ; 		//Absolute Images URL
$config["abs_url_videos"] 		= "http://kulamobil.site/panel/media/media/" ; 		//Absolute Videos URL
$config["abs_url_audios"] 		= "http://kulamobil.site/panel/media/media/" ; 		//Absolute Audio URL
$config["abs_url_files"] 		= "http://kulamobil.site/panel/media/file/" ; 		//Absolute Files URL
$config["image_allowed"][] 		= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; 		//whitelist image
$config["image_allowed"][] 		= array("mimetype"=>"image/jpg","ext"=>"jpg") ; 		
$config["image_allowed"][] 		= array("mimetype"=>"image/png","ext"=>"png") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"text/plain","ext"=>"txt") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"","ext"=>"tmp") ; 		
/** CONFIG:END **/

if(isset($_SERVER["HTTP_X_AUTHORIZATION"])){
	list($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]) = explode(":" , base64_decode(substr($_SERVER["HTTP_X_AUTHORIZATION"],6)));
}
$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Routes not found");

/** connect to mysql **/
$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);
if (mysqli_connect_errno()){
	die(mysqli_connect_error());
}


if(!isset($_GET["json"])){
	$_GET["json"]= "route";
}
if((!isset($_GET["form"])) && ($_GET["json"] == "submit")) {
	$_GET["json"]= "route";
}

if($config["utf8"]==true){
	$mysql->set_charset("utf8");
}

$get_dir = explode("/", $_SERVER["PHP_SELF"]);
unset($get_dir[count($get_dir)-1]);
$main_url = "http://" . $_SERVER["HTTP_HOST"] . implode("/",$get_dir)."/";


switch($_GET["json"]){	
	// TODO: -+- Listing : order
	case "order":
		$rest_api=array();
		// TODO: -+----+- Auth User
		$is_user = false;
		if(isset($_SERVER["PHP_AUTH_USER"])){
			$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
			$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
			$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
			if($result = $mysql->query($auth_sql)){
				$current_user = $result->fetch_array();
				if(isset($current_user["uname"])){
					$is_user = true;
				}
			}
			if($is_user == false){
				$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
				break;
			}
		}else{
			$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
			break;
		}
		$where = $_where = null;
		// TODO: -+----+- Filter by User
		$_where[] = "`uname` = '$php_auth_user'";
		// TODO: -+----+- statement where
		if(isset($_GET["id"])){
			if($_GET["id"]!="-1"){
				$_where[] = "`id` LIKE '%".$mysql->escape_string($_GET["id"])."%'";
			}
		}
		if(isset($_GET["date"])){
			if($_GET["date"]!="-1"){
				$_where[] = "`date` LIKE '%".$mysql->escape_string($_GET["date"])."%'";
			}
		}
		if(isset($_GET["service"])){
			if($_GET["service"]!="-1"){
				$_where[] = "`service` LIKE '%".$mysql->escape_string($_GET["service"])."%'";
			}
		}
		if(isset($_GET["uname"])){
			if($_GET["uname"]!="-1"){
				$_where[] = "`uname` LIKE '%".$mysql->escape_string($_GET["uname"])."%'";
			}
		}
		if(isset($_GET["status"])){
			if($_GET["status"]!="-1"){
				$_where[] = "`status` LIKE '%".$mysql->escape_string($_GET["status"])."%'";
			}
		}
		if(isset($_GET["note"])){
			if($_GET["note"]!="-1"){
				$_where[] = "`note` LIKE '%".$mysql->escape_string($_GET["note"])."%'";
			}
		}
		if(is_array($_where)){
			$where = " WHERE " . implode(" AND ",$_where);
		}
		// TODO: -+----+- orderby
		$order_by = "`id`";
		$sort_by = "DESC";
		if(!isset($_GET["order"])){
			$_GET["order"] = "`id`";
		}
		// TODO: -+----+- sort asc/desc
		if(!isset($_GET["sort"])){
			$_GET["sort"] = "desc";
		}
		if($_GET["sort"]=="asc"){
			$sort_by = "ASC";
		}else{
			$sort_by = "DESC";
		}
		if($_GET["order"]=="id"){
			$order_by = "`id`";
		}
		if($_GET["order"]=="date"){
			$order_by = "`date`";
		}
		if($_GET["order"]=="service"){
			$order_by = "`service`";
		}
		if($_GET["order"]=="uname"){
			$order_by = "`uname`";
		}
		if($_GET["order"]=="status"){
			$order_by = "`status`";
		}
		if($_GET["order"]=="note"){
			$order_by = "`note`";
		}
		if($_GET["order"]=="random"){
			$order_by = "RAND()";
		}
		$limit = 200;
		if(isset($_GET["limit"])){
			$limit = (int)$_GET["limit"] ;
		}
		// TODO: -+----+- SQL Query
		$sql = "SELECT * FROM `order` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;
		if($result = $mysql->query($sql)){
			$z=0;
			while ($data = $result->fetch_array()){
				if(isset($data['id'])){$rest_api[$z]['id'] = $data['id'];}; # id
				if(isset($data['date'])){$rest_api[$z]['date'] = $data['date'];}; # text
				if(isset($data['service'])){$rest_api[$z]['service'] = $data['service'];}; # heading-1
				
				$abs_url_images = $config['abs_url_images'].'/';
				$abs_url_videos = $config['abs_url_videos'].'/';
				$abs_url_audios = $config['abs_url_audios'].'/';
				if(!isset($data['uname'])){$data['uname']='undefined';}; # as_username
				if((substr($data['uname'], 0, 7)=='http://')||(substr($data['uname'], 0, 8)=='https://')){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(substr($data['uname'], 0, 5)=='data:'){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(isset($data['status'])){$rest_api[$z]['status'] = $data['status'];}; # text
				if(isset($data['note'])){$rest_api[$z]['note'] = $data['note'];}; # to_trusted
				$z++;
			}
			$result->close();
			if(isset($_GET["id"])){
				if(isset($rest_api[0])){
					$rest_api = $rest_api[0];
				}
			}
		}

		break;
	
	// TODO: -+- Listing : user
	case "user":
		$rest_api=array();
		// TODO: -+----+- Auth User
		$is_user = false;
		if(isset($_SERVER["PHP_AUTH_USER"])){
			$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
			$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
			$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
			if($result = $mysql->query($auth_sql)){
				$current_user = $result->fetch_array();
				if(isset($current_user["uname"])){
					$is_user = true;
				}
			}
			if($is_user == false){
				$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
				break;
			}
		}else{
			$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
			break;
		}
		$where = $_where = null;
		// TODO: -+----+- Filter by User
		$_where[] = "`uname` = '$php_auth_user'";
		// TODO: -+----+- statement where
		if(isset($_GET["id"])){
			if($_GET["id"]!="-1"){
				$_where[] = "`id` LIKE '%".$mysql->escape_string($_GET["id"])."%'";
			}
		}
		if(isset($_GET["fullname"])){
			if($_GET["fullname"]!="-1"){
				$_where[] = "`fullname` LIKE '%".$mysql->escape_string($_GET["fullname"])."%'";
			}
		}
		if(isset($_GET["uname"])){
			if($_GET["uname"]!="-1"){
				$_where[] = "`uname` LIKE '%".$mysql->escape_string($_GET["uname"])."%'";
			}
		}
		if(isset($_GET["pwd"])){
			if($_GET["pwd"]!="-1"){
				$_where[] = "`pwd` LIKE '%".$mysql->escape_string($_GET["pwd"])."%'";
			}
		}
		if(isset($_GET["address"])){
			if($_GET["address"]!="-1"){
				$_where[] = "`address` LIKE '%".$mysql->escape_string($_GET["address"])."%'";
			}
		}
		if(isset($_GET["type_ic"])){
			if($_GET["type_ic"]!="-1"){
				$_where[] = "`type_ic` LIKE '%".$mysql->escape_string($_GET["type_ic"])."%'";
			}
		}
		if(isset($_GET["no_ic"])){
			if($_GET["no_ic"]!="-1"){
				$_where[] = "`no_ic` LIKE '%".$mysql->escape_string($_GET["no_ic"])."%'";
			}
		}
		if(isset($_GET["phone"])){
			if($_GET["phone"]!="-1"){
				$_where[] = "`phone` LIKE '%".$mysql->escape_string($_GET["phone"])."%'";
			}
		}
		if(isset($_GET["email"])){
			if($_GET["email"]!="-1"){
				$_where[] = "`email` LIKE '%".$mysql->escape_string($_GET["email"])."%'";
			}
		}
		if(is_array($_where)){
			$where = " WHERE " . implode(" AND ",$_where);
		}
		// TODO: -+----+- orderby
		$order_by = "`id`";
		$sort_by = "DESC";
		if(!isset($_GET["order"])){
			$_GET["order"] = "`id`";
		}
		// TODO: -+----+- sort asc/desc
		if(!isset($_GET["sort"])){
			$_GET["sort"] = "desc";
		}
		if($_GET["sort"]=="asc"){
			$sort_by = "ASC";
		}else{
			$sort_by = "DESC";
		}
		if($_GET["order"]=="id"){
			$order_by = "`id`";
		}
		if($_GET["order"]=="fullname"){
			$order_by = "`fullname`";
		}
		if($_GET["order"]=="uname"){
			$order_by = "`uname`";
		}
		if($_GET["order"]=="pwd"){
			$order_by = "`pwd`";
		}
		if($_GET["order"]=="address"){
			$order_by = "`address`";
		}
		if($_GET["order"]=="type_ic"){
			$order_by = "`type_ic`";
		}
		if($_GET["order"]=="no_ic"){
			$order_by = "`no_ic`";
		}
		if($_GET["order"]=="phone"){
			$order_by = "`phone`";
		}
		if($_GET["order"]=="email"){
			$order_by = "`email`";
		}
		if($_GET["order"]=="random"){
			$order_by = "RAND()";
		}
		$limit = 200;
		if(isset($_GET["limit"])){
			$limit = (int)$_GET["limit"] ;
		}
		// TODO: -+----+- SQL Query
		$sql = "SELECT * FROM `user` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;
		if($result = $mysql->query($sql)){
			$z=0;
			while ($data = $result->fetch_array()){
				if(isset($data['id'])){$rest_api[$z]['id'] = $data['id'];}; # id
				if(isset($data['fullname'])){$rest_api[$z]['fullname'] = $data['fullname'];}; # heading-1
				#
				$abs_url_images = $config['abs_url_images'].'/';
				$abs_url_videos = $config['abs_url_videos'].'/';
				$abs_url_audios = $config['abs_url_audios'].'/';
				if(!isset($data['uname'])){$data['uname']='undefined';}; # as_username
				if((substr($data['uname'], 0, 7)=='http://')||(substr($data['uname'], 0, 8)=='https://')){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(substr($data['uname'], 0, 5)=='data:'){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				#
				$abs_url_images = $config['abs_url_images'].'/';
				$abs_url_videos = $config['abs_url_videos'].'/';
				$abs_url_audios = $config['abs_url_audios'].'/';
				if(!isset($data['pwd'])){$data['pwd']='undefined';}; # as_password
				if((substr($data['pwd'], 0, 7)=='http://')||(substr($data['pwd'], 0, 8)=='https://')){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				if(substr($data['pwd'], 0, 5)=='data:'){
					$abs_url_images = $abs_url_videos  = $abs_url_audios = '';
				}
				
				#if(isset($data['address'])){$rest_api[$z]['address'] = $data['address'];}; # to_trusted
				#if(isset($data['type_ic'])){$rest_api[$z]['type_ic'] = $data['type_ic'];}; # text
				#if(isset($data['no_ic'])){$rest_api[$z]['no_ic'] = $data['no_ic'];}; # text
				#if(isset($data['phone'])){$rest_api[$z]['phone'] = $data['phone'];}; # text
				#if(isset($data['email'])){$rest_api[$z]['email'] = $data['email'];}; # text
				$z++;
			}
			$result->close();
			if(isset($_GET["id"])){
				if(isset($rest_api[0])){
					$rest_api = $rest_api[0];
				}
			}
		}

		break;
	// TODO: -+- Authorization
	case "auth":
		// TODO: -+----+- Auth User
		
		$is_user = false;
		if(isset($_SERVER["PHP_AUTH_USER"])){
			$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
			$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
			$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
			if($result = $mysql->query($auth_sql)){
				$current_user = $result->fetch_array();
				if(isset($current_user["uname"])){
					$is_user = true;
				}
			}
			if($is_user === true){
				$rest_api=array("data"=>array("status"=>200,"error"=>"Successfully"),"title"=>"Successfully","message"=>"Successfully");
			}else{
				$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Failed","message"=>"Username or password is incorrect, please try again.");
			}
		}else{
			$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
			break;
		}

		break;
	// TODO: -+- me
	case "me":
		// TODO: -+----+- Auth User
		$is_user = false;
		if(isset($_SERVER["PHP_AUTH_USER"])){
			$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
			$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
			$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
			if($result = $mysql->query($auth_sql)){
				$current_user = $result->fetch_array();
				if(isset($current_user["uname"])){
					$is_user = true;
				}
			}
			if($is_user == true){
				$rest_api["data"]["status"]=200;
				$rest_api["me"]["id"]= $current_user["id"];
				$rest_api["me"]["fullname"]= $current_user["fullname"];
				$rest_api["me"]["uname"]= $current_user["uname"];
				$rest_api["me"]["address"]= $current_user["address"];
				$rest_api["me"]["type_ic"]= $current_user["type_ic"];
				$rest_api["me"]["no_ic"]= $current_user["no_ic"];
				$rest_api["me"]["phone"]= $current_user["phone"];
				$rest_api["me"]["email"]= $current_user["email"];
			}else{
				$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Failed","message"=>"Username or password is incorrect, please try again.");
			}
		}else{
			$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
			break;
		}

		break;
	// TODO: -+- route
	case "route":		$rest_api=array();
		$rest_api["site"]["name"] = "Laundry Service" ;
		$rest_api["site"]["description"] = "Created using Page Builder -> Eazy Setup Membership" ;
		$rest_api["site"]["imabuilder"] = "rev18.04.16" ;

		$rest_api["routes"][0]["namespace"] = "order";
		$rest_api["routes"][0]["tb_version"] = "";
		$rest_api["routes"][0]["methods"][] = "GET";
		$rest_api["routes"][0]["args"]["id"] = array("required"=>"false","description"=>"Selecting `order` based `id`");
		$rest_api["routes"][0]["args"]["date"] = array("required"=>"false","description"=>"Selecting `order` based `date`");
		$rest_api["routes"][0]["args"]["service"] = array("required"=>"false","description"=>"Selecting `order` based `service`");
		$rest_api["routes"][0]["args"]["uname"] = array("required"=>"false","description"=>"Selecting `order` based `uname`");
		$rest_api["routes"][0]["args"]["status"] = array("required"=>"false","description"=>"Selecting `order` based `status`");
		$rest_api["routes"][0]["args"]["note"] = array("required"=>"false","description"=>"Selecting `order` based `note`");
		$rest_api["routes"][0]["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `id`, `date`, `service`, `uname`, `status`, `note`");
		$rest_api["routes"][0]["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");
		$rest_api["routes"][0]["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");
		$rest_api["routes"][0]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=order";
		$rest_api["routes"][1]["namespace"] = "user";
		$rest_api["routes"][1]["tb_version"] = "";
		$rest_api["routes"][1]["methods"][] = "GET";
		$rest_api["routes"][1]["args"]["id"] = array("required"=>"false","description"=>"Selecting `user` based `id`");
		$rest_api["routes"][1]["args"]["fullname"] = array("required"=>"false","description"=>"Selecting `user` based `fullname`");
		$rest_api["routes"][1]["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `id`, `fullname`");
		$rest_api["routes"][1]["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");
		$rest_api["routes"][1]["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");
		$rest_api["routes"][1]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=user";
		$rest_api["routes"][2]["namespace"] = "me";
		$rest_api["routes"][2]["methods"][] = "GET";
		$rest_api["routes"][2]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=me";
		$rest_api["routes"][3]["namespace"] = "auth";
		$rest_api["routes"][3]["methods"][] = "GET";
		$rest_api["routes"][3]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=auth";
		$rest_api["routes"][4]["namespace"] = "submit/me";
		$rest_api["routes"][4]["methods"][] = "POST";
		$rest_api["routes"][4]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=me";
		$rest_api["routes"][5]["namespace"] = "submit/order";
		$rest_api["routes"][5]["tb_version"] = "";
		$rest_api["routes"][5]["methods"][] = "POST";
		$rest_api["routes"][5]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=order";
		$rest_api["routes"][5]["args"]["date"] = array("required"=>"true","description"=>"Insert data to field `Date` in table `order`");
		$rest_api["routes"][5]["args"]["service"] = array("required"=>"true","description"=>"Insert data to field `Service` in table `order`");
		$rest_api["routes"][5]["args"]["note"] = array("required"=>"true","description"=>"Insert data to field `Note` in table `order`");
		$rest_api["routes"][6]["namespace"] = "submit/user";
		$rest_api["routes"][6]["tb_version"] = "";
		$rest_api["routes"][6]["methods"][] = "POST";
		$rest_api["routes"][6]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=user";
		$rest_api["routes"][6]["args"]["fullname"] = array("required"=>"true","description"=>"Insert data to field `fullname` in table `user`");
		$rest_api["routes"][6]["args"]["uname"] = array("required"=>"true","description"=>"Insert data to field `uname` in table `user`");
		$rest_api["routes"][6]["args"]["pwd"] = array("required"=>"true","description"=>"Insert data to field `pwd` in table `user`");
		$rest_api["routes"][6]["args"]["address"] = array("required"=>"true","description"=>"Insert data to field `address` in table `user`");
		$rest_api["routes"][6]["args"]["type_ic"] = array("required"=>"true","description"=>"Insert data to field `type_ic` in table `user`");
		$rest_api["routes"][6]["args"]["no_ic"] = array("required"=>"true","description"=>"Insert data to field `no_ic` in table `user`");
		$rest_api["routes"][6]["args"]["phone"] = array("required"=>"true","description"=>"Insert data to field `phone` in table `user`");
		$rest_api["routes"][6]["args"]["email"] = array("required"=>"true","description"=>"Insert data to field `email` in table `user`");
		break;
	// TODO: -+- submit

	case "submit":
		$rest_api=array();

		$rest_api["methods"][0] = "POST";
		$rest_api["methods"][1] = "GET";
		switch($_GET["form"]){
			// TODO: -+----+- order
			case "order":


				$rest_api["auth"]["basic"] = true;
				// TODO: -+----+-----+- Auth User
				$is_user = false;
				if(isset($_SERVER["PHP_AUTH_USER"])){
					$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
					$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
					$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
					if($result = $mysql->query($auth_sql)){
						$current_user = $result->fetch_array();
						if(isset($current_user["uname"])){
							$is_user = true;
						}
					}
					if($is_user == false){
						$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
						break;
					}
				}else{
					$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
					break;
				}

				$rest_api["args"]["date"] = array("required"=>"true","description"=>"Receiving data from the input `Date`");
				$rest_api["args"]["service"] = array("required"=>"true","description"=>"Receiving data from the input `Service`");
				$rest_api["args"]["note"] = array("required"=>"true","description"=>"Receiving data from the input `Note`");
				if(!isset($_POST["date"])){
					$_POST["date"]="";
				}
				if(!isset($_POST["service"])){
					$_POST["service"]="";
				}
				if(!isset($_POST["note"])){
					$_POST["note"]="";
				}
				$rest_api["message"] = "Please! complete the form provided.";
				$rest_api["title"] = "Notice!";
				if(($_POST["date"] != "") || ($_POST["service"] != "") || ($_POST["note"] != "")){
					// avoid undefined
					$input["date"] = "";
					$input["service"] = "";
					$input["note"] = "";
					// variable post
					if(isset($_POST["date"])){
						$input["date"] = $mysql->escape_string($_POST["date"]);
					}

					if(isset($_POST["service"])){
						$input["service"] = $mysql->escape_string($_POST["service"]);
					}

					if(isset($_POST["note"])){
						$input["note"] = $mysql->escape_string($_POST["note"]);
					}
				// TODO: -+----+-----+- Insert by User 

					$input["uname"] = $php_auth_user ;
					$sql_query = "INSERT INTO `order` (`uname`,`date`,`service`,`note`) VALUES ('".$input["uname"]."','".$input["date"]."','".$input["service"]."','".$input["note"]."' )";
					if($query = $mysql->query($sql_query)){
						$rest_api["message"] = "Your request has been sent!";
						$rest_api["title"] = "Successfully";
					}else{
						$rest_api["message"] = "Form input and SQL Column do not match.";
						$rest_api["title"] = "Fatal Error!";
					}
				}else{
					$rest_api["message"] = "Please! complete the form provided.";
					$rest_api["title"] = "Notice!";
				}

				break;

			// TODO: -+----+- user
			case "user":


				$rest_api["auth"]["basic"] = false;

				$rest_api["args"]["fullname"] = array("required"=>"true","description"=>"Receiving data from the input `fullname`");
				$rest_api["args"]["uname"] = array("required"=>"true","description"=>"Receiving data from the input `uname`");
				$rest_api["args"]["pwd"] = array("required"=>"true","description"=>"Receiving data from the input `pwd`");
				$rest_api["args"]["address"] = array("required"=>"true","description"=>"Receiving data from the input `address`");
				$rest_api["args"]["type_ic"] = array("required"=>"true","description"=>"Receiving data from the input `type_ic`");
				$rest_api["args"]["no_ic"] = array("required"=>"true","description"=>"Receiving data from the input `no_ic`");
				$rest_api["args"]["phone"] = array("required"=>"true","description"=>"Receiving data from the input `phone`");
				$rest_api["args"]["email"] = array("required"=>"true","description"=>"Receiving data from the input `email`");
				if(!isset($_POST["fullname"])){
					$_POST["fullname"]="";
				}
				if(!isset($_POST["uname"])){
					$_POST["uname"]="";
				}
				if(!isset($_POST["pwd"])){
					$_POST["pwd"]="";
				}
				if(!isset($_POST["address"])){
					$_POST["address"]="";
				}
				if(!isset($_POST["type_ic"])){
					$_POST["type_ic"]="";
				}
				if(!isset($_POST["no_ic"])){
					$_POST["no_ic"]="";
				}
				if(!isset($_POST["phone"])){
					$_POST["phone"]="";
				}
				if(!isset($_POST["email"])){
					$_POST["email"]="";
				}
				$rest_api["message"] = "Please! complete the form provided.";
				$rest_api["title"] = "Notice!";
				if(($_POST["fullname"] != "") || ($_POST["uname"] != "") || ($_POST["pwd"] != "") || ($_POST["address"] != "") || ($_POST["type_ic"] != "") || ($_POST["no_ic"] != "") || ($_POST["phone"] != "") || ($_POST["email"] != "")){
					// avoid undefined
					$input["fullname"] = "";
					$input["uname"] = "";
					$input["pwd"] = "";
					$input["address"] = "";
					$input["type_ic"] = "";
					$input["no_ic"] = "";
					$input["phone"] = "";
					$input["email"] = "";
					// variable post
					if(isset($_POST["fullname"])){
						$input["fullname"] = $mysql->escape_string($_POST["fullname"]);
					}

					if(isset($_POST["uname"])){
						$input["uname"] = $mysql->escape_string($_POST["uname"]);
					}

					if(isset($_POST["pwd"])){
						$input["pwd"] = $mysql->escape_string($_POST["pwd"]);
					}

					if(isset($_POST["address"])){
						$input["address"] = $mysql->escape_string($_POST["address"]);
					}

					if(isset($_POST["type_ic"])){
						$input["type_ic"] = $mysql->escape_string($_POST["type_ic"]);
					}

					if(isset($_POST["no_ic"])){
						$input["no_ic"] = $mysql->escape_string($_POST["no_ic"]);
					}

					if(isset($_POST["phone"])){
						$input["phone"] = $mysql->escape_string($_POST["phone"]);
					}

					if(isset($_POST["email"])){
						$input["email"] = $mysql->escape_string($_POST["email"]);
					}

					$sql_query = "INSERT INTO `user` (`fullname`,`uname`,`pwd`,`address`,`type_ic`,`no_ic`,`phone`,`email`) VALUES ('".$input["fullname"]."','".$input["uname"]."','".$input["pwd"]."','".$input["address"]."','".$input["type_ic"]."','".$input["no_ic"]."','".$input["phone"]."','".$input["email"]."' )";
					if($query = $mysql->query($sql_query)){
						$rest_api["message"] = "You have successfully signed up, please login!";
						$rest_api["title"] = "Successfully";
					}else{
						$rest_api["message"] = "Form input and SQL Column do not match.";
						$rest_api["title"] = "Fatal Error!";
					}
				}else{
					$rest_api["message"] = "Please! complete the form provided.";
					$rest_api["title"] = "Notice!";
				}

				break;
			// TODO: -+- Submit : Me
			case "me":
				// TODO: -+----+- Auth User
				$is_user = false;
				if(isset($_SERVER["PHP_AUTH_USER"])){
					$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);
					$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);
					$auth_sql = "SELECT * FROM `user` WHERE `uname` = '$php_auth_user' AND `pwd` = '$php_auth_pw'";
					if($result = $mysql->query($auth_sql)){
						$current_user = $result->fetch_array();
						if(isset($current_user["uname"])){
							$is_user = true;

							$input["fullname"] = null;
							if(isset($_POST["fullname"])){
								$input["fullname"] = $mysql->escape_string($_POST["fullname"]);
							}

							$input["address"] = null;
							if(isset($_POST["address"])){
								$input["address"] = $mysql->escape_string($_POST["address"]);
							}

							$input["type_ic"] = null;
							if(isset($_POST["type_ic"])){
								$input["type_ic"] = $mysql->escape_string($_POST["type_ic"]);
							}

							$input["no_ic"] = null;
							if(isset($_POST["no_ic"])){
								$input["no_ic"] = $mysql->escape_string($_POST["no_ic"]);
							}

							$input["phone"] = null;
							if(isset($_POST["phone"])){
								$input["phone"] = $mysql->escape_string($_POST["phone"]);
							}

							$input["email"] = null;
							if(isset($_POST["email"])){
								$input["email"] = $mysql->escape_string($_POST["email"]);
							}
							$update_me_sql = "UPDATE `user` SET `fullname` = '".$input["fullname"]."', `address` = '".$input["address"]."', `type_ic` = '".$input["type_ic"]."', `no_ic` = '".$input["no_ic"]."', `phone` = '".$input["phone"]."', `email` = '".$input["email"]."' WHERE `uname`='$php_auth_user'";
							if($query = $mysql->query($update_me_sql)){
								$rest_api=array("data"=>array("status"=>200,"title"=>"Successfully"),"title"=>"Successfully","message"=>"You have successfully updated your data.");
							}else{
								$rest_api=array("data"=>array("status"=>200,"title"=>"Error"),"title"=>"Error","message"=>"You have fail updated your data.");
							}
						}
					}
					if($is_user == false){
						$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
						break;
					}
				}else{
					$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");
					break;
				}

				break;

		}


	break;

}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,X-Authorization');
if (!isset($_GET["callback"])){
	header('Content-type: application/json');
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);
	}else{
		echo json_encode($rest_api);
	}

}else{
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api,JSON_UNESCAPED_UNICODE). ");" ;
	}else{
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api) . ");" ;
	}

}