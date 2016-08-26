=== Include URL ===
Tags: include, url
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 0.2.1
Contributors: SamSK
Donate link: https://devel.dob.sk/include-url
License: GPLv3 or later

Include URL allows to include any URL in posts or pages.

== Description ==

= Features  =

Include URL is a Wordpress plugin for seamless inclusion of data from remote urls, optionaly passing GET parameters to it..

* Get remote url content
* Pass given GET params to url
* Cache fetched content localy in Wordpress DB

= Usage =

  `[include-url href="<URL>" params="param1,param2,param3..." timeout="seconds" cache="seconds"]`

* *href* - url starting with http:// or https:// (required)
* *params* - list of comma separated GET parameters, that should be passed to include url
* *timeout* - request timeout in seconds (honored only if CURL PHP extension is installed, default = 10 seconds)
* *cache* - cache request data localy in wordpress database (default = 0 seconds / disabled)

= Examples =

* Search frontend page for [SOLR](https://lucene.apache.org/solr/)

`
[include-url href="http://localhost:8080/solr/core1/select?wt=xslt&wt=results.xslt" params="q,fq" cache="15"]
`

This requests data from local SOLR instance, that will return search results formated as HTML (ie. table) via its XSLT handler, optionally passing q (query) and fq (filter) params to it. Data will be cached for 15 seconds in Wordpress database.

== Installation ==

1. Make sure you are using WordPress 4.0 or later and that your server is running PHP 5.2.4 or later (same requirement as WordPress itself)
1. Install and activate the plugin as usual from the 'Plugins' menu in WordPress.
1. Use shortcode in page or post

== Changelog ==

= 0.2.1 (2016-08-26) =

* Restrict urls to http:// and https://

= 0.2.0 (2016-08-25) =

* Initial release
