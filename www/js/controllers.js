angular.module("laundry_service.controllers", [])



// TODO: indexCtrl --|-- 
.controller("indexCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	// TODO: indexCtrl --|-- $rootScope.exitApp
	$rootScope.exitApp = function(){
		var confirmPopup = $ionicPopup.confirm({
			title: "Confirm Exit",
			template: "Are you sure you want to exit?"
		});
		confirmPopup.then(function (close){
			if(close){
				ionic.Platform.exitApp();
			}
			$rootScope.closeMenuPopover();
		});
	};
	
	// TODO: indexCtrl --|-- $rootScope.changeLanguage
	$rootScope.changeLanguage = function(langKey){
		if(typeof langKey !== null){
			$translate.use(langKey);
			tmhDynamicLocale.set(langKey);
			try {
				$rootScope.language_option = langKey;
				localforage.setItem("language_option",langKey);
			}catch(e){
				localforage.setItem("language_option","en-us");
			}
		}
	};
	
	// TODO: indexCtrl --|-- $rootScope.showLanguageDialog
	var modal_language = "";
	modal_language += "<ion-modal-view>";
	modal_language += "<ion-header-bar class=\"bar bar-header bar-dark\">";
	modal_language += "<h1 class=\"title\">{{ 'Language' | translate }}</h1>";
	modal_language += "</ion-header-bar>";
	modal_language += "<ion-content class=\"padding\">";
	modal_language += "<div class=\"list\">";
	modal_language += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"language_option\" ng-value=\"'en-us'\" ng-click=\"tryChangeLanguage('en-us')\">English - US</ion-radio>";
	modal_language += "<button class=\"button button-full button-dark\" ng-click=\"closeLanguageDialog()\">{{ 'Close' | translate }}</button>";
	modal_language += "</div>";
	modal_language += "</ion-content>";
	modal_language += "</ion-modal-view>";
	
	$rootScope.languageDialog = $ionicModal.fromTemplate(modal_language,{
		scope: $scope,
		animation: "slide-in-up"
	});
	
	$rootScope.showLanguageDialog = function(){
		$rootScope.languageDialog.show();
		localforage.getItem("language_option", function(err, value){
			$rootScope.language_option = value;
		}).then(function(value){
			$rootScope.language_option = value;
		}).catch(function (err){
			$rootScope.language_option = "en-us";
		})
	};
	
	$rootScope.closeLanguageDialog = function(){
		$rootScope.languageDialog.hide();
		$rootScope.closeMenuPopover();
	};
	
	$rootScope.tryChangeLanguage = function(langKey){
		$rootScope.changeLanguage(langKey);
	};
	
	localforage.getItem("language_option", function(err, value){
		if(value === null){
			localforage.setItem("language_option","en-us");
		}else{
			$rootScope.changeLanguage(value);
		}
	}).then(function(value){
		if(value === null){
			localforage.setItem("language_option","en-us");
		}else{
			$rootScope.changeLanguage(value);
		}
	}).catch(function (err){
		localforage.setItem("language_option","en-us");
	})
	// TODO: indexCtrl --|-- $rootScope.changeFontSize
	$rootScope.changeFontSize = function(fontSize){
		if(typeof fontSize !== null){
			try {
				$rootScope.fontsize_option = $rootScope.fontsize = fontSize;
				localforage.setItem("fontsize_option",fontSize);
			}catch(e){
				localforage.setItem("fontsize_option","normal");
			}
		}
	};
	
	// TODO: indexCtrl --|-- $rootScope.showFontSizeDialog
	var modal_fontsize = "";
	modal_fontsize += "<ion-modal-view>";
	modal_fontsize += "<ion-header-bar class=\"bar bar-header bar-dark\">";
	modal_fontsize += "<h1 class=\"title\">{{ 'Font Size' | translate }}</h1>";
	modal_fontsize += "</ion-header-bar>";
	modal_fontsize += "<ion-content class=\"padding\">";
	modal_fontsize += "<div class=\"list\">";
	modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"'small'\" ng-click=\"tryChangeFontSize('small');\">{{ 'Small' | translate }}</ion-radio>";
	modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"'normal'\" ng-click=\"tryChangeFontSize('normal');\">{{ 'Normal' | translate }}</ion-radio>";
	modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"'large'\" ng-click=\"tryChangeFontSize('large');\">{{ 'Large' | translate }}</ion-radio>";
	modal_fontsize += "<button class=\"button button-full button-dark\" ng-click=\"closeFontSizeDialog()\">{{ 'Close' | translate }}</button>";
	modal_fontsize += "</div>";
	modal_fontsize += "</ion-content>";
	modal_fontsize += "</ion-modal-view>";
	
	$rootScope.fontSizeDialog = $ionicModal.fromTemplate(modal_fontsize,{
		scope: $scope,
		animation: "slide-in-up"
	});
	
	$rootScope.showFontSizeDialog = function(){
		$rootScope.fontSizeDialog.show();
		localforage.getItem("fontsize_option", function(err, value){
			$rootScope.fontsize_option = $rootScope.fontsize = value;
		}).then(function(value){
			$rootScope.fontsize_option = $rootScope.fontsize = value;
		}).catch(function (err){
			$rootScope.fontsize_option = $rootScope.fontsize = "normal";
		})
	};
	
	$rootScope.closeFontSizeDialog = function(){
		$rootScope.fontSizeDialog.hide();
		$rootScope.closeMenuPopover();
	};
	
	localforage.getItem("fontsize_option", function(err, value){
		if(value === null){
			localforage.setItem("fontsize_option","normal");
		}else{
			$rootScope.changeFontSize(value);
		}
	}).then(function(value){
		if(value === null){
			localforage.setItem("fontsize_option","normal");
		}else{
			$rootScope.changeFontSize(value);
		}
	}).catch(function (err){
		console.log(err);
		localforage.setItem("fontsize_option","normal");
	})
	
	
	$rootScope.tryChangeFontSize = function(val){
		$rootScope.changeFontSize(val);
	};
	
	// TODO: indexCtrl --|-- $rootScope.clearCacheApp
	$rootScope.clearCacheApp = function(){
		var confirmPopup = $ionicPopup.confirm({
			title: "Confirm",
			template: "Are you sure you want to clear cache?"
		});
		confirmPopup.then(function (close){
			if(close){
				localforage.keys().then(function(keys) {
					for(var e = 0; e < keys.length ; e++) {
						localforage.setItem(keys[e],[]);
					}
					$state.go("laundry_service.profile");
				}).catch(function(err) {
					$state.go("laundry_service.profile");
				});
			}
			$rootScope.closeMenuPopover();
		});
	};
	$rootScope.last_edit = "-" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: indexCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: indexCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: indexCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: indexCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: indexCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: indexCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: indexCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `index` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: side_menusCtrl --|-- 
.controller("side_menusCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "-" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: side_menusCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: side_menusCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: side_menusCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: side_menusCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: side_menusCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: side_menusCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 
	
	var popover_template = "";
	popover_template += "<ion-popover-view class=\"fit\">";
	popover_template += "	<ion-content>";
	popover_template += "		<ion-list>";
	popover_template += "			<a  class=\"item dark-ink\" ng-href=\"#/laundry_service/about_us\" ng-click=\"popover.hide()\">";
	popover_template += "			{{ 'About Us' | translate }}";
	popover_template += "			</a>";
	popover_template += "		</ion-list>";
	popover_template += "	</ion-content>";
	popover_template += "</ion-popover-view>";
	
	
	$scope.popover = $ionicPopover.fromTemplate(popover_template,{
		scope: $scope
	});
	
	$scope.closePopover = function(){
		$scope.popover.hide();
	};
	
	$rootScope.closeMenuPopover = function(){
		$scope.popover.hide();
	};
	
	$scope.$on("$destroy", function(){
		$scope.popover.remove();
	});

	// TODO: side_menusCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `side_menus` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: about_usCtrl --|-- 
.controller("about_usCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: about_usCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: about_usCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: about_usCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: about_usCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: about_usCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: about_usCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: about_usCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
$ionicConfig.backButton.text("");			
		} catch(e){
			console.log("%cerror: %cPage: `about_us` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: dashboardCtrl --|-- 
.controller("dashboardCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: dashboardCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: dashboardCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: dashboardCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: dashboardCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: dashboardCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: dashboardCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: dashboardCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `dashboard` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: faqsCtrl --|-- 
.controller("faqsCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: faqsCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: faqsCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: faqsCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: faqsCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: faqsCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: faqsCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: faqsCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `faqs` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: form_loginCtrl --|-- 
.controller("form_loginCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "page_builder" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: form_loginCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: form_loginCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: form_loginCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_loginCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_loginCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: form_loginCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	
	// Form Request
	// code 

	// TODO: form_loginCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			

 
$scope.submitUser = function(form){
    
    $ionicLoading.show({
		template: '<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
	});
    
    if( angular.isDefined(form)){
        
		var uname = form.uname || "demo";
		var pwd = form.pwd || "demo";
		var http_value = "Basic " + base64.encode(uname + ":" + pwd);
		$http.defaults.headers.common["X-Authorization"] = http_value;
        $http.get("http://demo.ihsana.net/laundry_service/rest-api.php?json=auth").then(function(resp){
            if(resp.data.data.status == 401){
    			var alertPopup = $ionicPopup.alert({
    				title: resp.data.title,
    				template: resp.data.message,
    			});
            }else{
           	    $ionicHistory.nextViewOptions({
            		disableAnimate: true,
            		disableBack: true
            	});
                $state.go("laundry_service.profile");
            }
        },function(resp){   
            
			$timeout(function() {
				$ionicLoading.hide();
                
        		var alertPopup = $ionicPopup.alert({
        			title: "Ops, error!",
        			template: "Problem with crossdomain or Invalid JSON URL.",
        		});
                            
			}, 500); 
                        
            
        }).finally(function(){   
            
			$timeout(function() {
				$ionicLoading.hide();
			}, 500);          
              
        });    
    }
    
	$timeout(function() {
	   $ionicLoading.hide();
	}, 1000);  
    
}    
			
		} catch(e){
			console.log("%cerror: %cPage: `form_login` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: form_orderCtrl --|-- 
.controller("form_orderCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "page_builder" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: form_orderCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: form_orderCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: form_orderCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_orderCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_orderCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: form_orderCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	
	// Form Request
	//order
	$scope.form_order= {};
	// TODO: form_orderCtrl --|-- $scope.submitOrder
	$scope.submitOrder = function(){
		// animation loading 
		$ionicLoading.show();
	
		var $messages, $title = null;
		$http({
				method:"POST",
				url: "http://demo.ihsana.net/laundry_service/rest-api.php?json=submit&form=order",
				data: $httpParamSerializer($scope.form_order),  // pass in data as strings
				headers: {"Content-Type":"application/x-www-form-urlencoded"}  // set the headers so angular passing info as form data (not request payload)
			})
			.then(function(response) {
				$messages = response.data.message;
				$title = response.data.title;
			},function(response){
				$messages = response.statusText;
				$title = response.status;
			}).finally(function(){
				// event done, hidden animation loading
				$timeout(function() {
					$ionicLoading.hide();
					if($messages !== null){
						// message
					var alertPopup = $ionicPopup.alert({
						title: $title,
						template: $messages,
					});
						// clear input
						$scope.form_order.service = "";
						$scope.form_order.note = "";
					}
			}, 500);
		});
	};
	// code 

	// TODO: form_orderCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `form_order` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: form_userCtrl --|-- 
.controller("form_userCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "page_builder" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: form_userCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: form_userCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: form_userCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_userCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: form_userCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: form_userCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	
	// Form Request
	//user
	$scope.form_user= {};
	// TODO: form_userCtrl --|-- $scope.submitUser
	$scope.submitUser = function(){
		// animation loading 
		$ionicLoading.show();
	
		var $messages, $title = null;
		$http({
				method:"POST",
				url: "http://demo.ihsana.net/laundry_service/rest-api.php?json=submit&form=user",
				data: $httpParamSerializer($scope.form_user),  // pass in data as strings
				headers: {"Content-Type":"application/x-www-form-urlencoded"}  // set the headers so angular passing info as form data (not request payload)
			})
			.then(function(response) {
				$messages = response.data.message;
				$title = response.data.title;
			},function(response){
				$messages = response.statusText;
				$title = response.status;
			}).finally(function(){
				// event done, hidden animation loading
				$timeout(function() {
					$ionicLoading.hide();
					if($messages !== null){
						// message
					var alertPopup = $ionicPopup.alert({
						title: $title,
						template: $messages,
					});
						// clear input
						$scope.form_user.fullname = "";
						$scope.form_user.uname = "";
						$scope.form_user.pwd = "";
						$scope.form_user.address = "";
						$scope.form_user.type_ic = "";
						$scope.form_user.no_ic = "";
						$scope.form_user.phone = "";
						$scope.form_user.email = "";
					}
			}, 500);
		});
	};
	// code 

	// TODO: form_userCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
$ionicConfig.backButton.text("");			
		} catch(e){
			console.log("%cerror: %cPage: `form_user` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: menu_1Ctrl --|-- 
.controller("menu_1Ctrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: menu_1Ctrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: menu_1Ctrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: menu_1Ctrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: menu_1Ctrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: menu_1Ctrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: menu_1Ctrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: menu_1Ctrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `menu_1` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: menu_2Ctrl --|-- 
.controller("menu_2Ctrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: menu_2Ctrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: menu_2Ctrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: menu_2Ctrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: menu_2Ctrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: menu_2Ctrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: menu_2Ctrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: menu_2Ctrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `menu_2` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: orderCtrl --|-- 
.controller("orderCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "page_builder" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: orderCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: orderCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: orderCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: orderCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: orderCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: orderCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// TODO: orderCtrl --|-- $scope.showAuthentication
	$scope.showAuthentication  = function(){
		var form = {"uname":"demo","pwd":"demo"};
		$scope.form = {};
		var authPopup = $ionicPopup.show({
			template: '<input type="text" ng-model="form.uname" placeholder="Username"><input type="password" placeholder="Password" ng-model="form.pwd">',
			title: "Authorization",
			subTitle: "Please use username and password",
			scope: $scope,
			buttons: [
				{text:"Cancel",onTap: function(e){
					$state.go("laundry_service.profile");
				}},
				{text:"<strong>Save</strong>",type:"button-positive",onTap:function(e){
						return $scope.form;
					}},
			],
		}).then(function(form){
			if( angular.isDefined(form)){
				var uname = form.uname || "demo";
				var pwd = form.pwd || "demo";
				var http_value = "Basic " + base64.encode(uname + ":" + pwd);
				$http.defaults.headers.common["Authorization"] = http_value;
				$scope.doRefresh();
			}
		},function(err){
		},function(msg){
		});
	};
	
	// set default parameter http
	var http_params = {};
	
	// set HTTP Header 
	var http_header = {
		headers: {
		},
		params: http_params
	};
	var targetQuery = ""; //default param
	var raplaceWithQuery = "";
	//fix url History
	targetQuery = "json=order"; //default param
	raplaceWithQuery = "json=order";
	
	
	// TODO: orderCtrl --|-- $scope.splitArray
	$scope.splitArray = function(items,cols,maxItem) {
		var newItems = [];
		if(maxItem == 0){
			maxItem = items.length;
		}
		if(items){
			for (var i=0; i < maxItem; i+=cols) {
				newItems.push(items.slice(i, i+cols));
			}
		}
		return newItems;
	}
	$scope.gmapOptions = {options: { scrollwheel: false }};
	
	var fetch_per_scroll = 1;
	// animation loading 
	$ionicLoading.show();
	
	
	// TODO: orderCtrl --|-- $scope.fetchURL
	$scope.fetchURL = "http://kulamobil.site/panel/rest-api.php?json=order&sort=asc";
	// TODO: orderCtrl --|-- $scope.fetchURLp
	$scope.fetchURLp = "http://kulamobil.site/panel/rest-api.php?json=order&sort=asc&callback=JSON_CALLBACK";
	// TODO: orderCtrl --|-- $scope.hashURL
	$scope.hashURL = md5.createHash( $scope.fetchURL.replace(targetQuery,raplaceWithQuery));
	
	
	$scope.noMoreItemsAvailable = false; //readmore status
	var lastPush = 0;
	var data_orders = [];
	
	localforage.getItem("data_orders_" + $scope.hashURL, function(err, get_orders){
		if(get_orders === null){
			data_orders =[];
		}else{
			data_orders = JSON.parse(get_orders);
			$scope.data_orders =JSON.parse( get_orders);
			$scope.orders = [];
			for(lastPush = 0; lastPush < 100; lastPush++) {
				if (angular.isObject(data_orders[lastPush])){
					$scope.orders.push(data_orders[lastPush]);
				};
			}
			$timeout(function() {
				$ionicLoading.hide();
				controller_by_user();
			},200);
		}
	}).then(function(value){
	}).catch(function (err){
	})
	if(data_orders === null ){
		data_orders =[];
	}
	if(data_orders.length === 0 ){
		$timeout(function() {
			var url_request = $scope.fetchURL.replace(targetQuery,raplaceWithQuery);
			// overwrite HTTP Header 
			http_header = {
				headers: {
				},
				params: http_params
			};
			// TODO: orderCtrl --|-- $http.get
			console.log("%cRetrieving JSON: %c" + url_request,"color:blue;font-size:18px","color:red;font-size:18px");
			$http.get(url_request,http_header).then(function(response) {
				data_orders = response.data;
				console.log("%cSuccessfully","color:blue;font-size:18px");
				console.dir(data_orders);
				$scope.data_orders = response.data;
				// TODO: orderCtrl --|---------- set:localforage
				localforage.setItem("data_orders_" + $scope.hashURL, JSON.stringify(data_orders));
				$scope.orders = [];
				for(lastPush = 0; lastPush < 100; lastPush++) {
					if (angular.isObject(data_orders[lastPush])){
						$scope.orders.push(data_orders[lastPush]);
					};
				}
			},function(response) {
			
				$timeout(function() {
					var url_request = $scope.fetchURLp.replace(targetQuery,raplaceWithQuery);
					// overwrite HTTP Header 
					http_header = {
						headers: {
						},
						params: http_params
					};
					console.log("%cRetrieving again: %c" + url_request,"color:blue;font-size:18px","color:red;font-size:18px");
					// TODO: orderCtrl --|------ $http.jsonp
					$http.jsonp(url_request,http_header).success(function(data){
						data_orders = data;
						$scope.data_orders = data;
						$ionicLoading.hide();
						// TODO: orderCtrl --|---------- set:localforage
						localforage.setItem("data_orders_" + $scope.hashURL,JSON.stringify(data_orders));
						controller_by_user();
						$scope.orders = [];
						for(lastPush = 0; lastPush < 100; lastPush++) {
							if (angular.isObject(data_orders[lastPush])){
								$scope.orders.push(data_orders[lastPush]);
							};
						}
					}).error(function(data){
					if(response.status ===401){
						// TODO: orderCtrl --|------------ error:Unauthorized
						$scope.showAuthentication();
					}else{
						// TODO: orderCtrl --|------------ error:Message
						var data = { statusText:response.statusText, status:response.status };
						var alertPopup = $ionicPopup.alert({
							title: "Network Error" + " (" + data.status + ")",
							template: "An error occurred while collecting data.",
						});
						$timeout(function() {
							alertPopup.close();
						}, 2000);
					}
					});
				}, 200);
		}).finally(function() {
			$scope.$broadcast("scroll.refreshComplete");
			// event done, hidden animation loading
			$timeout(function() {
				$ionicLoading.hide();
				controller_by_user();
				if(angular.isDefined($scope.data_orders.data)){
					if($scope.data_orders.data.status ===401){
						$scope.showAuthentication();
						return false;
					}
				}
			}, 200);
		});
	
		}, 200);
	}
	
	
	// TODO: orderCtrl --|-- $scope.doRefresh
	$scope.doRefresh = function(){
		var url_request = $scope.fetchURL.replace(targetQuery,raplaceWithQuery);
		// retry retrieving data
		// overwrite http_header 
		http_header = {
			headers: {
			},
			params: http_params
		};
		// TODO: orderCtrl --|------ $http.get
		$http.get(url_request,http_header).then(function(response) {
			data_orders = response.data;
			$scope.data_orders = response.data;
			// TODO: orderCtrl --|---------- set:localforage
			localforage.setItem("data_orders_" + $scope.hashURL,JSON.stringify(data_orders));
			$scope.orders = [];
			for(lastPush = 0; lastPush < 100; lastPush++) {
				if (angular.isObject(data_orders[lastPush])){
					$scope.orders.push(data_orders[lastPush]);
				};
			}
		},function(response){
			
		// retrieving data with jsonp
			$timeout(function() {
			var url_request =$scope.fetchURLp.replace(targetQuery,raplaceWithQuery);
				// overwrite http_header 
				http_header = {
					headers: {
					},
					params: http_params
				};
				// TODO: orderCtrl --|---------- $http.jsonp
				$http.jsonp(url_request,http_header).success(function(data){
					data_orders = data;
					$scope.data_orders = data;
					$ionicLoading.hide();
					controller_by_user();
					// TODO: orderCtrl --|---------- set:localforage
					localforage.setItem("data_orders_"+ $scope.hashURL,JSON.stringify(data_orders));
					$scope.orders = [];
					for(lastPush = 0; lastPush < 100; lastPush++) {
						if (angular.isObject(data_orders[lastPush])){
							$scope.orders.push(data_orders[lastPush]);
						};
					}
				}).error(function(resp){
					if(response.status ===401){
					// TODO: orderCtrl --|------------ error:Unauthorized
					$scope.showAuthentication();
					}else{
						// TODO: orderCtrl --|------------ error:Message
						var data = { statusText:response.statusText, status:response.status };
						var alertPopup = $ionicPopup.alert({
							title: "Network Error" + " (" + data.status + ")",
							template: "An error occurred while collecting data.",
						});
					};
				});
			}, 200);
		}).finally(function() {
			$scope.$broadcast("scroll.refreshComplete");
			// event done, hidden animation loading
			$timeout(function() {
				$ionicLoading.hide();
				controller_by_user();
				if(angular.isDefined($scope.data_orders.data)){
					if($scope.data_orders.data.status ===401){
						$scope.showAuthentication();
						return false;
					}
				}
			}, 500);
		});
	
	};
	if (data_orders === null){
		data_orders = [];
	};
	// animation readmore
	var fetchItems = function() {
		for(var z=0;z<fetch_per_scroll;z++){
			if (angular.isObject(data_orders[lastPush])){
				$scope.orders.push(data_orders[lastPush]);
				lastPush++;
			}else{;
				$scope.noMoreItemsAvailable = true;
			}
		}
		$scope.$broadcast("scroll.infiniteScrollComplete");
	};
	
	// event readmore
	$scope.onInfinite = function() {
		$timeout(fetchItems, 500);
	};
	
	// create animation fade slide in right (ionic-material)
	$scope.fireEvent = function(){
		ionicMaterialInk.displayEffect();
	};
	
	// Form Request
	// code 

	// TODO: orderCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `order` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: profileCtrl --|-- 
.controller("profileCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "page_builder" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: profileCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: profileCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: profileCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: profileCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: profileCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: profileCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: profileCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			

    
$scope.current_user = {} ;
$scope.$on("$ionicView.afterEnter", function (){  
    
    $ionicLoading.show({
		template: '<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
	});
    
    $http.get("http://demo.ihsana.net/laundry_service/rest-api.php?json=me").then(function(resp){
        if(resp.data.data.status == 401){
       	    $ionicHistory.nextViewOptions({
        		disableAnimate: true,
        		disableBack: true
        	});
			$state.go("laundry_service.form_login");
        }else{
            $scope.current_user = resp.data.me ;
        }
        
        
        
    },function(resp){   
        
		$timeout(function() {
			$ionicLoading.hide();
		}, 500); 
        
		var alertPopup = $ionicPopup.alert({
			title: "Ops, error!",
			template: "Problem with crossdomain or Invalid URL.",
		});
        
        alertPopup.then(function(res){
              $state.go("laundry_service.form_login");
        });
        
    }).finally(function(){   
		$timeout(function() {
			$ionicLoading.hide();
		}, 500);            
    });      
    
});   

$scope.updateUser = function(){
  		
    // animation loading 
    $ionicLoading.show({
    	template: '<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>'
    });
	
		var $messages, $title = null;
		$http({
				method:"POST",
				url: "http://demo.ihsana.net/laundry_service/rest-api.php?json=submit&form=me",
				data: $httpParamSerializer($scope.current_user),   
				headers: {"Content-Type":"application/x-www-form-urlencoded"}  
			})
			.then(function(response) {
				$messages = response.data.message;
				$title = response.data.title;
			},function(response){
				$messages = response.statusText;
				$title = response.status;
			}).finally(function(){
				// event done, hidden animation loading
				$timeout(function() {
					$ionicLoading.hide();
					if($messages !== null){
						// message
    					var alertPopup = $ionicPopup.alert({
    						title: $title,
    						template: $messages,
    					});
					}
			     }, 500);
		  });
}
 
    			
		} catch(e){
			console.log("%cerror: %cPage: `profile` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})

// TODO: slide_tab_menuCtrl --|-- 
.controller("slide_tab_menuCtrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale){
	
	$rootScope.headerExists = true;
	$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;
	$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;
	$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;
	$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;
	$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;
	$rootScope.last_edit = "menu" ;
	$scope.$on("$ionicView.afterEnter", function (){
		var page_id = $state.current.name ;
		$rootScope.page_id = page_id.replace(".","-") ;
	});
	if($rootScope.headerShrink == true){
		$scope.$on("$ionicView.enter", function(){
			$scope.scrollTop();
		});
	};
	// TODO: slide_tab_menuCtrl --|-- $scope.scrollTop
	$rootScope.scrollTop = function(){
		$timeout(function(){
			$ionicScrollDelegate.$getByHandle("top").scrollTop();
		},100);
	};
	// TODO: slide_tab_menuCtrl --|-- $scope.openURL
	// open external browser 
	$scope.openURL = function($url){
		window.open($url,"_system","location=yes");
	};
	// TODO: slide_tab_menuCtrl --|-- $scope.openAppBrowser
	// open AppBrowser
	$scope.openAppBrowser = function($url){
		var appBrowser = window.open($url,"_blank","hardwareback=Done");
		appBrowser.addEventListener("loadstart",function(){
			appBrowser.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: slide_tab_menuCtrl --|-- $scope.openWebView
	// open WebView
	$scope.openWebView = function($url){
		var appWebview = window.open($url,"_blank","location=no");
		appWebview.addEventListener("loadstart",function(){
			appWebview.insertCSS({
				code: "body{background:#100;color:#100;font-size:1px;}body:after{content:'loading...';color:#fff;font-size:72px;position:absolute;bottom:50%;left:0;right:0; text-align: center; vertical-align: middle;}"
			});
		});
	
		appWebview.addEventListener("loaderror",function(){
			window.location = "retry.html";
		});
	};
	
	
	// TODO: slide_tab_menuCtrl --|-- $scope.toggleGroup
	$scope.toggleGroup = function(group) {
		if ($scope.isGroupShown(group)) {
			$scope.shownGroup = null;
		} else {
			$scope.shownGroup = group;
		}
	};
	
	$scope.isGroupShown = function(group) {
		return $scope.shownGroup === group;
	};
	
	// TODO: slide_tab_menuCtrl --|-- $scope.redirect
	// redirect
	$scope.redirect = function($url){
		$window.location.href = $url;
	};
	
	// Set Motion
	$timeout(function(){
		ionicMaterialMotion.slideUp({
			selector: ".slide-up"
		});
	}, 300);
	// code 

	// TODO: slide_tab_menuCtrl --|-- controller_by_user
	// controller by user 
	function controller_by_user(){
		try {
			
			
		} catch(e){
			console.log("%cerror: %cPage: `slide_tab_menu` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");
			console.dir(e);
		}
	}
	$scope.rating = {};
	$scope.rating.max = 5;
	
	// animation ink (ionic-material)
	ionicMaterialInk.displayEffect();
	controller_by_user();
})
