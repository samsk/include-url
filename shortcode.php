<?php
/*
 * Copyright 2016-2020 Samuel Behan <samuel(.)behan(at)dob(.)sk>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * ( at your option ) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

defined('ABSPATH') or exit();

function include_url_shortcode($atts, $msg = null) {
	$attrs = shortcode_atts(array(
			'href' => null,
			'param' => null,
			'params' => null,
			'timeout' => 10,
			'cache' => 0,
			'allow-file' => 0,
			'allow-other' => 0,
			'allow-shortcode' => 0,
		), $atts, 'include-url');

	// href=url
	$href = $attrs['href'];
	// params=param1,param2,param3
	$params = isset($attrs['params']) ? $attrs['params'] : $attrs['param'];
	// timeout=seconds
	$timeout = $attrs['timeout'];
	// cache=seconds
	$cache = $attrs['cache'];
	// allow-file=int
	$allow_file = $attrs['allow-file'];
	$is_file = (($allow_file != 0) && preg_match('/^file:\//', $href)) ? 1 : 0;
	// allow-other=int
	$allow_other = $attrs['allow-other'];
	// allow-shortcode=int
	$allow_shortcode = $attrs['allow-shortcode'];

	if (!isset($href))
		return '<b>include-url: required href parameter</b>';
	if (($allow_other == 0) && (!preg_match('/^https?:\//', $href) && (($allow_file == 0) || !$is_file)))
		return '<b>include-url: only http://, https:// and file:// (if allow-file="1") are urls allowed in href parameter (' . $href . ')</b>';

	// allow-file=1 - prepend document root
	if ($is_file && $allow_file == 1) {
		$href = preg_replace('/^file:\/\//', 'file://' . $_SERVER['DOCUMENT_ROOT'] . '/', $href);
	}

	$cache_key = 'include-url';
	$args = array();
	$params = explode(',', $params);
	foreach ($params as $key) {
		$cache_key .= ':';

		if (!isset($_GET[$key]))
			continue;

		$args[$key] = urlencode($_GET[$key]);
		$cache_key .= $_GET[$key];
	}

	// build url
	$url = add_query_arg($args, $href);

	// fetch content
	$content = '';
	if ($cache > 0)
		$content = get_transient($cache_key);
	if (!$content) {
		if (function_exists('curl_version')) {
			$c = curl_init();
	 		curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
			//curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
			//curl_setopt($c, CURLOPT_MAXREDIRS, 10);
			//$follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
			//if ($follow_allowed)
			//	curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($c, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($c, CURLOPT_REFERER, $url);
			curl_setopt($c, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($c, CURLOPT_AUTOREFERER, true);
			//curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');

			$content = curl_exec($c);
			$status = curl_getinfo($c);

			curl_close($c);

			if ($status['http_code'] != 200 && !$is_file)
				$content = $msg;
		} else {
			$context = stream_context_create(array(
				'http' => array('timeout' => $timeout)
			));

			$content = file_get_contents($url, false, $context);
			if (!$content)
				$content = $msg;
		}

		if ($allow_shortcode)
			$content = do_shortcode($content);

		if ($cache > 0 && $content)
			set_transient($cache_key, $content, $cache);
	}

	return $content;
}
add_shortcode('include-url', 'include_url_shortcode');

?>
