<?php
ini_set('max_execution_time', 2400); //in seconds
ini_set('display_errors', 'On');
//ob_start("ob_gzhandler");
error_reporting(E_ALL ^ E_DEPRECATED);
// start the session
session_start();

// database connection config
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'db_luvimar_cebu';

$con  = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// setting up the web root and server root for this website application
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = '/luvimar_cebu/';

$srvRoot  = str_replace('global-library/config.php', '', $thisFile);
$webRoot  = '/luvimar_cebu/';

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);


// do we need to limit the image width?  setting this value to 'true' is recommended
define('LIMIT_IMAGE_WIDTH',     true);

// do we need to limit the employee image width?  setting this value to 'true' is recommended
define('LIMIT_EMPLOYEE_WIDTH',     true);

// all file image width must not exceed 300 pixels
define('MAX_IMAGE_WIDTH', 300);

// all employee image width must not exceed 300 pixels
define('MAX_EMPLOYEE_WIDTH', 300);

// all category image width must not 
// exceed 75 pixels
define('MAX_CATEGORY_IMAGE_WIDTH', 75);

// the width for writer thumbnail
define('THUMBNAIL_WIDTH',         75);

/*if (!get_magic_quotes_gpc()) {
	if (isset($_POST)) {
		foreach ($_POST as $key => $value) {
			$_POST[$key] =  trim(addslashes($value));
		}
	}

	if (isset($_GET)) {
		foreach ($_GET as $key => $value) {
			$_GET[$key] = trim(addslashes($value));
		}
	}
}*/

/*
    since all page will require a database access and the common library is also used by all
    it's logical to load these library here.
*/
require_once 'database.php';
require_once 'common.php';
