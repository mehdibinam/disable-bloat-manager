<div align="center">

# Disable Bloat Manager

**افزونه جامع غیرفعالسازی بلوت وردپرس، ووکامرس و افزونهها — همراه با امنیت، فایروال و مسدودسازی دامنهها**
**All-in-one WordPress, WooCommerce & plugin bloat disabler — with security, firewall & domain blocking**

![Version](https://img.shields.io/badge/version-2.0.0-blue)
![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4)
![License](https://img.shields.io/badge/license-GPLv2%2B-green)
[![Download](https://img.shields.io/badge/download-latest%20release-brightgreen)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)

[فارسی](#-فارسی) • [English](#-english)

</div>

---

## 🇮🇷 فارسی

### معرفی
Disable Bloat Manager (که پیشتر با نام Bloat Manager شناخته میشد) یک افزونه سبک و یکپارچه است که قابلیتهای چندین افزونه محبوب بهینهسازی (Perfmatters، Disable Admin Notices، Featherweight، Disable Bloat for WP & Woo و ...) را در یک رابط مدرن با تبها و تولتیپ ارائه میدهد.

**نسخه ۲٫۰** سه بخش کاملاً جدید اضافه کرده است: **امنیت و فایروال**، **مسدودسازی دامنهها** و **مخفیسازی منوها**؛ به همراه کنترل فونتهای گوگل و گزینههای تکمیلی برای دیدگاهها، ابزارکها، پیشخوان و بهروزرسانیها. با بیش از ۱۵۰ گزینه، هم سرعت سایت را بالا میبرد و هم سطح امنیت آن را بهبود میبخشد.

### امکانات

| دسته | نمونه قابلیتها |
|---|---|
| **سمت بازدیدکننده و هسته** | حذف ایموجی، Embed/oEmbed، Dashicons، jQuery Migrate، پارامتر `?ver=`، استایل Block Library و Global Styles، لینک REST و Generator، DNS Prefetch، Shortlink، RSD، WLW، فید RSS، Self Pingback، سنجش قدرت رمز، پالت فرمان، View Transitions، غیرفعالسازی گوگل مپ |
| **دیدگاهها** | بستن دیدگاهها بر اساس نوع نوشته، بستن خودکار دیدگاههای قدیمی، صفحهبندی دیدگاهها، غیرفعالسازی پینگبک/ترکبک، حذف Gravatar، پاکسازی خودکار اسپم |
| **پیشخوان** | پاکسازی داشبورد، حذف تب راهنما و Screen Options، سلامت سایت، درونریزی/برونبری، کنترل Heartbeat، محدودسازی بازبینیها، تنظیم Autosave، شخصیسازی صفحه ورود، مدیریت اعلانها، حذف لوگوی وردپرس و متن پایین پیشخوان |
| **ویرایشگر بلوک** | بازگشت به ویرایشگر کلاسیک، حذف Welcome Guide، Block Directory، الگوهای پیشفرض و راهدور، ویرایشگر قالب |
| **ابزارکها** | غیرفعالسازی همه یا انتخابی ابزارکهای هسته، حذف کامل پشتیبانی از ابزارکها |
| **بهروزرسانیها** | خاموشکردن بهروزرسانی خودکار هسته، قالبها و افزونهها، قطع کامل بررسی بهروزرسانی |
| **ووکامرس** | غیرفعالسازی Analytics و Marketing، پیشنهادهای بازارچه، Cart Fragments، اسکریپتها و استایلها در صفحات غیرفروشگاهی، ابزارکها، تبلیغات ایمیل و پیشنهاد حملونقل |
| **افزونههای جانبی** | حذف هشدار و تبلیغ Jetpack/Blaze، ویجت و Google Fonts المنتور، تبلیغ و کامنت Yoast، CSS/JS فرم Contact Form 7، منوی ACF، UpdraftPlus |
| **هوش مصنوعی** | غیرفعالسازی AI هسته وردپرس، المنتور، Rank Math، Yoast، Jetpack و Bertha |
| **فونتهای گوگل** ⭐ | حذف فونتهای گوگل، میزبانی محلی فونت، `font-display: swap`، بارگذاری ناهمگام Font Awesome، حذف فونت گوگل المنتور |
| **🔒 امنیت** ⭐ | سختسازی وردپرس (XML-RPC، کشف نام کاربری، ویرایشگر فایل، رمزهای برنامه، محدودیت REST)، هدرهای امنیتی و HSTS، محدودکردن تلاشهای ورود، پیام خطای یکسان، آدرس سفارشی صفحه ورود، فایروال (الگوهای حمله، بررسی بارگذاریها، مسدودکردن اسکنرها، محدودکردن متدها و نرخ درخواست، لیستهای IP و مسیرهای مستثنا) |
| **🌐 مسدودسازی دامنهها** ⭐ | حالت فهرست سیاه/سفید، اسکن سایت برای یافتن دامنههای خارجی، حذف فایلهای مسدودشده از صفحه، مسدودکردن درخواستهای fetch/XHR، قطع تله متری و بررسی بهروزرسانی، محدودکردن زمان انتظار |
| **مخفیسازی منوها** ⭐ | مخفی کردن منوها و زیرمنوهای پیشخوان و موارد نوار بالا، با تشخیص خودکار افزونههای تازهنصبشده |

### نصب
1. فایل [`disable-bloat-manager (cld v2).zip`](https://github.com/mehdibinam/disable-bloat-manager/releases/latest) را از بخش Release دانلود کنید.
2. پوشه `disable-bloat-manager` را در مسیر `wp-content/plugins/` قرار دهید (یا فایل zip را مستقیماً از «افزونهها ← بارگذاری افزونه» آپلود کنید).
3. افزونه را از بخش «افزونهها» فعال کنید.
4. به **منوی Disable Bloat** بروید و گزینههای دلخواه را روشن کنید.

### راهنمای استفاده
- همه گزینهها بهصورت پیشفرض **خاموش** هستند و هیچ تغییری اعمال نمیشود تا خودتان آنها را فعال کنید.
- دکمه **«تنظیمات پیشنهادی»** گزینههای ایمن و پرتأثیر را یکجا روشن میکند.
- گزینههای دارای نشان «⭐ پیشنهادی» و «⚠ نیازمند بررسی» بهتریتوان تأثیر و ریسک را نشان میدهند؛ متن هشدار قبل از فعالسازی نمایش داده میشود.
- نشانگر «؟» کنار هر گزینه توضیح کامل را نشان میدهد.
- گزینههای ووکامرس و افزونههای جانبی فقط در صورت فعال بودن افزونه مربوطه اعمال میشوند.
- پس از فعالسازی گزینههای حساس (REST API، XML-RPC، فایروال، فیدها، کامنتها، HSTS) سایت و فرمها را حتماً تست کنید.
- در صورت قفل شدن صفحه ورود، در `wp-config.php` خط `define( 'MB_DISABLE_LOGIN_URL', true );` را اضافه کنید.

### نیازمندیها
WordPress 6.0+ ‏• PHP 7.4+

### مجوز
این پروژه تحت مجوز **GNU General Public License v2 or later (GPLv2+)** منتشر شده است. استفاده، ویرایش و توزیع طبق شرایط مجوز GPL آزاد است. جزئیات کامل در فایل [LICENSE](LICENSE).

### ارتباط
تلگرام: [@Mehdibinam](https://t.me/Mehdibinam)

---

## 🇬🇧 English

### About
Disable Bloat Manager (formerly known as Bloat Manager) is a lightweight, unified plugin that brings the features of several popular optimization plugins (Perfmatters, Disable Admin Notices, Featherweight, Disable Bloat for WP & Woo, and more) into one modern interface with AJAX tabs and tooltips.

**Version 2.0** adds three brand-new sections — **Security & Firewall**, **Domain Blocking**, and **Menu Hiding** — plus Google Fonts controls and extra options for comments, widgets, admin, and updates. With 150+ options it boosts both performance and security.

### Features

| Category | Highlights |
|---|---|
| **Frontend & Core** | Remove emojis, Embed/oEmbed, Dashicons, jQuery Migrate, `?ver=` query strings, Block Library & Global Styles CSS, REST & generator tags, DNS prefetch, shortlink, RSD, WLW, RSS feeds, self-pingbacks, password strength meter, command palette, view transitions, Google Maps |
| **Comments** | Close comments per post type, auto-close old comments, pagination, disable pingbacks/trackbacks, remove Gravatar, auto-clean spam |
| **Admin & Dashboard** | Clean dashboard, remove help tabs & Screen Options, Site Health, import/export, Heartbeat control, revisions limit, Autosave, login page tweaks, admin notice control, remove WordPress logo and footer text |
| **Block Editor** | Revert to classic editor, disable Welcome Guide, Block Directory, default & remote patterns, template editor |
| **Widgets** | Disable all or selected core widgets, remove widget support entirely |
| **Updates** | Disable auto-updates for core, themes and plugins, stop external update checks |
| **WooCommerce** | Disable Analytics & Marketing, marketplace suggestions, Cart Fragments, scripts/styles on non-shop pages, widgets, email promos, shipping suggestions |
| **Third-party** | Jetpack nags/Blaze, Elementor widget & Google Fonts, Yoast promos & HTML comment, Contact Form 7 assets, ACF menu, UpdraftPlus |
| **AI** | Disable AI in WordPress core, Elementor, Rank Math, Yoast, Jetpack, and Bertha |
| **Google Fonts** ⭐ | Remove Google Fonts, self-host fonts, `font-display: swap`, async Font Awesome, disable Elementor Google Fonts |
| **🔒 Security** ⭐ | WordPress hardening (XML-RPC, user enumeration, file editor, application passwords, REST limits), security headers & HSTS, login attempt limiting, generic login errors, custom login URL, firewall (attack signatures, upload checks, scanner blocking, HTTP methods & rate limiting, IP/path allow & block lists) |
| **🌐 Domain Blocking** ⭐ | Blacklist/whitelist modes, site scanner for external domains, strip blocked assets from page, block fetch/XHR requests, stop WordPress telemetry & update checks, external request timeout |
| **Menu Hiding** ⭐ | Hide admin sidebar menus/submenus and admin bar items, with auto-detection of newly installed plugins |

### Installation
1. Download [`disable-bloat-manager (cld v2).zip`](https://github.com/mehdibinam/disable-bloat-manager/releases/latest) from the Releases section.
2. Place the `disable-bloat-manager` folder in `wp-content/plugins/` (or upload the zip directly via **Plugins → Add New → Upload Plugin**).
3. Activate it from the **Plugins** screen.
4. Open the **Disable Bloat** menu and enable the options you want.

### Usage Guide
- Every option is **off by default**; nothing changes until you turn it on.
- The **Recommended settings** button enables the safe, high-impact options at once.
- Look for the "⭐ Recommended" and "⚠ Review needed" badges — warnings are shown before enabling risky options.
- Hover the "?" icon beside an option for a detailed explanation.
- WooCommerce and third-party options only apply when the related plugin is active.
- Test your site and forms after enabling sensitive options (REST API, XML-RPC, firewall, feeds, comments, HSTS).
- If you get locked out of the login page, add `define( 'MB_DISABLE_LOGIN_URL', true );` to `wp-config.php`.

### Requirements
WordPress 6.0+ • PHP 7.4+

### License
Released under the **GNU General Public License v2 or later (GPLv2+)**. You are free to use, modify and distribute it under the terms of the GPL. See [LICENSE](LICENSE).

### Contact
Telegram: [@Mehdibinam](https://t.me/Mehdibinam)
