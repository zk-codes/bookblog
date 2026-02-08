<!DOCTYPE html>
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
        <title>Jots - Road Less Read</title>
        <link rel="canonical" href="https://roadlessread.com/jots/">
        <meta name="description" content="Microblog posts and quick thoughts from Road Less Read.">
    </head>
    <body>

        <!-- SIDEBAR-->

        <aside class="sidebar">

            <!-- Skip/Return Link -->
            <p><a href="#top" class="smalltext">Skip/Return To Top</a></p>

            <!-- Mobile Menu Toggle -->
            <input type="checkbox" id="menu-toggle" class="menu-toggle">

            <!-- Menu -->
            <header>

                <!-- Mobile Menu -->
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <p class="sitetitle"><a href="/">Road Less Read</a></p>
                    <label for="menu-toggle" class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>
                </div>

                <!-- Menu Items -->
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

                <!-- Subscribe link -->
                <p class="highlight"><a href="/subscribe">Subscribe</a></p>

            </header>

            <p class="smalltext">Est. 2019</p>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content-area h-feed">

            <!-- Header -->
            <header>
                <!-- Breadcrumbs -->
                <p class="smalltext">You Are Here → <a href="/">Homepage</a> → <a href="/jots/">Jots</a> ↴</p>
                <!-- Heading -->
                <h1 id="top" class="p-name">Jots</h1>
                <!-- Metadata -->
                <p class="smalltext">Quick thoughts and microposts.</p>
            </header>

            <!-- Microposts Feed -->
            <section class="e-content">
<?php
// Parse markdown files and display them in reverse-chronological order
$jots = [];
$directory = __DIR__;

// Scan for markdown files
if (is_dir($directory)) {
    $files = scandir($directory);

    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension === 'md') {
            $filePath = $directory . '/' . $file;
            $content = file_get_contents($filePath);

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
                $date = date('Y-m-d\TH:i:s', filemtime($filePath));
            }

            // If no title, use filename
            if (empty($title)) {
                $title = pathinfo($file, PATHINFO_FILENAME);
            }

            // Convert markdown to HTML (basic conversion)
            $html = parseMarkdown($body);

            $jots[] = [
                'title' => $title,
                'date' => $date,
                'content' => $html,
                'filename' => $file,
            ];
        }
    }
}

// Sort jots in reverse-chronological order
usort($jots, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Function to convert basic markdown to HTML
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

// Display jots
if (empty($jots)) {
    echo '                <p>No posts yet. Check back soon!</p>' . "\n";
} else {
    foreach ($jots as $jot) {
        $formattedDate = date('j M Y', strtotime($jot['date']));
        $isoDate = date('c', strtotime($jot['date']));

        echo '                <article class="h-entry">' . "\n";
        echo '                    <header>' . "\n";
        echo '                        <h2 class="p-name">' . htmlspecialchars($jot['title']) . '</h2>' . "\n";
        echo '                        <p class="smalltext">' . "\n";
        echo '                            <strong>By</strong>: <a href="/about" class="p-author h-card" rel="author">Zachary Kai</a> » ' . "\n";
        echo '                            <strong>Posted</strong>: <time class="dt-published" datetime="' . htmlspecialchars($isoDate) . '">' . htmlspecialchars($formattedDate) . '</time>' . "\n";
        echo '                        </p>' . "\n";
        echo '                    </header>' . "\n";
        echo '                    <div class="e-content">' . "\n";
        echo '                        ' . $jot['content'];
        echo '                    </div>' . "\n";
        echo '                    <p class="smalltext"><a href="' . htmlspecialchars($jot['filename']) . '" class="u-url">Permalink</a></p>' . "\n";
        echo '                </article>' . "\n";
        echo '                <hr>' . "\n";
    }
}
?>
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
