<?php
// Get the slug from the URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Sanitize the slug to prevent directory traversal
$slug = basename($slug);

// Build the markdown file path
$mdFile = __DIR__ . '/' . $slug . '.md';

// Check if the file exists
if (!file_exists($mdFile)) {
    header("HTTP/1.0 404 Not Found");
    echo "Post not found.";
    exit;
}

// Read and parse the markdown file
$content = file_get_contents($mdFile);

// Parse frontmatter if it exists
$title = '';
$date = '';
$body = $content;

// Check for YAML frontmatter
if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
    $frontmatter = $matches[1];
    $body = $matches[2];

    // Extract title
    if (preg_match('/^title:\s*(.+)$/m', $frontmatter, $titleMatch)) {
        $title = trim($titleMatch[1], '"\'');
    }

    // Extract date
    if (preg_match('/^date:\s*(.+)$/m', $frontmatter, $dateMatch)) {
        $date = trim($dateMatch[1], '"\'');
    }
}

// If no date in frontmatter, use file modification time
if (empty($date)) {
    $date = date('Y-m-d\TH:i:s', filemtime($mdFile));
}

// If no title, use filename
if (empty($title)) {
    $title = ucfirst(str_replace(['-', '_'], ' ', $slug));
}

// Convert markdown to HTML
function parseMarkdown($text) {
    // Trim whitespace
    $text = trim($text);

    // Convert headers
    $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
    $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
    $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);

    // Convert links
    $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $text);

    // Convert bold
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);

    // Convert italic
    $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    $text = preg_replace('/_(.+?)_/', '<em>$1</em>', $text);

    // Convert paragraphs
    $paragraphs = explode("\n\n", $text);
    $html = '';
    foreach ($paragraphs as $para) {
        $para = trim($para);
        if (!empty($para)) {
            // Don't wrap if already has HTML tags
            if (!preg_match('/^<[h\d]/', $para)) {
                $html .= '<p>' . $para . '</p>' . "\n";
            } else {
                $html .= $para . "\n";
            }
        }
    }

    return $html;
}

$html = parseMarkdown($body);
$formattedDate = date('j M Y', strtotime($date));
$isoDate = date('c', strtotime($date));
?><!DOCTYPE html>
<html lang="en-US">

    <!-- HEAD -->
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Files -->
        <link rel="shortcut icon" href="/assets/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/style.css">
        <!-- Links -->
        <link rel="webmention" href="https://webmention.io/zacharykai.net/webmention" />
        <!-- Page Details -->
        <title><?php echo htmlspecialchars($title); ?> | Road Less Read</title>
        <link rel="canonical" href="https://roadlessread.com/jots/<?php echo htmlspecialchars($slug); ?>">
        <meta name="description" content="<?php echo htmlspecialchars(substr(strip_tags($html), 0, 160)); ?>">
    </head>
    <body>

        <!-- SIDEBAR-->

        <aside class="sidebar">

            <!-- Skip/Return Link -->
            <p><a href="#top" class="smalltext">Skip/Return To Top</a></p>

            <!-- Header Menu -->
            <header>
                <p class="sitetitle"><a href="/">Road Less Read</a></p>
                <nav>
                    <p><a href="/about.html">About</a></p>
                    <p><a href="/jots/">Jots</a></p>
                    <p><a href="/links">Links</a></p>
                    <p><a href="/lists/">Lists</a></p>
                    <p><a href="/notes/">Notes</a></p>
                    <p><a href="/reviews/">Reviews</a></p>
                    <p><a href="/sitemap">Sitemap</a></p>
                    <p><a href="/tools/">Tools</a></p>
                </nav>
                <p class="highlight"><a href="/subscribe">Subscribe</a></p>
            </header>

            <p class="smalltext">Est. 2019</p>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content-area h-entry">

            <!-- Header -->
            <header>
                <!-- Breadcrumbs -->
                <p class="smalltext">You Are Here → <a href="/">Homepage</a> → <a href="/jots/">Jots</a> ↴</p>
                <!-- Heading -->
                <h1 id="top" class="p-name"><?php echo htmlspecialchars($title); ?></h1>
                <!-- Metadata -->
                <p class="smalltext">
                    <!-- Author -->
                    <strong>By</strong>: <a href="/about" class="p-author h-card" rel="author">Zachary Kai</a> »
                    <!-- Date Published -->
                    <strong>Posted</strong>: <time class="dt-published" datetime="<?php echo htmlspecialchars($isoDate); ?>"><?php echo htmlspecialchars($formattedDate); ?></time>
                </p>
            </header>

            <!-- Post Content -->
            <section class="e-content">
                <?php echo $html; ?>
            </section>

            <!-- Copy & Share Link -->
            <section>
                <p class="smalltext"><strong>Copy + Share</strong>: <a href="https://roadlessread.com/jots/<?php echo htmlspecialchars($slug); ?>" class="u-url">roadlessread.com/jots/<?php echo htmlspecialchars($slug); ?></a></p>
                <p><a href="/jots/">← Back to all jots</a></p>
            </section>

            <p class="end">←------ •--♡--• ------→</p>

            <!-- FOOTER -->
            <footer>

                <!-- Newsletter Signup -->
                <section class="newsletter">
                    <p><strong><em>The Internet Isn't Fun Anymore. I Have Nothing To Read.</em></strong></p>
                    <p>Rubbish! It's all there, but there's no map. So skip the searching.</p>
                    <p><strong><a href="/subscribe">Get your curated books and curiosities delivered!</a></strong></p>
                </section>

                <!-- H-Card -->
                <section class="h-card vcard">
                    <section id="h-card-image">
                        <picture>
                            <source srcset="/assets/icon.webp" type="image/webp">
                            <img class="u-photo" loading="lazy" src="/assets/icon.png" alt="Zachary Kai's digital drawing: 5 stacked books (blue/teal/green/purple, black spine designs), green plant behind top book, purple heart on either side.">
                        </picture>
                    </section>
                    <section id="h-card-content">
                        <p><strong><a class="u-url u-id p-name p-author" href="https://roadlessread.com/about" rel="me author">
                        <span class="fn">Zachary Kai</span></a></strong> —
                        <span class="p-pronouns">he/him</span> |
                        <a class="u-email email" href="mailto:hi@zacharykai.net" rel="me">hi@zacharykai.net</a></p>
                        <p class="p-note">Zachary Kai is a space fantasy writer, offbeat queer, traveler, zinester, and avowed generalist. The internet is his livelihood and lifeline.</p>
                    </section>
                </section>

                <!-- Acknowledgement Of Country -->
                <section>
                    <p><strong>Acknowledgement Of Country</strong>: I owe my existence to the <a href="https://kht.org.au/" rel="noopener">Koori people's</a> lands: tended for millennia by the traditional owners and storytellers. What a legacy. May it prevail.</p>
                </section>

                <!-- Footer Links -->
                <section>
                    <p><strong>Useful Links</strong>:
                        <a href="/changelog">Changelog</a> |
                        <a href="/random">Random Page</a></p>
                </section>
            </footer>

        </main>
    </body>
</html>
