# Disable Bloat Manager
## مدیریت کارایی وردپرس | WP Performance Manager
---

<div dir="rtl">

## فارسی

**مدیریت کارایی وردپرس** مجموعه‌ای دسته‌بندی‌شده برای خاموش‌کردن بخش‌های اضافه‌ی وردپرس، ووکامرس و افزونه‌هاست؛ همراه با ابزارهای امنیت و کنترل ارتباط سایت با دامنه‌های بیرونی. هر امکان یک راهنمای ساده دارد (با رفتن روی علامت «؟») و رابط کاملاً فارسی و راست‌به‌چپ است.

### نصب
۱. آخرین نسخه ([`disable-bloat-manager-v2.1.zip`](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)) را دانلود و از بخش **افزونه‌ها ← افزودن ← بارگذاری افزونه** نصب و فعال کنید.
۲. از منوی **مدیریت کارایی** یا لینک «تنظیمات» کنار نام افزونه در صفحه‌ی افزونه‌ها وارد شوید.
۳. از **نمای کلی** روی «تنظیمات پیشنهادی» بزنید تا امکان‌های کم‌خطر روشن شوند، سپس هر بخش را بررسی کنید.

> **مهم:** قبل از استفاده روی سایت اصلی، روی یک سایت آزمایشی امتحان کنید و از سایت پشتیبان بگیرید. امکان‌های برچسب‌خورده با «نیازمند بررسی» ممکن است ظاهر یا کارکرد سایت را عوض کنند.

### راهنمای استفاده
- **جستجو:** در هدر بالا (کلید `/` هم کار می‌کند) هر امکان را پیدا کنید.
- **فعال‌سازی همه:** دکمه‌ی روبه‌روی عنوان هر بخش همه‌ی کلیدهای آن بخش را روشن یا خاموش می‌کند (قبل از روشن‌کردن، هشدار می‌دهد).
- **هشدار تداخل:** اگر افزونه‌ی دیگری (مثل Perfmatters، WP Rocket یا Disable Bloat) همان امکان را اجرا کند، کنار امکان نشان داده می‌شود.
- **گزینه‌های وابسته به افزونه:** اگر افزونه‌ی مربوطه (مثل ووکامرس یا المنتور) نصب نباشد، گزینه‌هایش دیده می‌شود ولی غیرفعال است.
- **حالت تیره/روشن:** با آیکن بالای صفحه.
- **ریست:** در «نمای کلی» دکمه‌ی «بازنشانی تنظیمات» همه‌چیز را به حالت اولیه برمی‌گرداند.
- اگر با «آدرس سفارشی صفحه‌ی ورود» قفل شدید، در `wp-config.php` این خط را اضافه کنید: `define( 'MB_DISABLE_LOGIN_URL', true );`

### بخش‌ها و امکانات
| بخش | چه کاری می‌کند |
|---|---|
| سمت بازدیدکننده | حذف ایموجی، لینک‌ها و برچسب‌های اضافه‌ی سربرگ، اسکریپت‌های بلااستفاده، فید و نقشه‌ی سایت |
| دیدگاه‌ها | بستن کامل یا جزئی دیدگاه، پینگ‌بک، Gravatar و پاک‌سازی خودکار اسپم |
| پیشخوان | اعلان‌ها، ویجت‌ها، نوار بالا، Heartbeat، نسخه‌های پیشین، صفحه‌ی ورود |
| ویرایشگر بلوک | غیرفعال‌سازی گوتنبرگ و بخش‌های اضافه‌ی آن |
| ابزارک‌ها | حذف همه یا برخی ابزارک‌های هسته |
| به‌روزرسانی‌ها | کنترل به‌روزرسانی خودکار هسته، افزونه‌ها و قالب‌ها |
| ووکامرس | تبلیغات و ماژول‌های اضافه‌ی ووکامرس، Cart Fragments و اسکریپت‌ها |
| فونت و افزونه‌ها | حذف یا میزبانی محلی فونت گوگل؛ پنهان‌کردن تبلیغات Jetpack، المنتور، Yoast و … |
| هوش مصنوعی | خاموش‌کردن AI وردپرس (و صفحه‌ی اتصال‌دهنده‌ها) و AI افزونه‌ها |
| امنیت | سخت‌سازی، هدرهای امنیتی، محافظت از ورود، آدرس ورود سفارشی و فایروال |
| مسدودسازی دامنه‌ها | فهرست سیاه یا سفید دامنه، یادگیری خودکار دامنه‌های خارجی و قطع درخواست‌های کندکننده |
| مخفی‌سازی منوها | مخفی‌کردن منوها و زیرمنوهای پیشخوان و نوار بالا با تشخیص خودکار افزونه‌های جدید |

فهرست کامل (۱۶۱ امکان) با توضیح هر کدام: **[docs/FEATURES.md](docs/FEATURES.md)**

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

### Installation
1. Download the [latest release (`disable-bloat-manager-v2.1.zip`)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest), then upload it at **Plugins → Add New → Upload Plugin** and activate it.
2. Open the **DeBloat** menu (or the "Setting" link on the Plugins page).
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

Full Persian feature list: **[docs/FEATURES.md](docs/FEATURES.md)**.

### FAQ
- **Will it slow my site?** No. Only the options you switch on run. Domain learning samples a small share of page views.
- **Something broke after enabling an option?** Turn that option off or use Reset. If you can't reach the dashboard, rename the plugin folder via FTP.
- **Bugs and ideas:** open an [Issue](../../issues) with your WordPress version, plugin version, and steps to reproduce.

### License
The code is public to be read and audited, but **copying, forking and redistributing it is not allowed**; running it on your own sites is free. See [LICENSE](LICENSE).
