<?php

$base_url = 'https://roadlessread.com';

// Initialize the array that will hold the URLs for random selection
$pages = [];

// Define the path to your sitemap.xml file.
// This assumes sitemap.xml is at the root of your web server's document directory.
$sitemapPath = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml';

// List of URLs to exclude from the random selection
$exclude_urls = [
    '/404',
    '/random',
    '/successful'
];

// Check if the sitemap file exists
if (file_exists($sitemapPath)) {
    $dom = new DOMDocument();
    // Load the XML file; suppress warnings for potentially malformed XML if desired
    // libxml_use_internal_errors(true);
    if ($dom->load($sitemapPath)) {
        // libxml_clear_errors();
        $xpath = new DOMXPath($dom);

        // Register the sitemap namespace (standard)
        $xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // Query all 'loc' (location) elements
        $locNodes = $xpath->query('//s:loc');

        foreach ($locNodes as $locNode) {
            $fullUrl = trim($locNode->nodeValue);
            $relativeUrl = str_replace($base_url, '', $fullUrl);

            // Special handling for the root URL
            if (empty($relativeUrl) || $relativeUrl === '') {
                $relativeUrl = '/';
            }

            // Remove trailing slash for non-root directories, if you want consistent paths
            // For example, /zines/ becomes /zines, unless it's just the root /
            if ($relativeUrl !== '/' && substr($relativeUrl, -1) === '/') {
                $relativeUrl = rtrim($relativeUrl, '/');
            }

            // Add the URL to our pages array if it's not in the exclusion list
            if (!in_array($relativeUrl, $exclude_urls)) {
                $pages[] = $relativeUrl;
            }
        }
    } else {
        // Log error if sitemap fails to load
        error_log("RANDOM PAGE ERROR: Failed to load sitemap.xml from $sitemapPath");
    }
} else {
    // Log error if sitemap file not found
    error_log("RANDOM PAGE ERROR: Sitemap.xml not found at $sitemapPath");
    // Fallback: if sitemap can't be loaded, you might want to redirect to homepage
    // or a default page to prevent errors for the user.
    header("Location: $base_url/");
    exit;
}

// Ensure there are pages to select from after parsing and filtering
if (!empty($pages)) {
    // Select Random Page
    $random_page = $pages[array_rand($pages)];

    // Construct Full URL
    $redirect_url = $base_url . $random_page;
} else {
    // If no pages were found (e.g., empty sitemap or all excluded), redirect to homepage
    $redirect_url = $base_url . '/';
}


// Perform Redirect
header("Location: $redirect_url");
exit;

?>