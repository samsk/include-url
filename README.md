Include URL
===========
Include URL is a [Wordpress](https://wordpress.org) plugin for seamless inclusion of data from remote urls, optionaly passing GET parameters to it.

This way, you can create ie. simple frontend page for some internal service, that will be seamlessly incoporated in your Wordpress site, using its theme and all what wordpress provides.

# Syntax
`
[include-url href="<URL>" params="param1,param2,param3..." timeout="seconds" cache="seconds" allow-file="1"]
`

- ***<URL>*** - http:// or https:// url
- ***href*** - (*required*) specifies url to be fetched
- ***params*** - list of GET parameters, that should be passed to request from request page
- ***timeout*** - request timeout in seconds (default = 10 seconds)
- ***cache*** - cache data for given amount of seconds in wordpress database (default = 0 / disabled)
- ***allow-file*** - allow file:// urls in href parameter (default = 0 / disabled, 1 = prepend file with document root, 2 = use absolute path)
- ***allow-other*** - allow any other protocol supported by cURL
- ***allow-shortcode*** - allow and process wordpress shortcodes in included content

*Note: timeout will be honored only if cURL PHP extension is present !*

# Installation
* Download plugin to *wp-content/plugins* directory
* Activate plugin in admin section
* Use shortcode in pages/posts

# Examples

* Search frontend page for [SOLR](https://lucene.apache.org/solr/)

`
[include-url href="http://localhost:8080/solr/core1/select?wt=xslt&wt=results.xslt" params="q,fq" cache="1800"]
`
This requests data from local SOLR instance, that will return search results formated as HTML (ie. table) via its XSLT handler, optionally passing q (query) and fq (filter) params to it. Data will be cached for 1800 seconds in Wordpress database.


* Read file from document_root

`
[include-url href="file://robots.txt" allow-file="1"]
`

* Read file specified by absolute path

`
[include-url href="file:///var/www/html/robots.txt" allow-file="2"]
`
