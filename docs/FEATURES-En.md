# Full feature list

> The plugin UI is Persian; this is the English version of the full feature list. Total features: **196** (v2.4.0).
> Persian version: [FEATURES-Fa.md](FEATURES-Fa.md).

## Admin dashboard

Keep the admin dashboard, login page, extra head code and site files, and WordPress updates light and clean.

### Admin dashboard speed

| Feature | Description | Notes |
|---|---|---|
| Heartbeat control | Heartbeat contacts the server every few seconds (for autosave, post locking and notifications). In the "Reduce requests" and "Edit screen only" modes you can also set the time interval. If you choose "Disabled", autosave and post locking will stop working too. | Recommended, Configurable, Selectable |
| Remove admin page transition animation | Removes the fade effect when moving from one admin page to another. |  |
| Remove admin quick search (Command Palette) | Removes the shortcut search window that opens with Ctrl+K in the admin dashboard and editor. |  |

### Dashboard home widgets

| Feature | Description | Notes |
|---|---|---|
| Remove dashboard home widgets | Removes the widgets you check from the dashboard home page; all are selected by default. WooCommerce widgets are handled separately in the WooCommerce section. | Configurable |
| Remove WooCommerce status widget from dashboard | Removes the store status box from the dashboard home page. | Requires WooCommerce |
| Remove WooCommerce setup widget | Removes the store setup guide box from the dashboard. | Requires WooCommerce |
| Remove Elementor widget from dashboard | Removes the Elementor news box from the dashboard home page. | Requires Elementor |
| Remove Yoast widget from dashboard | Removes the Yoast box from the dashboard home page. | Requires Yoast SEO |
| Remove WP Desk widgets from dashboard | Hides widgets from WP Desk plugins (invoice, checkout fields, etc.). | Requires WooCommerce |

### Admin bar & dashboard appearance

| Feature | Description | Notes |
|---|---|---|
| Remove WordPress logo from admin bar | Removes the small WordPress logo menu and its links from the site admin bar and the admin dashboard. |  |
| Remove greeting from admin bar | Removes the "Howdy" text next to the username, leaving only the name and avatar. |  |
| Remove admin footer text | Removes the "Thank you for creating with WordPress" line and the version number from the bottom of admin pages. |  |
| Remove "Screen Options" button | Hides the Screen Options button above admin tables. |  |
| Remove "Help" tab | Removes the WordPress Help tab at the top of admin pages. |  |
| Hide admin bar | Hides the admin bar at the top of the site for non-administrators or for everyone (including administrators). | Selectable |

### Login page

| Feature | Description | Notes |
|---|---|---|
| Hide WordPress logo on login page | The logo above the WordPress login form is not displayed. |  |
| Change login logo link to site home page | Instead of wordpress.org, clicking the logo takes you to your own home page. |  |
| Change login logo title to site name | The logo's tooltip text becomes your site name. |  |
| Remove language selector from login page | Removes the language selector at the bottom of the login form. |  |
| Login page background image | Sets an image from the media library as the WordPress login page background. | Configurable |

### Post saving

| Feature | Description | Notes |
|---|---|---|
| Limit post revisions | WordPress keeps a revision for every edit and the database gets heavy. With this option only the latest few revisions are kept. A value of 0 means no revisions are saved. | Recommended, Configurable |
| Change autosave interval | The normal autosave interval for drafts is 60 seconds. A larger number means fewer requests to the server. | Configurable |
| Completely disable autosave | The editor no longer autosaves drafts. Only enable this if you prefer saving manually. | Needs review |
| Shorten trash retention period | Posts sent to the trash are permanently deleted sooner than the default 30 days. | Configurable |
| Disable post tags (WordPress) | Removes post tags and their menu; suitable for sites that only use categories. | Needs review |
| Turn off WP-Cron on every visit | Instead of a real cron, WordPress checks scheduled tasks on every visit. Turning it off makes the site lighter, but you must set up a real cron on your host (for example every 5 minutes) for wp-cron.php. |  |

### Images & loading

| Feature | Description | Notes |
|---|---|---|
| Turn off WordPress default Lazy Load | WordPress lazily loads images below the fold. Only turn this off if another plugin (cache or optimizer) does it better. | Needs review |
| Turn off Speculative Loading | WordPress (6.8 and later) preloads in the browser pages you are likely to open. Turning it off reduces unnecessary traffic. |  |
| Disable image srcset | WordPress puts several image sizes in the page code for each image (srcset). Turning it off makes the page code shorter, but mobile devices may download a larger image than needed. | Needs review |

