include-url
===========
include-url is a [Wordpress](https://wordpress.org) plugin for seamless inclusion of data from remote url, optionaly passing GET parameters to it.

This way, you can 

# Syntax
`
[include-url href="<URL>" params="param1,param2,param3..." timeout="seconds" cache="seconds"]
`

- ***href*** - (*required*) specifies url to be fetched
- ***params*** - list of GET parameters, that should be passed to request from request page
- ***timeout*** - request timeout in seconds (default = 10 seconds)
- ***cache*** - cache data for given amount of seconds in wordpress database (default = 0 / disabled)

*Note: timeout will be honored only if CURL PHP extension is present !*

# Installation
* Download plugin to *wp-content/plugins* directory
* Activate plugin in admin section
* Use shortcode in pages/posts

# Examples

* Search frontend page for [SOLR](https://lucene.apache.org/solr/)

`
[include-url href="http://localhost:8080/solr/core1/select?wt=xslt&wt=results.xslt" params="q,fq" cache="15"]
`

This requests data from local SOLR instance, that will return search results formated as HTML (ie. table) via its XSLT handler, optionally passing q (query) and fq (filter) params to it. Data will be cached for 15 seconds in Wordpress database.
