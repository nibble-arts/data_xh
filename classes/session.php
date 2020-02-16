<?php

/* the session class provide access to the
 * http parameters and the cookies.
 * @version: 1.2
 * @author: Thomas H Winkler
 * @copyright: 2018-2020
 */

/*
 * V1.2 uri function added
 */

namespace data;

class Session {
	
	private static $get;
	private static $post;
	private static $params;		// http values
	private static $cookies;	// cookie values
	private static $session;	// session values

	private static $page;

	static $adm;
	static $edit;

	
	// load session
	public static function load () {

		global $adm, $edit;
		
		self::start();

		self::$get = $_GET;
		self::$post = $_POST;
		self::$params = $_REQUEST;
		self::$cookies = $_COOKIE;
		self::$session = $_SESSION;

		self::$adm = $adm;
		self::$edit = $edit;

		$server = Path::create([$_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME']]);

		self::$page['root'] = pathinfo($server, PATHINFO_DIRNAME);
		self::$page['script'] = pathinfo($server, PATHINFO_BASENAME);
	}
	
	
	// get session value
	// http before session before cookie
	// public static function get ($name) {

	// 	// get http param
	// 	if (!$val = self::param($name)) {
		
	// 		if (!$val = self::session($name)) {

	// 			// not present, try cookie
	// 			$val = self::cookie($name);
	// 		}
	// 	}

	// 	return $val;
	// }
	

	// set session and cookie value
	public static function set ($key, $value) {

		self::set_session($key, $value);
		self::set_cookie($key, $value);
	}


	// set session value
	public static function set_session($key, $value) {

		$_SESSION[$key] = $value;
	}


	// set session value
	public static function set_cookie($key, $value) {

		setcookie($key, $value);
	}


	// set parameter
	public static function set_param($key, $value) {
		self::$params[$key] = $value;
		unset (self::$get[$key]);
		unset (self::$post[$key]);
	}


	// remove sesseion/cookie value
	public static function remove ($key) {

		if (isset($_SESSION[$key])) {
			unset ($_SESSION[$key]);
			self::set_cookie($key, "");
		}

		self::load();
	}


	// get GET parameter
	public static function get($name) {
		
		if (isset(self::$get[$name])) {
			return self::$get[$name];
		}
		else {
			return false;
		}
	}
	
	
	// get POST parameter
	public static function post($name) {
		
		if (isset(self::$post[$name])) {
			return self::$post[$name];
		}
		else {
			return false;
		}
	}
	

	// remove POST
	public static function remove_http($filter = false) {

		self::$get = false;
		self::$post = false;
		self::$params = false;
	}
	

	// get GET and POSt parameter
	public static function param($name) {
		
		if (isset(self::$params[$name])) {
			return self::$params[$name];
		}
		else {
			return false;
		}
	}


	// get parameter keys
	public static function get_param_keys() {
		return array_keys(self::$params);
	}


	// get cookie
	public static function cookie($name) {
		
		if (isset(self::$cookies[$name])) {
			return self::$cookies[$name];
		}
		else {
			return false;
		}
	}
	

	// get seesion
	public static function session($name) {

		if (isset(self::$session[$name])) {
			return self::$session[$name];
		}
		else {
			return false;
		}
	}


	// get page root uri
	public static function uri($param) {

		if (isset(self::$page[$param])) {
			return self::$page[$param];
		}

		return false;
	}


	// start session
	private static function start () {
		// start session
		if (preg_match("!Googlebot!i",$_SERVER['HTTP_USER_AGENT']));
		else if (preg_match("!MSNbot!i",$_SERVER['HTTP_USER_AGENT']));
		else if (preg_match("!slurp!i",$_SERVER['HTTP_USER_AGENT']));
		elseif (function_exists('XH_startSession')) {
		    XH_startSession();
		} elseif (!session_id()) {
		    session_start();
		}
	}


	public static function debug() {

		$o = "http: " . print_r(self::$params, true) . "<br>";
		$o .= "session: " . print_r(self::$session, true) . "<br>";
		$o .= "cookies: " . print_r(self::$cookies, true);

		return $o;
	}
}