### Media

| Feature | Description | Notes |
|---|---|---|
| Prevent PDF thumbnail generation | WordPress creates several thumbnails for every PDF you upload. With this option they are not created and less hosting space is used. |  |
| Use GD for image processing | WordPress uses the GD library to create image sizes; this helps if you have problems with Imagick on your host (memory errors). |  |

### WordPress tools

| Feature | Description | Notes |
|---|---|---|
| Completely disable Site Health | Disables Site Health checks, the menu, automatic scheduling and their endpoints. | Needs review |
| Hide "Import" and "Export" | Removes the import and export pages from the Tools menu and closes direct access. | Needs review |
| Disable WordPress privacy tools | Disables the tools for exporting and erasing users' personal data. | Needs review |

### Page head

| Feature | Description | Notes |
|---|---|---|
| Remove emoji script and styles | Modern browsers display emojis themselves. This option removes the extra files WordPress loads for emojis on every page. | Recommended |
| Remove RSD tag | This tag was only needed by old remote publishing tools. If you don't publish with such a tool, it is safe to remove. | Recommended |
| Remove WLW Manifest | A file for the old Windows Live Writer software, which has not been supported for years. Removing it does not harm the site's appearance or function. | Recommended |
| Remove shortlink | WordPress puts a short URL like ?p=123 in the page code for every post. Remove it if you don't use it. |  |
| Hide WordPress version | The WordPress version is written in the site code and helps hackers find vulnerabilities of that version. This option hides it. | Recommended |
| Remove previous and next post links | Tags that introduced the previous and next post to search engines. Today they have almost no effect. |  |
| Remove DNS prefetch | WordPress tells the browser to connect to external domains early. Removing it makes the code lighter, but external files may start loading slightly later. | Needs review |
| Empty favicon for sites without one | If you haven't set a favicon for your site, browsers send an extra request and get a 404 error. This option declares an empty icon. It has no effect if you have set a favicon. |  |
| Remove REST API link from head | Removes the tag and header that advertise the REST API from the page. The REST API itself still works. | Recommended |

### Scripts & styles

| Feature | Description | Notes |
|---|---|---|
| Disable oEmbed | With this section turned off, video or tweet links in posts are not automatically converted into players, and others cannot embed your posts on their sites. | Needs review |
| Remove Dashicons for visitors | Dashicons is WordPress's admin icon set and visitors usually don't need it. It is removed only for users who are not logged in. | Needs review |
| Remove jQuery Migrate | A helper library that keeps very old jQuery code compatible. If your theme and plugins are up to date, you don't need it. | Needs review |
| Remove Gutenberg block styles | If you build your site with the classic editor or a page builder (such as Elementor), these styles are unnecessary and removing them makes the page lighter. | Needs review |
| Remove block theme global styles | The color and font code that block themes add to every page. Safe to remove if your theme is not a block theme. | Needs review |
| Remove password strength meter | A heavy script that shows password strength in registration and profile forms. Remove it if you don't need it. |  |
| Disable Google Maps | Removed wherever a theme or plugin loads Google Maps. If you have a map on your site, don't turn this on or add its page to the exceptions. | Needs review, Configurable |
| Load block styles only when used | Instead of one large stylesheet for all blocks, each block brings its own styles only when it is present on the page. |  |

### Feeds & sitemap

| Feature | Description | Notes |
|---|---|---|
| Remove feed links from head | Removes RSS feed links from the page code, but the feeds themselves still work. |  |
| Completely disable feeds | Closes feed (RSS) URLs and feed readers no longer receive your site's content. | Needs review |
| Disable WordPress default sitemap | The sitemap (wp-sitemap.xml) helps search engines find pages. If an SEO plugin generates a sitemap, this one is not needed. | Needs review |
| Remove Generator tag from feeds | Hides the WordPress version from inside the feed files. |  |

### URLs & text

