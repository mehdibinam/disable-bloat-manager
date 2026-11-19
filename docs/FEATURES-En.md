# Full feature list

> The plugin UI is Persian; this is the English version of the full feature list. Total features: **161** (v2.2.0).
> For the Persian version, see [FEATURES-Fa.md](FEATURES-Fa.md). A short English summary per category is in the main README.

## Visitor side

Remove the extra code and files WordPress puts on your site's pages.

### Page head

| Feature | Description | Notes |
|---|---|---|
| Remove emoji script and styles | Modern browsers display emojis themselves. This option removes the extra files WordPress loads for emojis on every page. | Recommended |
| Remove RSD tag | This tag was only needed for old remote publishing applications. If you don't publish posts with such an app, it's safe to remove. | Recommended |
| Remove WLW Manifest | A file related to the old Windows Live Writer software, which hasn't been supported for years. Removing it does not harm the appearance or function of the site. | Recommended |
| Remove shortlink | WordPress puts a short URL like ?p=123 in the page code for every post. If you don't use it, remove it. |  |
| Hide WordPress version | The WordPress version is written into the site code and helps hackers find vulnerabilities of that version. This option hides it. | Recommended |
| Remove previous and next post links | Tags that introduced the previous and next post to search engines. Today they have almost no effect. |  |
| Remove DNS prefetch | WordPress tells the browser to connect to external domains early. Removing it makes the code lighter, but external files may start loading slightly later. | Needs review |
| Blank favicon for sites without one | If you haven't set a favicon for the site, browsers send an extra request and get a 404 error. This option provides a blank icon. It has no effect if the site already has a favicon. |  |
| Remove REST API link from head | The tag and header that advertise the REST API are removed from the page. The REST API itself still works. | Recommended |

### Scripts & styles

| Feature | Description | Notes |
|---|---|---|
| Disable auto-embeds (oEmbed) | With this section off, video or tweet links in posts are no longer automatically turned into players, and others can no longer embed your posts on their sites. | Needs review |
| Remove Dashicons for visitors | Dashicons is the set of icons used in the WordPress admin, and visitors usually don't need it. It is only removed for logged-out users. | Needs review |
| Remove jQuery Migrate | A helper library that keeps very old jQuery code compatible. If your theme and plugins are up to date, you don't need it. | Needs review |
| Remove Gutenberg block styles | If you build the site with the classic editor or a page builder (such as Elementor), these styles aren't needed and removing them makes the page lighter. | Needs review |
| Remove block theme global styles | The color and font code that block themes add to every page. If your theme isn't a block theme, it's safe to remove. | Needs review |
| Remove password strength meter | A heavy script that shows password strength in registration and profile forms. If you don't need it, remove it. |  |
| Disable Google Maps | Removed wherever a theme or plugin loads Google Maps. If you have a map on the site, don't enable this or add its page to the exceptions. | Needs review, Configurable |
| Load block styles only when used | Instead of one large stylesheet for all blocks, each block loads its own styles only when it is present on the page. |  |

### Feeds & sitemap

| Feature | Description | Notes |
|---|---|---|
| Remove feed links from head | RSS feed links are removed from the page code, but the feeds themselves still work. |  |
| Disable feeds entirely | The feed (RSS) URLs are closed and feed readers can no longer receive the site's content. | Needs review |
| Disable WordPress default sitemap | The sitemap (wp-sitemap.xml) helps search engines find pages. If an SEO plugin generates a sitemap, this one isn't needed. | Needs review |
| Remove Generator tag from feeds | Hides the WordPress version from inside the feed files. |  |

### URLs & text

| Feature | Description | Notes |
|---|---|---|
| Remove ?ver from file URLs | The version number is removed from the end of CSS and JavaScript file URLs so versions aren't exposed. After a theme or plugin update, browsers may keep the old file. | Needs review |
| Disable automatic text correction (WordPress) | WordPress automatically changes «Wordpress» to «WordPress» wherever it appears. This option disables automatic text tweaking in the sections you choose. | Configurable |

## Comments

Limit or completely turn off the comments section.

### Comments

