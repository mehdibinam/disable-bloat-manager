# Disable Bloat Manager
## مدیریت کارایی وردپرس | WP Performance Manager

<div align="center">

![Version](https://img.shields.io/badge/version-2.2.0-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4)
![License](https://img.shields.io/badge/license-Source--Available-orange)
[![Download](https://img.shields.io/badge/download-v2.2.0-brightgreen)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)

</div>

<div dir="rtl">
## فارسی

**مدیریت کارایی وردپرس** مجموعه‌ای دسته‌بندی‌شده برای خاموش‌کردن بخش‌های اضافه‌ی وردپرس، ووکامرس و افزونه‌هاست؛ همراه با ابزارهای امنیت و کنترل ارتباط سایت با دامنه‌های بیرونی. هر امکان یک راهنمای ساده دارد (با رفتن روی علامت «؟») و رابط کاملاً فارسی و راست‌به‌چپ است.

<img width="1577" height="873" alt="disable-bloat-manager" src="https://github.com/user-attachments/assets/7bf67285-04d2-413a-a7a7-41e93c4970bc" />

### ایدهٔ اولیه
ایدهٔ اولیهٔ این پروژه با الهام از افزونه های زیر شکل گرفت:

[Bad Domain Blocker](https://shetabwp.com/bad-domain-blocker/), Bloatoff Utils, Disable Admin Notices, [Disable Bloat Pro](https://disablebloat.com/), Disable everything, [Featherweight](https://wordpress.org/plugins/wp-disable/), [ghateino](https://github.com/shokrino/ghateino), [Perfmatters](https://perfmatters.io/), wp widget disable.

### مزیت اصلی
- **کاهش تعداد درخواستها و حجم صفحه:** فقط امکانهایی که خودتان روشن میکنید اجرا میشوند و هیچ کد اضافهای بارگذاری نمیشود.
- **رفتار قابلپیشبینیتر سایت:** با قطع تماسهای بیرونی (فونتهای گوگل، Mixpanel، تله متری وردپرس) صفحه کمتر منتظر سرویسهای خارجی میماند.
- **وابستگی کمتر به CDN و سرویسهای خارجی:** در شرایط اینترنت ناپایدار یا محدود، سایت باز هم کار میکند.
- **عیبزدایی سادهتر:** تولتیپ هر امکان، هشدار تداخل با سایر افزونهها و پیام هشدار قبل از تغییرات حساس.

### رویکرد
همهچیز بهصورت پیشفرض خاموش است (Minimal by default)؛ شما تعیین میکنید چه چیزی اجرا شود. امکانهای حساس قبل از فعالسازی هشدار میدهند و هیچ تغییری بهصورت خودکار در سایت اعمال نمیشود.

### سناریوهای پیشنهادی
- **عمومی (ایمن):** دکمه «تنظیمات پیشنهادی» را بزنید تا امکانهای کمخطر روشن شوند.
- **اینترنت محدود:** حالت «فهرست سفید» دامنهها را انتخاب و دامنههای ضروری (درگاه پرداخت، پیامک، نقشه) را وارد کنید.
- **عیبزدایی:** هشدارهای تداخل را بررسی کنید، یادگیری دامنهها را موقتاً خاموش و در صورت لزوم از «بازنشانی» استفاده کنید.

### نکات سازگاری
- گزینههایی مثل «آدرس ورود سفارشی»، HSTS و فایروال را اول روی سایت آزمایشی امتحان کنید.
- اگر فرمها از REST API استفاده میکنند، نامفضای آنها را در فیلد «نامفضاهای عمومی REST» وارد کنید.
- اگر نقشهای گوگل در سایت دارید، «غیرفعالسازی گوگل مپ» را روشن نکنید یا صفحهی آن را در استثناها بنویسید.
- در صورت قفلشدن پیشخوان، پوشهی افزونه را موقتاً تغییر نام دهید یا خط `MB_DISABLE_LOGIN_URL` را به `wp-config.php` اضافه کنید.

### تازه های نسخه ۲٫۲
- منوی افزونه به **تنظیمات ← مدیریت کارایی** منتقل شد و سایدبار ساده تر شد.
- ویرایشگر بلوک و ابزارک ها در یک بخش ادغام شدند.
- فیلدهای عددی با دکمه های + و −، چیدمان دو ستونه با ارتفاع هم اندازه و دکمه «پاک کردن فهرست» برای فهرست سیاه و سفید.
- شناسایی کامل موارد نوار بالا (لوگوی وردپرس، زیرمنوها، دیدگاه ها، به روز رسانی ها و ...) و تشخیص منوهای هم نام.
- فهرست اعلان های مخفی شده با بازگردانی تکی، دریافت و وارد کردن تنظیمات (JSON) و جستجوی دوزبانه (فارسی و انگلیسی).
- فونت گوگل و المنتور در بالای بخش «فونت و افزونه ها» قرار گرفتند.
### نصب
۱. آخرین نسخه ([`disable-bloat-manager-v2.2.zip`](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)) را دانلود و از بخش **افزونه‌ها ← افزودن ← بارگذاری افزونه** نصب و فعال کنید.
۲. از **تنظیمات ← مدیریت کارایی** یا لینک «تنظیمات» کنار نام افزونه در صفحه ی افزونه ها وارد شوید.
۳. از **نمای کلی** روی «تنظیمات پیشنهادی» بزنید تا امکان‌های کم‌خطر روشن شوند، سپس هر بخش را بررسی کنید.

> **مهم:** قبل از استفاده روی سایت اصلی، روی یک سایت آزمایشی امتحان کنید و از سایت پشتیبان بگیرید. امکان‌های برچسب‌خورده با «نیازمند بررسی» ممکن است ظاهر یا کارکرد سایت را عوض کنند.

### راهنمای استفاده
- **جستجو:** در هدر بالا (کلید `/` هم کار می‌کند) هر امکان را پیدا کنید.
- **فعال‌سازی همه:** دکمه‌ی روبه‌روی عنوان هر بخش همه‌ی کلیدهای آن بخش را روشن یا خاموش می‌کند (قبل از روشن‌کردن، هشدار می‌دهد).
- **هشدار تداخل:** اگر افزونه‌ی دیگری (مثل Perfmatters، WP Rocket یا Disable Bloat) همان امکان را اجرا کند، کنار امکان نشان داده می‌شود.
- **گزینه‌های وابسته به افزونه:** اگر افزونه‌ی مربوطه (مثل ووکامرس یا المنتور) نصب نباشد، گزینه‌هایش دیده می‌شود ولی غیرفعال است.
- **ریست:** در «نمای کلی» دکمه‌ی «بازنشانی تنظیمات» همه‌چیز را به حالت اولیه برمی‌گرداند.
- اگر با «آدرس سفارشی صفحه‌ی ورود» قفل شدید، در `wp-config.php` این خط را اضافه کنید: `define( 'MB_DISABLE_LOGIN_URL', true );`

### بخش‌ها و امکانات

<table dir="rtl">
<thead>
<tr><th>بخش</th><th>چه کاری می‌کند</th></tr>
</thead>
<tbody>
<tr><td>سمت بازدیدکننده</td><td>حذف ایموجی، لینک‌ها و برچسب‌های اضافه‌ی سربرگ، اسکریپت‌های بلااستفاده، فید و نقشه‌ی سایت</td></tr>
<tr><td>دیدگاه‌ها</td><td>بستن کامل یا جزئی دیدگاه، پینگ‌بک، Gravatar و پاک‌سازی خودکار اسپم</td></tr>
<tr><td>پیشخوان</td><td>اعلان‌ها، ویجت‌ها، نوار بالا، Heartbeat، نسخه‌های پیشین، صفحه‌ی ورود</td></tr>
<tr><td>ویرایشگر بلوک</td><td>غیرفعال‌سازی گوتنبرگ و بخش‌های اضافه‌ی آن</td></tr>
<tr><td>ابزارک‌ها</td><td>حذف همه یا برخی ابزارک‌های هسته</td></tr>
<tr><td>به‌روزرسانی‌ها</td><td>کنترل به‌روزرسانی خودکار هسته، افزونه‌ها و قالب‌ها</td></tr>
<tr><td>ووکامرس</td><td>تبلیغات و ماژول‌های اضافه‌ی ووکامرس، Cart Fragments و اسکریپت‌ها</td></tr>
<tr><td>فونت و افزونه‌ها</td><td>حذف یا میزبانی محلی فونت گوگل؛ پنهان‌کردن تبلیغات Jetpack، المنتور، Yoast و …</td></tr>
<tr><td>هوش مصنوعی</td><td>خاموش‌کردن AI وردپرس (و صفحه‌ی اتصال‌دهنده‌ها) و AI افزونه‌ها</td></tr>
<tr><td>امنیت</td><td>سخت‌سازی، هدرهای امنیتی، محافظت از ورود، آدرس ورود سفارشی و فایروال</td></tr>
<tr><td>مسدودسازی دامنه‌ها</td><td>فهرست سیاه یا سفید دامنه، یادگیری خودکار دامنه‌های خارجی و قطع درخواست‌های کندکننده</td></tr>
<tr><td>مخفی‌سازی منوها</td><td>مخفی‌کردن منوها و زیرمنوهای پیشخوان و نوار بالا با تشخیص خودکار افزونه‌های جدید</td></tr>
</tbody>
</table>


فهرست کامل (۱۶۱ امکان) با توضیح هر کدام: **[docs/FEATURES-Fa.md](docs/FEATURES-Fa.md)**

### پرسش‌های متداول
- **افزونه سایتم را کند نمی‌کند؟** خیر؛ فقط تنظیمات انتخابی اجرا می‌شود. «یادگیری دامنه‌ها» فقط از بخش کوچکی از بازدیدها نمونه می‌گیرد.
- **بعد از روشن‌کردن گزینه‌ای سایت خراب شد؟** همان گزینه را خاموش کنید یا از «بازنشانی» استفاده کنید. اگر وارد پیشخوان نمی‌شوید، پوشه‌ی افزونه را موقتاً تغییر نام بدهید.
- **مشکل یا پیشنهاد دارم:** در بخش [Issues](../../issues) گیت‌هاب ثبت کنید و نسخه‌ی وردپرس، نسخه‌ی افزونه و مراحل بروز مشکل را بنویسید.

### مجوز
کد برای مطالعه و گزارش مشکل عمومی است، اما **کپی، فورک و توزیع آن مجاز نیست**؛ استفاده روی سایت‌های خودتان آزاد است. متن کامل در [LICENSE](LICENSE).

</div>

---

<a id="english"></a>

## English

**WordPress Performance Manager** (Persian UI, RTL) is a categorized toolbox for switching off unnecessary parts of WordPress, WooCommerce and popular plugins, plus security hardening and control over which external domains your site talks to. Every option has a short plain-language tooltip (hover the "?" icon).

### Initial inspiration
The initial idea for this project was inspired by these plugins: bad domain blocker, bloatoff utils, disable admin notices, Disable Bloat for WP & Woo Pro, disable everything, Featherweight, [ghateino](https://github.com/shokrino/ghateino), perfmatters, wp widget disable.

### Key advantage
- **Fewer requests and a lighter page:** only the options you switch on run — no extra code is loaded.
- **More predictable behavior:** by cutting external calls (Google Fonts, Mixpanel, WordPress telemetry) pages spend less time waiting on third-party services.
- **Less dependency on CDNs and external services:** on unstable or restricted connections, the site keeps working.
- **Easier troubleshooting:** every option has a tooltip, overlap warnings flag conflicting plugins, and risky changes show a confirmation warning.

### Approach
Everything is off by default (minimal by default) — you decide what runs. Sensitive options warn before enabling, and nothing changes on your site automatically.

### Recommended scenarios
- **General (safe):** click "Recommended settings" to enable the low-risk options.
- **Restricted internet:** switch domain control to "Whitelist" mode and add essential domains (payment gateways, SMS, maps).
- **Troubleshooting:** check overlap warnings, temporarily turn off domain learning, and use Reset if needed.

### Compatibility notes
- Test options like the custom login URL, HSTS and the firewall on a staging site first.
- If your forms use the REST API, add their namespaces to the "Public REST namespaces" field.
- If your site uses Google Maps, don't enable "Disable Google Maps" or add that page to the exclusions.
- If you get locked out of the dashboard, temporarily rename the plugin folder or add `MB_DISABLE_LOGIN_URL` to `wp-config.php`.

### What's new in v2.2
- The menu moved to **Settings → managing performance**, with a flat sidebar.
- The block editor and widgets were merged into one section.
- Number fields with +/− steppers, an equal-height two-column layout, and a "Clear list" button for the blacklist and whitelist.
- Full admin-bar detection (WordPress logo, submenus, comments, updates, …) and detection of duplicate menu names.
- A hidden-notices list with per-item restore, settings export/import (JSON), and bilingual (Persian/English) search.
- Google Fonts and Elementor moved to the top of "Fonts & plugins".
### Installation
1. Download the [latest release (`disable-bloat-manager-v2.2.zip`)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest), then upload it at **Plugins → Add New → Upload Plugin** and activate it.
2. Open **Settings → managing performance** (or the "تنظیمات" link on the Plugins page).
3. On the **Overview** page click "Recommended settings" to enable the low-risk options, then review each section.

> **Important:** test on a staging site and keep a backup first. Options marked "needs review" can change your site's look or behavior.

### How to use it
- **Search** from the header (`/` focuses it). **Enable all / Disable all** sits opposite each section title and asks for confirmation first.
- **Overlap warnings:** if another plugin (Perfmatters, WP Rocket, Disable Bloat, Wordfence, …) already does the same thing, you'll see a badge and a warning before enabling.
- Options for plugins that aren't installed (WooCommerce, Elementor, Yoast, …) are visible but disabled.
- **Reset** and **Recommended settings** live on the Overview page. Locked out by the custom login URL? Add `define( 'MB_DISABLE_LOGIN_URL', true );` to `wp-config.php`.

### What it covers (161 options)
- **Visitor side:** emojis, head clutter, unused scripts/styles, feeds, sitemap, Google Maps, block styles.
- **Comments:** disable fully or per post type, pingbacks, Gravatar, automatic spam cleanup.
- **Dashboard:** notices (hide all / per notice), widgets (per widget), admin bar, Heartbeat, revisions, login page.
- **Block editor, Widgets, Updates:** disable Gutenberg and its extras, core widgets, automatic updates.
- **WooCommerce:** marketing/promo UI, WooCommerce Home, cart fragments, scripts on non-shop pages, reviews.
- **Fonts & plugins:** remove or self-host Google Fonts; hide promos of Jetpack, Elementor, Yoast, CF7, ACF, UpdraftPlus and more.
- **AI:** WordPress AI and Connectors page, Elementor AI/MCP, Rank Math, Yoast, Jetpack AI.
- **Security:** hardening, security headers, login protection (attempt limit, custom login URL), a lightweight firewall (attack patterns, upload checks, scanner blocking, rate limiting).
- **Domain blocking:** blacklist or whitelist mode, automatic domain learning from normal visits (sampled, light on the server), runtime blocking in the browser, telemetry/update-check blocking, request timeout cap.
- **Menu hiding:** hide admin menus/submenus and admin-bar items; newly installed plugins appear automatically.

Full English feature list: **[docs/FEATURES-En.md](docs/FEATURES-En.md)**.

### FAQ
- **Will it slow my site?** No. Only the options you switch on run. Domain learning samples a small share of page views.
- **Something broke after enabling an option?** Turn that option off or use Reset. If you can't reach the dashboard, rename the plugin folder via FTP.
- **Bugs and ideas:** open an [Issue](../../issues) with your WordPress version, plugin version, and steps to reproduce.

### License
The code is public to be read and audited, but **copying, forking and redistributing it is not allowed**; running it on your own sites is free. See [LICENSE](LICENSE).
