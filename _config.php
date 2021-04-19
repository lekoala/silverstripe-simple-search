<?php

// Add custom route to sitemap
if (class_exists(\Wilr\GoogleSitemaps\GoogleSitemap::class)) {
    \Wilr\GoogleSitemaps\GoogleSitemap::register_routes([
        '/search/',
    ]);
}