| Feature | Description | Notes |
|---|---|---|
| Disable comments entirely | Comments and pingbacks are closed across the whole site and the Comments menu is hidden from the admin dashboard. | Needs review |
| Remove link from comment author name | The author name becomes plain text instead of a link. This reduces spammers' incentive to post promotional comments. |  |
| Remove comment reply script | Keeps the threaded comment reply script only on pages where it's actually needed. | Recommended |
| Prevent self-pingbacks | When you link to another post on the same site, WordPress sends a notification to itself and creates a useless comment. This option prevents that. | Recommended |
| Close comments for selected post types | Comments are closed only for the post types you select; the rest remain normal. |  |
| Automatically close comments on old posts | Commenting is closed on posts that are more than a certain number of days old, to reduce spam. | Configurable |
| Paginate comments | Long comment threads are split into several pages to keep the page light. | Configurable |
| Disable pingbacks and trackbacks | WordPress no longer accepts or sends pingback and trackback notifications. | Recommended |
| Don't turn URLs in comment text into links | URLs that users write in comments are not automatically turned into links, reducing spam. |  |

### Avatar & privacy

| Feature | Description | Notes |
|---|---|---|
| Disable Gravatar | No request is sent to gravatar.com for user avatars; faster and more private. If left empty, a simple avatar is shown. | Recommended, Configurable |
| Automatically clean up spam comments | Spam comments that have been in the spam folder for a certain period are automatically deleted. | Selectable |

## Admin dashboard

Make the admin dashboard lighter and cleaner.

### Admin speed

| Feature | Description | Notes |
|---|---|---|
| Control Heartbeat | Heartbeat communicates with the server every few seconds (for autosave, post locking and notifications). It puts a lot of load on weak hosts. If you choose «Disable», autosave and post locking also stop working. | Recommended, Configurable, Selectable |
| Remove dashboard widgets | Removes the widgets you check from the admin dashboard home; all are selected by default. WooCommerce widgets are separate in the WooCommerce section. | Configurable |
| Remove admin page transition animations | Removes the fade effect when moving from one admin page to another. |  |
| Remove admin quick search (Command Palette) | Removes the shortcut search window that opens with Ctrl+K in the admin and editor. |  |

### Post saving

| Feature | Description | Notes |
|---|---|---|
| Limit post revisions | WordPress keeps a copy of every edit and the database gets heavy. This option keeps only the latest few revisions. A value of 0 means no revisions are saved. | Recommended, Configurable |
| Change autosave interval | The draft autosave interval is normally 60 seconds. A larger number means fewer requests to the server. | Configurable |
| Disable autosave entirely | The editor no longer saves the draft automatically. Only enable this if you prefer manual saving. | Needs review |
| Shorten trash retention period | Posts sent to the trash are deleted permanently sooner than the default 30 days. | Configurable |
| Remove tags from posts | The default post tags (Tags) are disabled; suitable for sites that only use categories. | Needs review |

### Media

| Feature | Description | Notes |
|---|---|---|
| Prevent thumbnail generation for PDFs | WordPress creates several thumbnails for every PDF you upload. This option prevents that and uses less hosting space. |  |
| Use GD for image processing | WordPress uses the GD library to create image sizes; this helps if you have problems with Imagick on your host (memory errors). |  |

### Admin bar & appearance

| Feature | Description | Notes |
|---|---|---|
| Remove WordPress logo from the admin bar | The small WordPress logo menu and its links are removed from the top bar of the site and the admin. |  |
| Remove greeting from the admin bar | The «Hello» text next to the username is removed, leaving only the name and picture. |  |
| Remove admin footer text | The thank-you line for WordPress and the version number are removed from the bottom of admin pages. |  |
| Remove the «Screen Options» button | The Screen Options button above admin tables is hidden. |  |
| Remove the «Help» tab | The WordPress Help tab at the top of admin pages is removed. |  |
| Hide the admin bar for non-administrators | The admin bar at the top of the site is shown only to administrators. |  |

### Notices