| Feature | Description | Notes |
|---|---|---|
| Remove ?ver from file URLs | Removes the version number from the end of CSS and JavaScript file URLs so versions are not revealed. After a theme or plugin update, browsers may keep the old file. | Needs review |
| Turn off WordPress automatic text correction | WordPress automatically changes "Wordpress" to "WordPress" everywhere. With this option, automatic text tweaking is turned off in the sections you choose. | Configurable |

### Automatic updates

| Feature | Description | Notes |
|---|---|---|
| Turn off automatic theme updates | Themes are not updated until you do it yourself. |  |
| Turn off automatic plugin updates | Plugins are not updated until you do it yourself. |  |
| Turn off automatic core updates | WordPress does not automatically install minor or major versions. |  |
| Completely disable core update checks | WordPress no longer checks for a new version and sends no request to the WordPress servers. |  |

### Notices

| Feature | Description | Notes |
|---|---|---|
| Manage admin notices | Hide promotional notices from plugins. The "Selectable" mode adds a "Hide" button next to each notice. | Recommended, Selectable |
| Hide update notice from non-administrators | The "New WordPress version available" message is shown only to users who have permission to update. |  |
| Turn off periodic admin email verification | Removes the "Is your admin email still correct?" prompt shown every few months. |  |
| Remove privacy page reminder | Removes reminders and notices about the privacy policy page in the admin dashboard. |  |
| Remove old browser warning | Removes the "Your browser is outdated" warning box from the dashboard. |  |
| Remove new editor promo | Removes the "Try the new editor" promotional box from the dashboard. |  |
| Remove plugin suggestions on Add page | Removes the "Featured" and "Popular" tabs and the suggested list from the Add Plugins page. |  |
| Remove popular plugin tags | Hides the popular tags box on the Add Plugins page. |  |

## Comments

Limit or completely turn off the comments section.

### Comments

| Feature | Description | Notes |
|---|---|---|
| Completely disable comments | Closes comments and pingbacks site-wide and hides the Comments menu from the admin dashboard. | Needs review |
| Remove link from comment author name | The author's name becomes plain text instead of a link. This reduces spammers' incentive to post promotional comments. |  |
| Remove comment reply script | Keeps the threaded comment reply script only on pages where it is actually needed. | Recommended |
| Prevent self-pingbacks | When you link to another post on the same site in a post, WordPress notifies itself and creates a useless comment. This option prevents that. | Recommended |
| Close comments for selected post types | Closes comments only for the post types you select; others remain normal. |  |
| Automatically close comments on old posts | Closes commenting on posts published more than a set number of days ago to reduce spam. | Configurable |
| Comment pagination | Splits long comment threads into several pages to keep the page light. | Configurable |
| Disable pingbacks and trackbacks | WordPress no longer accepts or sends pingback and trackback notifications. | Recommended |
| Don't turn URLs in comment text into links | URLs that users write in comments are not automatically turned into links, reducing spam. |  |

### Avatars & privacy

| Feature | Description | Notes |
|---|---|---|
| Disable Gravatar | No request is sent to gravatar.com for user avatars; faster and more private. If left empty, a simple avatar is shown. | Recommended, Configurable |
| Automatically purge spam comments | Spam comments that have been in the spam folder for more than the selected number of days are permanently deleted automatically. | Configurable |

### Anti-spam

| Feature | Description | Notes |
|---|---|---|
| Remove "Website" field from comment form | Removes the website URL field from the comment form; spammers no longer have a place to put links. | Recommended |
| Bot trap (Honeypot and time) | A hidden field plus a form submission time check rejects bot comments without a captcha. Works even if the page is cached. | Recommended |
| Persian names in comments | The comment author's name must be in Persian (Persian letters and spaces). Suitable for fully Persian sites. | Needs review |

## Block editor & widgets

Turn off extra sections of the Gutenberg editor and WordPress widgets.

### Block editor (Gutenberg)

| Feature | Description | Notes |
|---|---|---|
| Disable Gutenberg | Turns off the block editor (Gutenberg) and replaces it with the classic editor. Suitable for sites using a page builder. | Needs review |

### Extra sections

| Feature | Description | Notes |
|---|---|---|
| Return to the old widgets editor | The Widgets screen is displayed in the classic form. |  |
| Automatically close the welcome guide | The tutorial window shown on first entry to the editor does not open. |  |
| Remove external block directory | Removes searching for plugin blocks from the internet in the editor. | Recommended |
| Remove core patterns | Removes WordPress default patterns from the editor. |  |
| Prevent fetching patterns from the internet | WordPress does not fetch ready-made patterns from an external server; the editor opens faster. | Recommended |
| Automatically exit fullscreen mode | The editor opens with the admin menu instead of fullscreen. |  |
| Disable template editor | Removes template editing from inside the block editor. |  |

