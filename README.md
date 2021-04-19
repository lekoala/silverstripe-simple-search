# SilverStripe Simple Search module

[![Build Status](https://travis-ci.com/lekoala/silverstripe-simple-search.svg?branch=master)](https://travis-ci.com/lekoala/silverstripe-simple-search/)
[![scrutinizer](https://scrutinizer-ci.com/g/lekoala/silverstripe-simple-search/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lekoala/silverstripe-simple-search/)
[![Code coverage](https://codecov.io/gh/lekoala/silverstripe-simple-search/branch/master/graph/badge.svg)](https://codecov.io/gh/lekoala/silverstripe-simple-search)

## Intro

This module allows to implement a simple search engine for a website.

All searches are directed to a single endpoint: /search/ which is registered in the SiteMap if you use `silverstripe/googlesitemaps`.

You can call `$SimpleSearchForm` in your layout to output a form or create your own : simple point to /search/?q=YourQueryHere.

The search controller will search existing pages and DataObjects available in the sitemap.

Rendering the layout is up to you, but sample templates based on Bootstrap 5 are available in the /templates folder. We use the default
`Page_results.ss` layout.

## Searching DataObjects

By default, this module will also search any DataObject registered in the sitemap.

```php
GoogleSitemap::register_dataobject(BlogTag::class);
GoogleSitemap::register_dataobject(BlogCategory::class);
```

Will by default enable search on these objects.

## Misc

- Please note with use ending / at the end of the url
- Implement custom filters in your dataobjects with getSearchFilters

## Compatibility

Tested with 4.6 but should work on any ^4 projects

## Maintainer

LeKoala - thomas@lekoala.be