| Feature | Description | Notes |
|---|---|---|
| Manage admin notices | Hide promotional notices from plugins. The «Selectable» mode adds a «Hide» button next to each notice. | Recommended, Selectable |
| Hide update notices from non-administrators | The «A new WordPress version is available» message is shown only to users who have permission to update. |  |
| Disable periodic admin email verification | The «Is your admin email still correct?» prompt, shown every few months, is removed. |  |
| Remove privacy page reminder | Notices and reminders about the privacy policy page are removed from the admin dashboard. |  |
| Remove outdated browser warning | The «Your browser is out of date» warning box is removed from the dashboard. |  |
| Remove new editor promo | The «Try the new editor» promotional box is removed from the dashboard. |  |
| Remove plugin suggestions from the Add Plugins page | The «Featured» and «Popular» tabs and the suggested list are removed from the Add Plugins page. |  |
| Remove popular plugin tags | The popular tags box on the Add Plugins page is hidden. |  |

### Login page

| Feature | Description | Notes |
|---|---|---|
| Hide the WordPress logo on the login page | The logo above the WordPress login form is not displayed. |  |
| Change login logo link to the site home page | Instead of wordpress.org, clicking the logo goes to your own home page. |  |
| Change login logo title to the site name | The logo's tooltip text becomes your site name. |  |
| Remove language selector from the login page | The language switcher at the bottom of the login form is removed. |  |

### WordPress tools

| Feature | Description | Notes |
|---|---|---|
| Disable Site Health entirely | Site Health checks, menu, scheduled events and their endpoints are disabled. | Needs review |
| Hide «Import» and «Export» | The import and export pages are removed from the Tools menu and direct access is blocked. | Needs review |
| Disable WordPress privacy tools | The tools for exporting and erasing users' personal data are disabled. | Needs review |

## Block editor

Turn off the extra sections of the Gutenberg editor.

### Block editor (Gutenberg)

| Feature | Description | Notes |
|---|---|---|
| Disable Gutenberg | The block editor (Gutenberg) is turned off and the classic editor replaces it. Suitable for sites that use a page builder. | Needs review |

### Extras

| Feature | Description | Notes |
|---|---|---|
| Restore the old widgets editor | The Widgets page is displayed in the classic form. |  |
| Automatically close the welcome guide | The tutorial window shown on first entering the editor doesn't open. |  |
| Remove the external block directory | Searching for plugin blocks from the internet in the editor is removed. | Recommended |
| Remove core block patterns | WordPress's default block patterns (Patterns) are removed from the editor. |  |
| Prevent fetching patterns from the internet | WordPress doesn't fetch ready-made patterns from an external server; the editor opens faster. | Recommended |
| Exit full-screen mode automatically | The editor opens with the admin menu instead of in full-screen mode. |  |
| Disable the template editor | Template editing from within the block editor is removed. |  |

## Widgets

Disable the WordPress widgets you don't need.

### Widgets

| Feature | Description | Notes |
|---|---|---|
| Disable all core widgets | All default WordPress widgets (search, archives, categories, etc.) become unavailable. | Needs review |
| Disable selected widgets | Only the widgets you check are removed. |  |
| Remove widget support entirely | The Widgets menu is removed from the admin and the theme no longer has a widget sidebar. Only for sites without a sidebar. | Needs review |

## Updates

Control automatic updates of the core, plugins and themes.

### Automatic updates

| Feature | Description | Notes |
|---|---|---|
| Disable automatic theme updates | Themes are not updated until you choose to update them. |  |
| Disable automatic plugin updates | Plugins are not updated until you choose to update them. |  |
| Disable automatic core updates | WordPress does not automatically install minor or major versions. | Needs review |
| Disable core update checks entirely | WordPress no longer checks for new versions and sends no request to the WordPress server. | Needs review |

## WooCommerce

Control WooCommerce ads, extra sections and heavy scripts.

### WooCommerce admin