### Widgets

| Feature | Description | Notes |
|---|---|---|
| Disable all core widgets | Disables all default WordPress widgets (search, archives, categories, etc.). | Needs review |
| Disable selected widgets | Only the widgets you check are removed. |  |
| Completely remove widget support | Removes the Widgets menu from the admin dashboard and the theme no longer has a widget sidebar. Only for sites without a sidebar. | Needs review |

### Gutenberg blocks

| Feature | Description | Notes |
|---|---|---|
| Disable selected Gutenberg blocks | The blocks you select are removed from the editor. The list updates automatically with installed plugins. Existing content containing these blocks is still displayed. |  |

## WooCommerce

Control WooCommerce ads, extra sections and heavy scripts.

### WooCommerce admin

| Feature | Description | Notes |
|---|---|---|
| Disable WooCommerce Admin | Turns off the new, heavy WooCommerce interface (analytics, dashboard, etc.) and keeps the classic admin dashboard. | Needs review, Requires WooCommerce |
| Hide WooCommerce "Home" | Removes the WooCommerce "Home" page from the menu and directs the entry point to orders. | Requires WooCommerce |
| Remove marketing section | Removes the WooCommerce marketing menu and its suggestions. | Recommended, Requires WooCommerce |
| Remove WooCommerce.com notices | Hides notices about connecting a WooCommerce.com account and subscription renewals. | Recommended, Requires WooCommerce |
| Remove marketplace suggestions | Suggested WooCommerce plugins are not shown on product and settings pages. | Recommended, Requires WooCommerce |
| Remove Plugins and Subscriptions submenu | Removes the "Plugins" and "My Subscriptions" submenus from the WooCommerce menu. | Requires WooCommerce |
| Remove shipping method suggestions | Removes the shipping plugin suggestion box from settings. | Requires WooCommerce |
| Remove payment gateway suggestions | Removes the payment gateway suggestion box from settings. | Requires WooCommerce |
| Remove marketplace link from checkout page | Hides the "Add a payment method" link to the marketplace on the Payments page. | Requires WooCommerce |
| Turn off new product editor | The product editing screen is displayed in the classic form. | Requires WooCommerce |
| Remove WooCommerce blocks in admin | Removes WooCommerce block styles in the editor. | Needs review, Requires WooCommerce |

### Emails

| Feature | Description | Notes |
|---|---|---|
| Turn off WooCommerce guide emails | WooCommerce educational and promotional emails are not sent to the admin. | Requires WooCommerce |
| Remove "Get the app" line from emails | Removes the WooCommerce app promo text from the bottom of emails. | Requires WooCommerce |

### Store (visitor side)

| Feature | Description | Notes |
|---|---|---|
| Load WooCommerce only on store pages | WooCommerce scripts and styles are not loaded on regular pages and posts. | Needs review, Requires WooCommerce |
| Turn off Cart Fragments | Removes the heavy mini-cart update request on every page. | Needs review, Requires WooCommerce |
| Remove WooCommerce block styles | Removes WooCommerce block styles on the visitor side. | Needs review, Requires WooCommerce |
| Disable WooCommerce widgets | Removes cart, price filter and other widgets. | Requires WooCommerce |
| Disable product reviews | Turns off product ratings and reviews. | Needs review, Requires WooCommerce |
| Remove WooCommerce password strength meter | Removes the heavy password strength script from the account page. | Requires WooCommerce |
| Load Stripe only on required pages | Stripe scripts are loaded only on product, cart and checkout pages. | Requires Stripe gateway |
| Disable default WooCommerce styles | All default WooCommerce stylesheets are not loaded. Suitable for themes (such as Hello Elementor) that build the store appearance themselves. | Needs review, Requires WooCommerce |
| Hide product SKU | The product SKU is not displayed to visitors on the product page and in lists. | Requires WooCommerce |
| Disable product tags | Removes WooCommerce product tags and their menu. | Needs review, Requires WooCommerce |

### Coupons

