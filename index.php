<!DOCTYPE html>
<html lang="en-US">

    <!-- HEAD -->
    <head>
        <meta charset="utf-8">
        <title>Road Less Read: Better Books For Better Reading</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/assets/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/style.css">
        <link rel="webmention" href="https://webmention.io/zacharykai.net/webmention" />
        <link rel="canonical" href="https://roadlessread.com/">
        <meta name="date" content="2019-06-02">
        <meta name="last-modified" content="2025-08-20">
        <meta name="description" content="">
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
                    <p><a href="/about">About</a></p>
                    <p><a href="/links">Links</a></p>
                    <p><a href="/lists/">Lists</a></p>
                    <p><a href="https://lunaseeker.com/newsletter/" rel="noopener">Newsletter</a></p>
                    <p><a href="/notes/">Notes</a></p>
                    <p><a href="/reviews/">Reviews</a></p>
                    <p><a href="/assets/rss.xml">RSS</a></p>
                    <p><a href="/sitemap">Sitemap</a></p>
                </nav>
            </header>

            <p class="smalltext">Est. 2019</p>

        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content-area">

            <!-- Header -->
            <header>
                <p class="smalltext">You Are Here → <a href="/">Homepage</a> ↴</p>
                <h1 class="p-name">Enough With The Doomscrolling!</h1>
                <p class="smalltext">
                    <strong>Written By</strong>: <a href="/about">Zachary Kai</a> »
                    <strong>Published</strong>: <time class="dt-published" datetime="2019-06-02">2 Jun 2019</time> |
                    <strong>Updated</strong>: <time class="dt-modified" datetime="2025-11-15">15 Nov 2025</time>
                </p>
            </header>

            <!-- Introduction -->
            <p id="top" class="p-summary dropcap">Better books for better reading. Find something you <strong>actually</strong> want to read, <em>fast</em>. Just <a href="/sitemap">browse here</a> for what you like, and I’ll recommend your next book. Or, find it among all my favorites in <a href="/lists/read">this curated list</a>.</p>
            <p><strong>Note: This site is still very much under construction! Or should I say restoration?</strong></p>

            <!-- Featured Image -->
            <img src="/assets/imgs/decorative/booksonshelves2.png" alt="Books on shelves, filtered via dithering." loading="lazy">
            <p>Photo Credit: Christine Neale</p>

            <!-- Latest Entries: Script For Pulling Them In -->
            <section>

            </section>
                <h2>Latest Entries</h2>

                <ul>
                <?php

                    // Source Directories 
                    $directories = [
                    __DIR__ . '/lists/',
                    __DIR__ . '/notes/',
                    __DIR__ . '/reviews/',
                    ];
                    $posts = [];

    foreach ($directories as $currentDir) {
        if (is_dir($currentDir)) {
            $files = scandir($currentDir);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                    $filePath = $currentDir . $file;
                    $content = file_get_contents($filePath);
                    $title = '';
                    $dateAttr = '';

                    // Extract Title & Remove " | Zachary Kai"
                    if (preg_match('/<title>(.*?)<\/title>/s', $content, $matches)) {
                        $fullTitle = trim($matches[1]);
                        $title = preg_replace('/ \-| Road Less Read$/i', '', $fullTitle);
                    }

                    // Extract DateTime Attribute
                    if (preg_match('/<time\s+class="dt-published"\s+datetime="([^"]+)">.*?<\/time>/s', $content, $matches)) {
                        $dateAttr = $matches[1];
                    }

                    if (!empty($title) && !empty($dateAttr)) {
                        $slug = pathinfo($file, PATHINFO_FILENAME);
                        $formattedDisplayDate = date('j M Y', strtotime($dateAttr));

                        // Determine the URL path based on the directory
                        $relativePath = str_replace(__DIR__, '', $currentDir);
                        $relativePath = trim($relativePath, '/'); // Remove leading/trailing slashes
                        $url = '/' . $relativePath . '/' . $slug;

                        $posts[] = [
                            'title' => $title,
                            'date_attr' => $dateAttr,
                            'display_date' => $formattedDisplayDate,
                            'url' => $url,
                        ];
                    }
                }
            }
        }
    }

    // Sort Posts In Descending Order
    usort($posts, function($a, $b) {
        return strtotime($b['date_attr']) - strtotime($a['date_attr']);
    });

    // Get Latest Fifteen Posts
    $latestFifteenPosts = array_slice($posts, 0, 15);

    foreach ($latestFifteenPosts as $post) {
        echo '                    <li><a href="' . htmlspecialchars($post['url']) . '">' . htmlspecialchars($post['title']) . '</a> | ' . htmlspecialchars($post['display_date']) . '</li>' . "\n";
    }
    ?>
</ul>


            </section>

            <!-- Site Navigation -->
            <section>
                <p>To see everything that's on this site, visit <a href="/sitemap.html">the sitemap</a>. You can also browse by <a href="/lists/">lists</a>, <a href="/notes/">notes</a>, and <a href="/reviews/">reviews</a>. Happy reading, and hope you find something useful!</p>
            </section>

            <p class="end">←------ •--♡--• ------→</p>

            <!-- FOOTER -->
            <footer>

                <!-- Newsletter Signup -->
                <section>
                    <p><strong>The Internet Isn't Fun Anymore. I Have Nothing To Read.</strong></h2>
                    <p>Rubbish! It's all there, but there's no map. So skip the searching. <a href="https://lunaseeker.com/newsletter/" rel="noopener">Get curated books and curiosities delivered!</a></p>
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
                        <p><strong><a class="u-url u-id p-name" href="https://roadlessread.com" rel="me">
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