| Feature | Description | Notes |
|---|---|---|
| Disable WooCommerce Admin | The new, heavy WooCommerce interface (analytics, dashboard, etc.) is turned off and the classic admin remains. | Needs review, Requires WooCommerce |
| Hide the WooCommerce «Home» page | The WooCommerce «Home» page is removed from the menu and the entry point redirects to Orders. | Requires WooCommerce |
| Remove the Marketing section | The WooCommerce Marketing menu and its suggestions are removed. | Recommended, Requires WooCommerce |
| Remove WooCommerce.com notices | Notices about connecting a WooCommerce.com account and renewing subscriptions are hidden. | Recommended, Requires WooCommerce |
| Remove Marketplace suggestions | Suggested WooCommerce plugins are not shown on product and settings pages. | Recommended, Requires WooCommerce |
| Remove the Plugins and Subscriptions submenus | The «Plugins» and «My Subscriptions» submenus are removed from the WooCommerce menu. | Requires WooCommerce |
| Remove shipping method suggestions | The box suggesting shipping plugins in settings is removed. | Requires WooCommerce |
| Remove payment gateway suggestions | The box suggesting payment gateways in settings is removed. | Requires WooCommerce |
| Remove the Marketplace link from the payment page | The «Add payment method» link to the Marketplace on the Payments page is hidden. | Requires WooCommerce |
| Remove the WooCommerce status widget from the dashboard | The store status box is removed from the admin dashboard home. | Requires WooCommerce |
| Remove the WooCommerce setup widget | The store setup guide box is removed from the dashboard. | Requires WooCommerce |
| Disable the new product editor | The product edit page is displayed in the classic form. | Requires WooCommerce |
| Remove WooCommerce blocks in the admin | WooCommerce block styles are removed in the editor. | Needs review, Requires WooCommerce |

### Emails

| Feature | Description | Notes |
|---|---|---|
| Disable WooCommerce guide emails | WooCommerce's educational and promotional emails are not sent to the admin. | Requires WooCommerce |
| Remove the «Get the app» line from emails | The WooCommerce app promo line is removed from the bottom of emails. | Requires WooCommerce |

### Storefront (visitor side)

| Feature | Description | Notes |
|---|---|---|
| Load WooCommerce only on store pages | WooCommerce scripts and styles are not loaded on regular pages and posts. | Needs review, Requires WooCommerce |
| Disable Cart Fragments | The heavy mini-cart update request on every page is removed. | Needs review, Requires WooCommerce |
| Remove WooCommerce block styles | WooCommerce block styles are removed on the visitor side. | Needs review, Requires WooCommerce |
| Disable WooCommerce widgets | Cart, price filter, and other widgets are removed. | Requires WooCommerce |
| Disable product reviews | Product ratings and reviews are turned off. | Needs review, Requires WooCommerce |
| Remove WooCommerce password strength meter | The heavy password strength script on the account page is removed. | Requires WooCommerce |
| Load Stripe only on needed pages | Stripe scripts are loaded only on the product, cart and checkout pages. | Requires Stripe gateway |

## Fonts & plugins

Remove or locally host Google Fonts and hide ads and extra sections from popular plugins. If a plugin isn't installed, its options are shown as disabled.

### Jetpack

| Feature | Description | Notes |
|---|---|---|
| Remove Jetpack ads and notices | Jetpack promotional messages and install suggestions are hidden. | Recommended, Requires Jetpack |
| Disable Blaze | The Blaze advertising tool is not shown in the admin. | Requires Jetpack |

### Elementor

| Feature | Description | Notes |
|---|---|---|
| Remove the Elementor widget from the dashboard | The Elementor news box is removed from the admin dashboard home. | Requires Elementor |
| Hide the Elementor «Home» page | The Elementor «Home» page is removed from the menu and the entry point redirects to Templates. | Requires Elementor |
| Disconnect from the Elementor account | Requests to connect to the Elementor account and cloud services (online templates, license) are stopped. | Needs review, Requires Elementor |

### Yoast SEO

| Feature | Description | Notes |
|---|---|---|
| Remove premium version ads | Premium promotional notices and menu are hidden. | Recommended, Requires Yoast SEO |
| Remove the Yoast menu from the admin bar | The Yoast menu is removed from the admin bar. | Requires Yoast SEO |
| Remove the Yoast HTML comment from the page | The promotional comment inside the page code is removed. | Recommended, Requires Yoast SEO |
| Remove the Yoast widget from the dashboard | The Yoast box is removed from the admin dashboard home. | Requires Yoast SEO |