| Feature | Description | Notes |
|---|---|---|
| Disable coupons | Removes the ability to use coupons in the cart and checkout, and the "Coupons" menu. | Needs review, Requires WooCommerce |

### Checkout & payment

| Feature | Description | Notes |
|---|---|---|
| Remove checkout form fields | The selected fields are removed from the checkout form to make purchasing shorter. | Needs review, Requires WooCommerce, Configurable |
| Place order without payment step | Removes the payment selection and payment step from checkout and places the order without online payment (suitable for orders by phone or in-person payment). | Requires WooCommerce |

## Fonts & plugins

Take care of Google Fonts and Elementor first; then hide ads and extra sections of popular plugins. If a plugin is not installed, its options are shown as disabled.

### Google Fonts

| Feature | Description | Notes |
|---|---|---|
| Remove Google Fonts | Removes all styles, links and @import related to fonts.googleapis.com from the page (also in Elementor). | Needs review |
| Self-host Google Fonts | Google Fonts used by the theme are saved once on your own host and no request is sent to Google's servers anymore. | Recommended |
| Add font-display: swap | Until the font loads, text is displayed with a fallback font so visitors don't see a blank page. | Recommended |
| Load Font Awesome asynchronously | The Font Awesome stylesheet loads without blocking page rendering. |  |
| Remove Elementor Google Fonts | Elementor no longer fetches Google Fonts from the internet. | Requires Elementor |

### Elementor

| Feature | Description | Notes |
|---|---|---|
| Hide Elementor "Home" | Removes the Elementor home page from the menu and directs the entry point to templates. | Requires Elementor |
| Disconnect from Elementor account | Stops requests to connect to Elementor account and cloud services (online templates, license). | Needs review, Requires Elementor |
| Remove e_search_props parameter from URLs | Elementor adds a parameter to search result URLs that creates duplicate URLs and extra cache. With this option, visitors are redirected to the URL without the parameter. |  |

### Jetpack

| Feature | Description | Notes |
|---|---|---|
| Remove Jetpack ads and notices | Hides promotional messages and Jetpack installation suggestions. | Recommended, Requires Jetpack |
| Disable Blaze | The Blaze advertising tool is not displayed in the admin dashboard. | Requires Jetpack |

### Yoast SEO

| Feature | Description | Notes |
|---|---|---|
| Remove premium version ads | Hides premium promotional notices and menu. | Recommended, Requires Yoast SEO |
| Remove Yoast menu from admin bar | Removes the Yoast menu from the admin bar. | Requires Yoast SEO |
| Remove Yoast HTML comment from page | Removes the promotional note inside the page code. | Recommended, Requires Yoast SEO |

### Contact Form 7

| Feature | Description | Notes |
|---|---|---|
| Remove Contact Form 7 script and styles | Removes the plugin files from all pages. Only enable this if you have no forms or you load them yourself on the form page. | Needs review, Requires Contact Form 7 |
| Smart loading of Contact Form 7 | The form script and styles are loaded only on pages that contain the form shortcode. Better than complete removal. | Recommended, Requires Contact Form 7 |

### ACF & others

| Feature | Description | Notes |
|---|---|---|
| Hide Advanced Custom Fields menu | Removes the ACF menu from the admin dashboard (fields still work). | Requires Advanced Custom Fields |
| Remove WPML plugin Generator tag | Hides the WPML version from the site code. | Requires WPML |
| Remove UpdraftPlus from admin bar | Removes the UpdraftPlus menu from the admin bar. | Requires UpdraftPlus |
| Remove SkyVerge menu | Hides the SkyVerge dashboard menu that comes with some WooCommerce plugins. | Requires WooCommerce |
| Remove Flexible Shipping "Shipping plugins" submenu | Removes the suggested shipping plugins submenu from WooCommerce. | Requires WooCommerce |

## AI

Turn off AI features of WordPress and plugins.

### WordPress

| Feature | Description | Notes |
|---|---|---|
| Disable WordPress AI | Turns off WordPress's built-in AI features and removes the Connectors page from the Settings menu. | Recommended |

### Plugins

