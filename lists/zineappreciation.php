<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Webring Members

$sites = [
    [
        'id' => 1,
        'site_name' => 'Road Less Read',
        'site_url' => 'https://roadlessread.com/',
    ],
    [
        'id' => 2,
        'site_name' => 'Mewizard',
        'site_url' => 'https://mewizard.nekoweb.org',
    ],
];

// Find Current Site Index Based On Referrer

function findCurrentSiteIndex($sites, $referrer_url) {
    if (empty($referrer_url)) {
        return -1;
    }

    foreach ($sites as $index => $site) {
        $site_domain = parse_url($site['site_url'], PHP_URL_HOST);
        $referrer_domain = parse_url($referrer_url, PHP_URL_HOST);

        if ($site_domain && $referrer_domain) {
            $site_domain = preg_replace('/^www\./', '', $site_domain);
            $referrer_domain = preg_replace('/^www\./', '', $referrer_domain);

            if ($site_domain === $referrer_domain) {
                return $index;
            }
        }
    }

    return -1;
}

// Check If It's A Navigation Request

$action = $_GET['action'] ?? null;

if ($action && in_array($action, ['random', 'next', 'prev', 'previous', 'list', 'all'])) {

    // Handle Navigation
    $referrer = $_SERVER['HTTP_REFERER'] ?? '';

    if (empty($sites)) {
        header("Location: /");
        exit;
    }

    $target_site = null;

    switch ($action) {
        case 'random':
            $random_index = array_rand($sites);
            $target_site = $sites[$random_index];
            break;

        case 'next':
            $current_index = findCurrentSiteIndex($sites, $referrer);
            if ($current_index >= 0) {
                $next_index = ($current_index + 1) % count($sites);
                $target_site = $sites[$next_index];
            } else {
                $target_site = $sites[0];
            }
            break;

        case 'prev':
        case 'previous':
            $current_index = findCurrentSiteIndex($sites, $referrer);
            if ($current_index >= 0) {
                $prev_index = ($current_index - 1 + count($sites)) % count($sites);
                $target_site = $sites[$prev_index];
            } else {
                $target_site = end($sites);
            }
            break;

        case 'list':
        case 'all':
            header("Location: /#members");
            exit;
    }

    // Redirect To Target Site If Found
    if ($target_site) {
        $redirect_url = $target_site['site_url'];

        if (!preg_match('/^https?:\/\//', $redirect_url)) {
            $redirect_url = 'http://' . $redirect_url;
        }

        header("Location: " . $redirect_url);
        exit;
    } else {
        header("Location: /");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en-US">

    <!-- HEAD -->
    <head>
        <meta charset="utf-8">
        <title>Zine Appreciation Webring - Road Less Read</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/assets/icon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/style.css">
        <link rel="webmention" href="https://webmention.io/zacharykai.net/webmention" />
        <link rel="canonical" href="https://roadlessread.com/lists/zineappreciation">
        <meta name="date" content="2026-02-03">
        <meta name="last-modified" content="2026-02-03">
        <meta name="description" content="A webring connecting zine makers and appreciators across the internet.">
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
        <main class="main-content-area">

            <!-- Header -->
            <header>
                <p class="smalltext"><a href="/">Homepage</a> • <a href="/lists/">Lists</a> ↴</p>
                <h1 class="p-name">Zine Appreciation Webring</h1>
                <p class="smalltext">
                    <strong>Written By</strong>: <a href="/about">Zachary Kai</a> »
                    <strong>Published</strong>: <time class="dt-published" datetime="2026-02-03">3 Feb 2026</time> |
                    <strong>Updated</strong>: <time class="dt-modified" datetime="2026-02-03">3 Feb 2026</time>
                </p>
            </header>

            <!-- PRIMARY CONTENT -->
            <p id="top" class="p-summary dropcap">A webring connecting zine makers and appreciators across the internet. Find your people and find your pages.</p>

            <section>

                <!-- Table Of Contents -->
                <details>
                    <summary><strong>Table Of Contents</strong></summary>
                    <ul>
                        <li><a href="#navigate">Navigate The Ring</a></li>
                        <li><a href="#members">Members</a></li>
                        <li><a href="#guidelines">Submission Guidelines</a></li>
                        <li><a href="#join">Join The Webring</a></li>
                    </ul>
                </details>

                <!-- Navigation Links -->
                <h2 id="navigate">Navigate The Ring</h2>
                <p>Webrings are a classic way for folks to find others with similar interests! Use these links to explore other sites in the webring:</p>
                <ul>
                    <li><a href="?action=random">Random Site</a> - Jump to a random member site</li>
                    <li><a href="?action=next">Next Site</a> - Visit the next site in the ring</li>
                    <li><a href="?action=prev">Previous Site</a> - Visit the previous site in the ring</li>
                    <li><a href="#members">View All Sites</a> - See the complete member list</li>
                </ul>

                <!-- Members -->
                <h2 id="members">Current Members</h2>
                <p><em>Listed in order of joining.</em></p>
                <ul>
                    <?php foreach ($sites as $site): ?>
                    <li><a href="<?php echo htmlspecialchars($site['site_url']); ?>" rel="noopener"><?php echo htmlspecialchars($site['site_name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Guidelines -->
                <h2 id="guidelines">Submission Guidelines</h2>
                <p>To join, your site must be a personal one and actively updated. And have the code somewhere visible! (Format it and style it however you'd like, provided it's accessible.)</p>
                <p><pre><code>&lt;a href="https://roadlessread.com/lists/zineappreciation"&gt;Zine Appreciation Webring&lt;/a&gt; &rarr; &lt;a href="https://roadlessread.com/lists/zineappreciation?action=prev"&gt;Previous&lt;/a&gt; | &lt;a href="https://roadlessread.com/lists/zineappreciation?action=random"&gt;Random&lt;/a&gt; | &lt;a href="https://roadlessread.com/lists/zineappreciation?action=next"&gt;Next&lt;/a&gt;</code></pre></p>

                <!-- Join The Webring -->
                <h3 id="join">Join The Webring</h3>
                <p>Want to join the webring? Use the form below or <a href="/contact">contact me</a>!</p>
                <ul>
                    <li>I'll check your site meets the guidelines</li>
                    <li>Once approved, I'll email you with the HTML code for webring navigation</li>
                    <li>You can request changes or removal at any time</li>
                    <li>Email addresses are never displayed publicly</li>
                </ul>

                <!-- Submission Form -->
                <form id="submission-form" action="/assets/scripts/submissions.php" method="post">
                    <label for="name">Write what you'd like me to call you:*</label>
                    <br/>
                    <input type="text" id="name" name="name" required/>
                    <br/>
                    <label for="email">Enter in your email:*</label>
                    <br/>
                    <input type="email" id="email" name="email" required/>
                    <br/>
                    <label for="site_name">Type in your site's name:*</label>
                    <br/>
                    <input type="text" id="site_name" name="site_name" required/>
                    <br/>
                    <label for="site_url">Enter your site's URL:*</label>
                    <br/>
                    <input type="url" id="site_url" name="site_url" required/>
                    <br/>
                    <label for="favorite_zine">Tell me about a zine you love:</label>
                    <br/>
                    <input type="text" id="favorite_zine" name="favorite_zine"/>
                    <br/>
                    <label for="captcha">Enter this page's title (the heading at the top):*</label>
                    <br>
                    <input type="text" id="captcha" name="captcha" required>
                    <br>
                    <input type="hidden" name="form_type" value="zineappreciation">
                    <button type="submit">Join The Ring!</button>
                </form>

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
                        <p><strong><a class="u-url u-id p-name" href="https://roadlessread.com" rel="me">
                        <span class="fn">Zachary Kai</span></a></strong> —
                        <span class="p-pronouns">he/him</span> |
                        <a class="u-email email" href="mailto:hi@zacharykai.net" rel="me">hi@roadlessread.com</a></p>
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