### Contact Form 7

| Feature | Description | Notes |
|---|---|---|
| Remove Contact Form 7 scripts and styles | The plugin's files are removed on all pages. Only enable this if you have no forms or load them yourself on the form page. | Needs review, Requires Contact Form 7 |

### ACF & others

| Feature | Description | Notes |
|---|---|---|
| Hide the Advanced Custom Fields menu | The ACF menu is removed from the admin (fields still work). | Requires Advanced Custom Fields |
| Remove the WPML plugin Generator tag | The WPML version is hidden from the site code. | Requires WPML |
| Remove UpdraftPlus from the admin bar | The UpdraftPlus menu is removed from the admin bar. | Requires UpdraftPlus |
| Remove the SkyVerge menu | The SkyVerge dashboard menu that comes with some WooCommerce plugins is hidden. | Requires WooCommerce |
| Remove WP Desk widgets from the dashboard | Widgets from WP Desk plugins (invoices, checkout fields, etc.) are hidden. | Requires WooCommerce |
| Remove the Flexible Shipping «Shipping Plugins» menu | The submenu suggesting shipping plugins is removed from WooCommerce. | Requires WooCommerce |

### Google Fonts

| Feature | Description | Notes |
|---|---|---|
| Remove Google Fonts | All styles, links and @import related to fonts.googleapis.com are removed from the page (also in Elementor). | Needs review |
| Host Google Fonts locally | Google Fonts used by the theme are saved once on your own host, so no request is sent to Google's server. | Recommended |
| Add font-display: swap | Until the font loads, text is shown with a fallback font so the visitor doesn't see a blank page. | Recommended |
| Load Font Awesome asynchronously | The Font Awesome stylesheet is loaded without blocking page rendering. |  |
| Remove Elementor Google Fonts | Elementor doesn't fetch Google Fonts from the internet. | Requires Elementor |

## AI

Turn off the AI features of WordPress and plugins.

### WordPress

| Feature | Description | Notes |
|---|---|---|
| Disable WordPress AI | WordPress's built-in AI features are turned off and the Connectors page is also removed from the Settings menu. | Recommended |

### Plugins

| Feature | Description | Notes |
|---|---|---|
| Disable Elementor AI | Elementor's AI buttons and tools are not shown in the editor. | Requires Elementor |
| Disable Elementor MCP | The connection of external AI tools to Elementor (MCP) is turned off. | Requires Elementor |
| Disable Rank Math AI | The Content AI tool in Rank Math is removed. | Requires Rank Math |
| Disable Yoast AI | The AI text generation buttons in Yoast are removed. | Requires Yoast SEO |
| Disable Jetpack AI | The AI assistant in Jetpack is turned off. | Requires Jetpack |

## Security

WordPress hardening, login protection and firewall, all in one place.

### WordPress hardening

| Feature | Description | Notes |
|---|---|---|
| Disable XML-RPC | The old xmlrpc.php endpoint, which hackers exploit for password guessing and pingback attacks, is closed. | Recommended |
| Prevent username discovery | ?author=1 probing, user listings in REST and the sitemap, and the author name in oEmbed are blocked for guests. | Recommended |
| Disable author pages | Author archive URLs are redirected to the home page. | Needs review |
| Disable the theme and plugin file editor | Direct code editing from within the admin is blocked so that a compromised admin account can't inject malicious code. | Recommended |
| Disable application passwords | The ability to create Application Passwords for REST access is turned off. |  |
| Close the REST batch route for guests | Batch REST requests are allowed only for logged-in users. | Recommended |

### REST API access

| Feature | Description | Notes |
|---|---|---|
| Who can use the REST API? | The REST API is the way apps, the block editor and plugins read and change site data via the /wp-json endpoint. If you don't need guests to access it, you can restrict it; the block editor still works for authorized users. | Selectable |

### Security headers

| Feature | Description | Notes |
|---|---|---|
| Send security headers | The X-Content-Type-Options, X-Frame-Options, Referrer-Policy and Permissions-Policy headers are sent for browser protection. Headers already set are not replaced. | Recommended |
| Enable HSTS | Tells the browser to connect to the site only via HTTPS for one year. | Needs review |