| Feature | Description | Notes |
|---|---|---|
| Disable Elementor AI | Elementor's AI buttons and tools are not displayed in the editor. | Requires Elementor |
| Disable Elementor MCP | Turns off connecting external AI tools to Elementor (MCP). | Requires Elementor |
| Disable Rank Math AI | Removes the Content AI tool in Rank Math. | Requires Rank Math |
| Disable Yoast AI | Removes AI text generation buttons in Yoast. | Requires Yoast SEO |
| Disable Jetpack AI | Turns off the AI assistant in Jetpack. | Requires Jetpack |

## Security

WordPress hardening, login protection and firewall, all in one place.

### WordPress hardening

| Feature | Description | Notes |
|---|---|---|
| Disable XML-RPC | Closes the old xmlrpc.php endpoint that hackers abuse for password guessing and pingback attacks. | Recommended |
| Prevent username discovery | Blocks ?author=1 probing, user lists in REST and the sitemap, and the author name in oEmbed for guests. | Recommended |
| Disable author page | Redirects author archive URLs to the home page. | Needs review |
| Disable theme and plugin file editor | Closes direct code editing from the admin dashboard so malicious code cannot be injected if an admin account is stolen. | Recommended |
| Disable application passwords | Turns off the ability to create Application Passwords for REST access. |  |
| Close REST batch endpoint for guests | Allows REST batch requests only for logged-in users. | Recommended |

### REST API access

| Feature | Description | Notes |
|---|---|---|
| Disable REST API | The REST API is how apps, the block editor and plugins read and change site data via the /wp-json endpoint. You can keep access for administrators only or only for logged-in users. Contact Form 7 forms and the WooCommerce store are exempt. | Selectable |

### Security headers

| Feature | Description | Notes |
|---|---|---|
| Send security headers | Sends X-Content-Type-Options, X-Frame-Options, Referrer-Policy and Permissions-Policy headers for browser protection. Headers already set are not replaced. | Recommended |
| Enable HSTS | Tells the browser to connect to the site over HTTPS only for one year. |  |

### Login protection

| Feature | Description | Notes |
|---|---|---|
| Limit failed login attempts | After several wrong login attempts, an IP address is blocked from trying for a while. Allowlisted IPs (firewall) are never locked out. | Recommended, Configurable |
| Same error message on login | Shows the same message for a wrong username and a wrong password so valid usernames are not revealed. | Recommended |
| Custom login page URL | Moves the login page to your secret URL and makes wp-login.php and wp-admin return "404" for logged-out visitors. If you get locked out, add this line to wp-config.php: define( 'MB_DISABLE_LOGIN_URL', true ); | Configurable |

### Firewall

| Feature | Description | Notes |
|---|---|---|
| Check attack patterns | Checks request parameters for XSS, SQL Injection and path traversal signatures and blocks suspicious requests. Administrators and author users are exempt. |  |
| Check uploaded files | Blocks files with executable extensions (even double extensions like .php.jpg) or containing PHP code. | Recommended |
| Block vulnerability scanners | Rejects requests with an empty User-Agent or from known attack tools (sqlmap, nikto, wpscan, etc.). | Recommended |
| Check form same-origin | Rejects comment form and login submissions from other sites. |  |
| Restrict HTTP methods | Only GET, POST, HEAD and OPTIONS are allowed (for REST: PUT, PATCH and DELETE as well). |  |
| Request rate limit | If an IP sends too many requests in a short interval, it is temporarily blocked with a 429 error. Logged-in users are not limited. | Configurable |

## Domain blocking

Cut off site and browser requests to tracking or slow domains, or allow only permitted domains.

### How blocking works

| Feature | Description | Notes |
|---|---|---|
| Remove blocked external files from the page | Removes CSS, JavaScript and iframe files coming from a blocked domain from the page code (site and admin) so the page doesn't wait for them. | Recommended |
| Block browser requests | Also blocks requests the page's JavaScript sends to blocked domains (fetch and XHR) and tags added after page load. |  |
| Block Mixpanel | Blocks the Mixpanel analytics service loaded by some plugins in all cases. |  |

### Stability & privacy

| Feature | Description | Notes |
|---|---|---|
| Stop WordPress data and news requests | Stops requests to WordPress stats, news and events services and removes news widgets. | Recommended |
| Stop update checks from external server | Stops checking for new core, plugin and theme versions on the WordPress server so the admin dashboard doesn't stall. |  |
| Limit external request timeout | If a service responds slowly, the site won't wait longer than this period so the site and admin dashboard don't slow down. | Recommended, Configurable |

### Mode & lists

