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
        <link rel="indieauth-metadata" href="https://roadlessread.com/.well-known/oauth-authorization-server" />
        <link rel="authorization_endpoint" href="https://roadlessread.com/auth/" />
        <link rel="token_endpoint" href="https://roadlessread.com/auth/token.php" />
        <link rel="me" href="mailto:hi@zacharykai.net">
        <link rel="me" href="https://github.com/zk-codes">
        <link rel="micropub" href="https://roadlessread.com/micropub/" />
        <link rel="webmention" href="https://webmention.io/zacharykai.net/webmention" />
        <!-- Page Details -->
        <title>Road Less Read: Better Books For Better Reading</title>
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
        <main class="main-content-area h-entry">

            <!-- Header -->
            <header>
                <!-- Breadcrumbs -->
                <p class="smalltext">You Are Here → <a href="/">Homepage</a> ↴</p>
                <!-- Heading -->
                <h1 class="p-name">Enough With The Doomscrolling!</h1>
                <!-- Metadata -->
                <p class="smalltext">
                    <!-- Author --->
                    <strong>Written By</strong>: <a href="/about" class="p-author h-card" rel="author">Zachary Kai</a> »
                    <!-- Date Written -->
                    <strong>Published</strong>: <time class="dt-published" datetime="2019-06-02">2 Jun 2019</time> |
                    <!-- Date Updated -->
                    <strong>Updated</strong>: <time class="dt-updated" datetime="2025-11-15">15 Nov 2025</time>
                </p>
                <!-- Invisible Microformats -->
                <p style="display: none;" class="p-summary">Road Less Read is a book, literature, writing, and zine site with lists, resources, and tools.</p>
            </header>

            <!-- Introduction -->
            <section class="e-content">

                <section>
                    <p id="top" class="dropcap">Better books for better reading. Find ones you <strong>actually</strong> want to read, <em>fast</em>. Just <a href="/sitemap">browse</a> for what you like, and I'll recommend your next book. Or, find it among all my favorites in <a href="/lists/read">this curated list</a>.</p>
                    <p><strong>Note: This site is still very much under construction! Or should I say restoration?</strong></p>
                </section>
                
                <!-- Featured Image -->
                <section>
                    <img class="u-photo" src="/assets/imgs/decorative/booksonshelves2.png" alt="Books on shelves, filtered via dithering." loading="lazy">
                    <p>Photo Credit: Christine Neale</p>
                </section>
                
                <section>
                    <!-- Latest Entries -->
                    <h2>Latest Entries</h2>

                    <ul>
                    <?php
                    // Source Directories
                    $directories = [
                        __DIR__ . '/lists/',
                        __DIR__ . '/notes/',
                        __DIR__ . '/reviews/',
                        __DIR__ . '/tools/',
                    ];
                    $posts = [];

                    // Scan directories for HTML and PHP files
                    foreach ($directories as $currentDir) {
                        if (is_dir($currentDir)) {
                            $files = scandir($currentDir);

                            foreach ($files as $file) {
                                $extension = pathinfo($file, PATHINFO_EXTENSION);

                                if ($extension === 'html' || $extension === 'php') {
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
                                        $relativePath = trim($relativePath, '/');
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

                    // Output Posts
                    foreach ($latestFifteenPosts as $post) {
                        echo '                        <li><a href="' . htmlspecialchars($post['url']) . '">' . htmlspecialchars($post['title']) . '</a> | ' . htmlspecialchars($post['display_date']) . '</li>' . "\n";
                    }
                    ?>
                    </ul>
                </section>

                <!-- Site Navigation -->
                <section>
                    <p>To see everything that's on this site, visit <a href="/sitemap.html">the sitemap</a>. You can also browse by <a href="/lists/">lists</a>, <a href="/notes/">notes</a>, and <a href="/reviews/">reviews</a>. Happy reading, and hope you find something useful!</p>
                </section>

                <!-- Copy & Share Link -->
                <section>
                    <p class="smalltext"><strong>Copy + Share</strong>: <a href="https://roadlessread.com/" class="u-url">roadlessread.com</a></p>
                </section>

            </section>

            <p class="end">←------ •--♡--• ------→</p>

            <!-- FOOTER -->
            <footer>

                <!-- Newsletter Signup -->
                <section class="newsletter">
                    <p><strong><em>The Internet Isn’t Fun Anymore. I Have Nothing To Read.</em></strong></p>
                    <p>Rubbish! It’s all there, but there’s no map. So skip the searching.</p>
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