### Login protection

| Feature | Description | Notes |
|---|---|---|
| Limit failed login attempts | After several failed login attempts, an IP address is blocked from trying for a while. Allowed IPs (firewall) are never locked out. | Recommended, Configurable |
| Uniform login error message | The same message is shown for a wrong username and a wrong password, so valid usernames aren't exposed. | Recommended |
| Custom login page URL | The login page is moved to your secret URL and wp-login.php and wp-admin return «404» for logged-out users. If you get locked out, add this line to wp-config.php: define( 'MB_DISABLE_LOGIN_URL', true ); | Needs review, Configurable |

### Firewall

| Feature | Description | Notes |
|---|---|---|
| Check attack patterns | Request parameters are checked for signs of XSS, SQL Injection and path traversal, and suspicious requests are blocked. Administrators and author users are exempt. | Needs review |
| Check uploaded files | Files with executable extensions (even double extensions like .php.jpg) or containing PHP code are blocked. | Recommended |
| Block vulnerability scanners | Requests with an empty User-Agent or belonging to known attack tools (sqlmap, nikto, wpscan, etc.) are rejected. | Recommended |
| Check form same-origin | Comment form and login submissions from other sites are rejected. |  |
| Restrict HTTP methods | Only GET, POST, HEAD and OPTIONS are allowed (for REST: PUT, PATCH and DELETE as well). |  |
| Request rate limit | If an IP sends too many requests in a short period, it is temporarily blocked with a 429 error. Logged-in users are not limited. | Needs review, Configurable |

## Domain blocking

Cut off site and browser requests to filtering or slowing domains, or allow only approved domains.

### Mode & lists

| Feature | Description | Notes |
|---|---|---|
| Request control mode | In «Blacklist» mode, only listed domains are blocked. In «Whitelist» mode, everything except whitelisted domains is blocked (useful for international internet outages). Requests to the site itself are never blocked. | Needs review, Selectable |
| Domain blacklist | One domain per line. Subdomains are included too. You can fill the list using the quick-add buttons or the site scan result. |  |
| Domain whitelist | In whitelist mode, only these domains are allowed. One domain per line. |  |
| Automatically learn external domains | During normal visits to the site's pages (and the admin), the plugin occasionally identifies external domains and saves them in «Suggestions». No manual scan is needed and it puts no load on the server, since it only samples a small portion of visits. | Recommended |
| Domain suggestions | External domains found automatically, ordered by frequency. Add any of them to the blacklist with one click. |  |

### How blocking works

| Feature | Description | Notes |
|---|---|---|
| Remove blocked external files from the page | CSS, JavaScript and iframe files coming from a blocked domain are removed from the page code (site and admin) so the page doesn't wait for them. | Recommended |
| Block browser requests | Requests that the page's JavaScript sends to blocked domains (fetch and XHR) and tags added after loading are also blocked. |  |
| Block Mixpanel | The Mixpanel analytics service loaded by some plugins is blocked in every mode. |  |

### Stability & privacy

| Feature | Description | Notes |
|---|---|---|
| Stop sending WordPress data and news | Requests to WordPress's stats, news and events services are stopped and news widgets are removed. | Recommended |
| Stop update checks from the external server | Checking for new versions of the core, plugins and themes from the WordPress server is stopped so the admin doesn't hang. | Needs review |
| Limit timeout for external requests | If a service responds slowly, it won't be waited on longer than this period, so the site and admin don't slow down. | Recommended, Configurable |

## Menu hiding

Hide admin and admin bar menus and submenus; the list updates automatically as new plugins are installed.

### Admin sidebar

| Feature | Description | Notes |
|---|---|---|
| Menus and submenus to hide | Check a box to hide a menu or submenu. A newly installed plugin automatically appears in this list. Note: hiding only removes the menu; direct access by URL is not blocked. |  |

### Admin bar

| Feature | Description | Notes |
|---|---|---|
| Admin bar items to hide | Hide top bar items (such as My Sites, Comments and the Plugins menu). The list updates automatically with installed plugins. |  |