| Feature | Description | Notes |
|---|---|---|
| Request control mode | In "Blacklist" mode, only domains in the list are blocked. In "Whitelist" mode, everything except whitelisted domains is blocked (useful when international internet access is cut off). Requests to the site itself are never blocked. | Selectable |
| Domain blacklist | One domain per line. Subdomains are included too. You can fill the list using the quick-add buttons or the site scan result. |  |
| Domain whitelist | In whitelist mode, only these domains are allowed. One domain per line. |  |
| Automatic learning of external domains | During normal visits (and the site's own requests to external services), the plugin detects external domains and saves them in "Suggestions". No manual scan is needed and it puts no load on the server. | Recommended |
| Domain suggestions | Automatically discovered external domains, ordered by frequency. Add any of them to the blacklist with one click. |  |

## Menu hiding

Hide admin dashboard and admin bar menus and submenus; the list updates automatically when new plugins are installed.

### Admin sidebar

| Feature | Description | Notes |
|---|---|---|
| Menus and submenus to hide | Check a menu or submenu to hide it. A newly installed plugin automatically appears in this list. Note: hiding only removes the menu; direct URL access is not blocked. |  |

### Admin bar

| Feature | Description | Notes |
|---|---|---|
| Admin bar items to hide | Hide admin bar items (such as My Sites, Comments and the plugins menu). The list updates automatically with installed plugins. |  |

## Special features

In addition to turning off extra sections, add several useful features: duplicate, export, subscript font, color palette and more.

### Editing & content

| Feature | Description | Notes |
|---|---|---|
| Duplicate posts, pages and other content | Adds a "Duplicate" link next to each item in the list and creates a copy as a draft (along with categories, tags and custom fields). | Configurable |
| Duplicate menus | Adds a "Duplicate Menu" button on the Menus page and creates a full copy of the menu (with submenus). |  |
| Featured image column in lists | Adds a small column with the featured image in the posts, pages and products lists. | Configurable |
| Add categories to pages | WordPress pages get categories just like posts. |  |
| Custom classic editor height | Sets the height of the writing box in the classic editor. | Configurable |
| Elementor templates in the classic editor | Adds an "Elementor Template" dropdown to the classic editor so you can insert a template shortcode with one click. |  |
| Publish and update without reload (Ajax) | In the classic editor, the Publish/Update button saves without refreshing the page and changes the browser URL to the edit screen. | Configurable |
| Single XML export of a post or page | Adds an "Export XML" link next to each post or page and downloads only that item (in standard WordPress format). | Configurable |

### Media

| Feature | Description | Notes |
|---|---|---|
| Media display: infinite scroll or pagination | In "Infinite scroll" mode, media loads as you scroll down; in "Load more button" mode, on click. In list view, the number below sets the number per page. | Configurable, Selectable |

### Site & Elementor

| Feature | Description | Notes |
|---|---|---|
| Subscript font on the site | The subscript font (bundled with the plugin and loaded from your own host) is applied to selected parts of the site. | Configurable |
| Continue reading | Saves the visitor's reading position in each post in their browser and returns them to the same place next time. |  |
| Add source when copying text | When someone copies text from your site, a source line (page title and link) is added to the end. Text shorter than the set limit is not changed. | Configurable |
| Make Elementor section, container and column clickable | Adds a "Full link" field in the "Advanced" tab of every Elementor section, container and column to make the whole area clickable. |  |
| Iranian mobile validation in Elementor Pro forms | Phone fields in Elementor forms accept only Iranian mobile numbers (09xxxxxxxxx) and Persian digits are converted to English automatically. |  |
| Iranian mobile validation in Gravity Forms | Phone fields in Gravity Forms accept only Iranian mobile numbers and Persian digits are converted to English. |  |

### Admin dashboard

| Feature | Description | Notes |
|---|---|---|
| Custom admin panel color palette | Adds a color scheme named "My Palette" to the profile page with 4 colors you choose yourself: menu background, accent color, notice color and link color. After saving, select it under "Profile ← Color Scheme". | Configurable |
| Logo column in plugins list | Shows each plugin's icon next to its name on the Plugins page (for plugins without an icon, the first letter of the name). |  |
| Performance management widget on dashboard | Shows a summary of active features, the number of requests and blocked attacks, and plugin update status on the dashboard home page. |  |
