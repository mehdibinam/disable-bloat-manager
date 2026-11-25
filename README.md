# Disable Bloat Manager

## مدیریت کارایی وردپرس | WP Performance Manager

![Version](https://img.shields.io/badge/version-2.4.0-blue) ![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b) ![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4) ![License](https://img.shields.io/badge/license-Source--Available-orange) [![Download](https://img.shields.io/badge/download-v2.4.0-brightgreen)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)

[فارسی](#فارسی) · [English](#english)

---

<a id="فارسی"></a>

<div dir="rtl">

## فارسی

**مدیریت کارایی وردپرس** مجموعه‌ای دسته‌بندی‌شده برای خاموش‌کردن بخش‌های اضافه‌ی وردپرس، ووکامرس و افزونه‌هاست؛ همراه با ابزارهای امنیت و کنترل ارتباط سایت با دامنه‌های بیرونی. هر امکان یک راهنمای ساده دارد (با رفتن روی علامت «؟») و رابط کاملاً فارسی و راست‌به‌چپ است.

<!-- DO NOT REMOVE: user-provided screenshot (not present in the plugin ZIP). Keep this <img> when replacing README. See docs/README-IMAGE.md -->
<img width="1597" height="872" alt="disable-bloat-manager" src="https://github.com/user-attachments/assets/0cf19e4f-24b1-47f1-89c4-1312e2552f0d" />

### ایدهٔ اولیه

ایدهٔ اولیهٔ این پروژه با الهام از افزونه‌های زیر شکل گرفت:

[Bad Domain Blocker](https://shetabwp.com/bad-domain-blocker/), Bloatoff Utils, Disable Admin Notices, [Disable Bloat Pro](https://disablebloat.com/), Disable everything, [Featherweight](https://wordpress.org/plugins/wp-disable/), [ghateino](https://github.com/shokrino/ghateino), [Perfmatters](https://perfmatters.io/), wp widget disable.

کد این افزونه از نو نوشته شده است و از کد افزونه‌های بالا کپی نشده است.

### مزیت اصلی

- **کاهش تعداد درخواست‌ها و حجم صفحه:** فقط امکان‌هایی که خودتان روشن می‌کنید اجرا می‌شوند و هیچ کد اضافه‌ای بارگذاری نمی‌شود.
- **رفتار قابل‌پیش‌بینی‌تر سایت:** با قطع تماس‌های بیرونی (فونت‌های گوگل، Mixpanel، تله‌متری وردپرس) صفحه کمتر منتظر سرویس‌های خارجی می‌ماند.
- **وابستگی کمتر به CDN و سرویس‌های خارجی:** در شرایط اینترنت ناپایدار یا محدود، سایت باز هم کار می‌کند.
- **عیب‌زدایی ساده‌تر:** تولتیپ هر امکان، هشدار تداخل با سایر افزونه‌ها و پیام هشدار قبل از تغییرات حساس.

### رویکرد

همه‌چیز به‌صورت پیش‌فرض خاموش است (Minimal by default)؛ شما تعیین می‌کنید چه چیزی اجرا شود. امکان‌های حساس قبل از فعال‌سازی هشدار می‌دهند و هیچ تغییری به‌صورت خودکار در سایت اعمال نمی‌شود.

### سناریوهای پیشنهادی

- **عمومی (ایمن):** دکمه‌ی «تنظیمات پیشنهادی» را بزنید تا امکان‌های کم‌خطر روشن شوند.
- **اینترنت محدود:** حالت «فهرست سفید» دامنه‌ها را انتخاب و دامنه‌های ضروری (درگاه پرداخت، پیامک، نقشه) را وارد کنید.
- **عیب‌زدایی:** هشدارهای تداخل را بررسی کنید، یادگیری دامنه‌ها را موقتاً خاموش و در صورت لزوم از «بازنشانی» استفاده کنید.

### نکات سازگاری

- گزینه‌هایی مثل «آدرس ورود سفارشی»، HSTS و فایروال را اول روی سایت آزمایشی امتحان کنید.
- اگر «محدودکردن REST API» را روشن می‌کنید، فرم‌های Contact Form 7 و فروشگاه ووکامرس خودکار مستثنا هستند؛ برای افزونه‌های دیگر که از REST در سمت بازدیدکننده استفاده می‌کنند، بعد از فعال‌سازی عملکردشان را بررسی کنید.
- اگر نقشه‌ی گوگل در سایت دارید، «غیرفعال‌سازی گوگل مپ» را روشن نکنید یا صفحه‌ی آن را در استثناها بنویسید.
- در صورت قفل‌شدن پیشخوان، پوشه‌ی افزونه را موقتاً تغییر نام دهید یا خط `MB_DISABLE_LOGIN_URL` را به `wp-config.php` اضافه کنید.
- اگر در `wp-config.php` ثابت `WP_HTTP_BLOCK_EXTERNAL` را روشن کرده‌اید، `api.github.com` و `github.com` را به `WP_ACCESSIBLE_HOSTS` اضافه کنید تا بررسی و نصب به‌روزرسانی افزونه کار کند. همچنین `DISALLOW_FILE_MODS` جلوی نصب به‌روزرسانی از پیشخوان را می‌گیرد.

### تازه‌های نسخه ۲٫۴

- **منوی «امکانات ویژه»:** علاوه بر خاموش‌کردن، امکانات کاربردی هم دارد: تکثیر نوشته/برگه/منو، ستون تصویر شاخص، اسکرول بی‌نهایت یا بارگذاری بیشتر برای رسانه، ارتفاع دلخواه ویرایشگر، قالب‌های المنتور در ویرایشگر کلاسیک، انتشار با Ajax، برون‌بری XML تکی، کلیک‌پذیر کردن سکشن و ستون المنتور، فونت وزیرمتن در سایت، ادامه‌ی مطالعه، افزودن منبع هنگام کپی، اعتبارسنجی موبایل ایران (المنتور پرو و Gravity Forms)، پالت رنگ سفارشی پنل مدیریت، ستون لوگوی افزونه‌ها و ویجت داشبورد.
- **منوی «پیشخوان» یکپارچه شد:** «سمت بازدیدکننده» و «به‌روزرسانی‌ها» در آن ادغام شدند و همه‌ی ویجت‌های صفحه‌ی اصلی پیشخوان در یک بخش‌اند.
- **امکان‌های تازه:** غیرفعال‌سازی بلوک‌های انتخابی گوتنبرگ، ضد اسپم دیدگاه (Honeypot، زمان، حذف فیلد وب‌سایت)، بارگذاری هوشمند CF7، خاموش‌کردن Lazy Load، Speculative Loading و srcset، تصویر پس‌زمینه‌ی ورود، غیرفعال‌سازی کد تخفیف، برچسب محصول و SKU، حذف فیلدهای تسویه‌حساب و WP‑Cron.
- **گزارش تأثیر:** در «نمای کلی» و ویجت داشبورد، تعداد درخواست‌ها و حمله‌های مسدودشده، قفل‌های ورود، اسپم ردشده و فایل‌های حذف‌شده در ۳۰ روز اخیر و نمودار ۱۴ روزه نمایش داده می‌شود.
- **بهبود رابط:** زیرگزینه‌ها همیشه دیده می‌شوند ولی تا فعال‌شدن امکان اصلی غیرفعال‌اند؛ انتخاب تصویر از رسانه‌ها؛ لیست پیشنهاد دامنه سه‌ستونه با دکمه‌ی قرمز «مسدود کردن»؛ انیمیشن نرم جابه‌جایی منو و تغییر حالت تیره/روشن؛ منوی مرتبط با امکان‌های خاموش‌شده (مثل هوش مصنوعی و MCP المنتور) خودکار پنهان می‌شود.
- **یادگیری دامنه‌ها** حالا دامنه‌ی درخواست‌های سروری سایت را هم یاد می‌گیرد.

### نسخه ۲٫۳

- **دکمه‌ی به‌روزرسانی:** هر وقت نسخه‌ی جدیدی در [Releases](https://github.com/mehdibinam/disable-bloat-manager/releases) گیت‌هاب منتشر شود، کنار دکمه‌ی حالت تیره/روشن یک دکمه‌ی به‌روزرسانی ظاهر می‌شود و نسخه‌ی جدید در فهرست به‌روزرسانی‌های وردپرس هم دیده می‌شود. بررسی نسخه فقط در پس‌زمینه و با زمان انتظار کوتاه انجام می‌شود، پس قطع‌بودن گیت‌هاب سایت را کند نمی‌کند.
- **برچسب‌های رنگی:** «پیشنهادی» سبز، «نیازمند بررسی» زرد و برای امکان‌های بسیار حساس (مثل آدرس ورود سفارشی، قطع بررسی به‌روزرسانی هسته و فایروال) برچسب قرمز «حساسیت بالا».
- **دکمه‌های آیکنی:** مثلاً سطل زباله برای پاک‌کردن فهرست‌ها و چشم برای برگرداندن اعلان‌های مخفی‌شده.
- **اصلاح مخفی‌سازی موارد نوار بالا:** مواردی که تیک می‌زنید (همراه با زیرمنوهایشان) واقعاً از همه‌ی صفحه‌ها پنهان می‌شوند و موارد جدید و دیرهنگام هم شناسایی می‌شوند.
- **فایل `uninstall.php`:** با حذف افزونه، تنظیمات، کش‌ها، اعلان‌های مخفی‌شده و فونت‌های ذخیره‌شده‌ی آن هم پاک می‌شوند (با غیرفعال‌سازی چیزی پاک نمی‌شود).

### نسخه ۲٫۲

- منوی افزونه به **تنظیمات ← مدیریت کارایی** منتقل شد و سایدبار ساده‌تر شد؛ ویرایشگر بلوک و ابزارک‌ها ادغام شدند.
- فیلدهای عددی با دکمه‌های + و −، چیدمان دو ستونه با ارتفاع هم‌اندازه و دکمه‌ی «پاک‌کردن فهرست» برای فهرست سیاه و سفید.
- شناسایی کامل منوها و موارد نوار بالا، فهرست اعلان‌های مخفی‌شده با بازگردانی تکی، دریافت و وارد کردن تنظیمات (JSON) و جستجوی دوزبانه (فارسی و انگلیسی).
- فونت گوگل و المنتور در بالای بخش «فونت و افزونه‌ها».

### نصب

۱. آخرین نسخه ([`disable-bloat-manager-vX.Y.zip`](https://github.com/mehdibinam/disable-bloat-manager/releases/latest)) را دانلود و از بخش **افزونه‌ها ← افزودن ← بارگذاری افزونه** نصب و فعال کنید.
۲. از **تنظیمات ← مدیریت کارایی** یا لینک «تنظیمات» کنار نام افزونه در صفحه‌ی افزونه‌ها وارد شوید.
۳. از **نمای کلی** روی «تنظیمات پیشنهادی» بزنید تا امکان‌های کم‌خطر روشن شوند، سپس هر بخش را بررسی کنید.

> **مهم:** قبل از استفاده روی سایت اصلی، روی یک سایت آزمایشی امتحان کنید و از سایت پشتیبان بگیرید. امکان‌های برچسب‌خورده با «نیازمند بررسی» یا «حساسیت بالا» ممکن است ظاهر یا کارکرد سایت را عوض کنند.

### راهنمای استفاده

- **جستجو:** در هدر بالا (کلید `/` هم کار می‌کند) هر امکان را پیدا کنید؛ هم فارسی و هم انگلیسی (مثلاً «رنگ» یا `color`).
- **فعال‌سازی همه:** دکمه‌ی روبه‌روی عنوان هر بخش همه‌ی کلیدهای آن بخش را روشن یا خاموش می‌کند (قبل از روشن‌کردن، هشدار می‌دهد).
- **هشدار تداخل:** اگر افزونه‌ی دیگری (مثل Perfmatters، WP Rocket یا Disable Bloat) همان امکان را اجرا کند، کنار امکان نشان داده می‌شود.
- **گزینه‌های وابسته به افزونه:** اگر افزونه‌ی مربوطه (مثل ووکامرس یا المنتور) نصب نباشد، گزینه‌هایش دیده می‌شود ولی غیرفعال است.
- **ابزارها در «نمای کلی»:** تنظیمات پیشنهادی، بازنشانی، و دریافت و وارد کردن تنظیمات به‌صورت فایل JSON.
- **اعلان‌های مخفی‌شده:** با حالت «انتخابی» در «مدیریت اعلان‌های پیشخوان»، فهرست اعلان‌های مخفی‌شده زیر همان گزینه می‌آید و هر کدام را جداگانه می‌توان برگرداند.
- **مخفی‌سازی منوها:** منوی «تنظیمات» و صفحه‌ی خود افزونه قابل مخفی‌شدن نیستند. منوهای هم‌نام با شناسه‌شان از هم جدا می‌شوند.
- **به‌روزرسانی:** وقتی نسخه‌ی جدید روی گیت‌هاب باشد، دکمه‌ی ابری کنار آیکن حالت تیره/روشن ظاهر می‌شود؛ با کلیک روی آن، فرایند استاندارد به‌روزرسانی افزونه‌ی وردپرس اجرا می‌شود.
- اگر با «آدرس سفارشی صفحه‌ی ورود» قفل شدید، در `wp-config.php` این خط را اضافه کنید: `define( 'MB_DISABLE_LOGIN_URL', true );`

### بخش‌ها و امکانات


<table dir="rtl">
<thead>
<tr><th>بخش</th><th>چه کاری می‌کند</th></tr>
</thead>
<tbody>
<tr><td>پیشخوان</td><td>ویجت‌ها، اعلان‌ها، نوار بالا، Heartbeat، صفحه‌ی ورود، کدهای اضافه‌ی سربرگ و فایل‌های سایت، فید و نقشه، و به‌روزرسانی‌های وردپرس</td></tr>
<tr><td>دیدگاه‌ها</td><td>بستن کامل یا جزئی دیدگاه، پینگ‌بک، Gravatar و پاک‌سازی خودکار اسپم</td></tr>
<tr><td>ویرایشگر و ابزارک‌ها</td><td>غیرفعال‌سازی گوتنبرگ و بخش‌های اضافه‌ی آن، حذف همه یا برخی ابزارک‌های هسته</td></tr>
<tr><td>ووکامرس</td><td>تبلیغات و ماژول‌های اضافه‌ی ووکامرس، Cart Fragments و اسکریپت‌ها</td></tr>
<tr><td>فونت و افزونه‌ها</td><td>حذف یا میزبانی محلی فونت گوگل؛ المنتور؛ پنهان‌کردن تبلیغات Jetpack، Yoast و …</td></tr>
<tr><td>هوش مصنوعی</td><td>خاموش‌کردن AI وردپرس (و صفحه‌ی اتصال‌دهنده‌ها) و AI افزونه‌ها</td></tr>
<tr><td>امنیت</td><td>سخت‌سازی، هدرهای امنیتی، محافظت از ورود، آدرس ورود سفارشی و فایروال</td></tr>
<tr><td>مسدودسازی دامنه‌ها</td><td>فهرست سیاه یا سفید دامنه، یادگیری خودکار دامنه‌های خارجی و قطع درخواست‌های کندکننده</td></tr>
<tr><td>مخفی‌سازی منوها</td><td>مخفی‌کردن منوها و زیرمنوهای پیشخوان و نوار بالا با تشخیص خودکار افزونه‌های جدید</td></tr>
<tr><td>امکانات ویژه</td><td>تکثیر، برون‌بری XML، فونت وزیرمتن، پالت رنگ، المنتور و امکانات کاربردی دیگر</td></tr>
</tbody>
</table>


فهرست کامل (۱۹۶ امکان) با توضیح هر کدام: **[docs/FEATURES-Fa.md](docs/FEATURES-Fa.md)**

### پرسش‌های متداول

- **افزونه سایتم را کند نمی‌کند؟** خیر؛ فقط تنظیمات انتخابی اجرا می‌شود. «یادگیری دامنه‌ها» فقط از بخش کوچکی از بازدیدها نمونه می‌گیرد و بررسی به‌روزرسانی فقط در پس‌زمینه انجام می‌شود.
- **بعد از روشن‌کردن گزینه‌ای سایت خراب شد؟** همان گزینه را خاموش کنید یا از «بازنشانی» استفاده کنید. اگر وارد پیشخوان نمی‌شوید، پوشه‌ی افزونه را موقتاً تغییر نام بدهید.
- **با حذف افزونه چه چیزی پاک می‌شود؟** تنظیمات و داده‌های موقت افزونه؛ به محتوای سایت دست نمی‌زند.
- **مشکل یا پیشنهاد دارم:** در بخش [Issues](https://github.com/mehdibinam/disable-bloat-manager/issues) گیت‌هاب ثبت کنید و نسخه‌ی وردپرس، نسخه‌ی افزونه و مراحل بروز مشکل را بنویسید.

### مجوز

کد برای مطالعه و گزارش مشکل عمومی است، اما **کپی، فورک و توزیع آن مجاز نیست**؛ استفاده روی سایت‌های خودتان آزاد است. متن کامل در [LICENSE](LICENSE).

</div>

---

<a id="english"></a>

## English

**WordPress Performance Manager** (Persian UI, RTL) is a categorized toolbox for switching off unnecessary parts of WordPress, WooCommerce and popular plugins, plus security hardening and control over which external domains your site talks to. Every option has a short plain-language tooltip (hover the "?" icon).

### Initial inspiration

The initial idea for this project was inspired by these plugins: [Bad Domain Blocker](https://shetabwp.com/bad-domain-blocker/), Bloatoff Utils, Disable Admin Notices, [Disable Bloat for WP & Woo Pro](https://disablebloat.com/), Disable Everything, [Featherweight](https://wordpress.org/plugins/wp-disable/), [ghateino](https://github.com/shokrino/ghateino), [Perfmatters](https://perfmatters.io/), WP Widget Disable.

The code of this plugin was written from scratch; no code was copied from the plugins above.

### Key advantages

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
- If you restrict the REST API, Contact Form 7 and the WooCommerce store API are exempt automatically; for other plugins that use REST on the visitor side, check that they still work.
- If your site uses Google Maps, don't enable "Disable Google Maps" or add that page to the exclusions.
- If you get locked out of the dashboard, temporarily rename the plugin folder or add `MB_DISABLE_LOGIN_URL` to `wp-config.php`.
- If `WP_HTTP_BLOCK_EXTERNAL` is on in `wp-config.php`, add `api.github.com` and `github.com` to `WP_ACCESSIBLE_HOSTS` so update checks and installs work. `DISALLOW_FILE_MODS` also blocks installing updates from the dashboard.

### What's new in v2.4

- **"Special features" menu** with useful extras on top of switching things off: duplicate posts/pages/menus, thumbnail column, media infinite scroll/load more, editor height, Elementor templates in the classic editor, Ajax publish, single-post XML export, clickable Elementor sections/columns, Vazirmatn font, reading-position resume, copy credit line, Iranian mobile validation (Elementor Pro, Gravity Forms), custom admin color palette, plugin-logo column, and a dashboard widget.
- **Unified "Dashboard" menu:** the visitor-side and updates sections merged into it; every dashboard-widget switch lives in one section.
- **New options:** selective Gutenberg block disabling, comment anti-spam (honeypot, time trap, no website field), smart CF7 loading, switch off Lazy Load / Speculative Loading / srcset, login background image, disable coupons, product tags and SKU, remove checkout fields, WP-Cron.
- **Impact report:** Overview and the dashboard widget show blocked outgoing requests, blocked attacks, login lockouts, rejected spam and removed files over 30 days, plus a 14-day chart.
- **UI:** sub-options are always visible but disabled until the parent option is on; media-library image picker; 3-column domain suggestions with a red "Block" button; smooth menu and dark-mode transitions; menus of disabled features (e.g. AI, Elementor MCP) are hidden automatically.
- **Domain learning** now also learns hosts of the site's own server-side requests.

### v2.3

- **Update button:** when a new release is published on GitHub, an update button appears next to the dark-mode toggle, and the new version also shows in WordPress's update list. Checks run in the background with a short timeout and cached results, so an unreachable GitHub never slows your site.
- **Colored badges:** "Recommended" in green, "Needs review" in yellow, and a red "High sensitivity" label for risky options (custom login URL, core update checks, firewall, …).
- **Icon buttons** (trash to clear lists, eye to restore hidden notices).
- **Fixed admin-bar hiding:** selected items (with their submenus) are really hidden everywhere, and late-added items are detected.
- **`uninstall.php`:** deleting the plugin removes its settings, caches, hidden-notice records and stored fonts (deactivating removes nothing).

### v2.2

- The menu moved to **Settings → مدیریت کارایی**; flat sidebar; block editor and widgets merged.
- Number fields with +/− steppers, an equal-height two-column layout, and a "Clear list" button for the blacklist and whitelist.
- Full menu and admin-bar detection, hidden-notices list with per-item restore, settings export/import (JSON), and bilingual (Persian/English) search.
- Google Fonts and Elementor moved to the top of "Fonts & plugins".

### Installation

1. Download the [latest release (`disable-bloat-manager-vX.Y.zip`)](https://github.com/mehdibinam/disable-bloat-manager/releases/latest), then upload it at **Plugins → Add New → Upload Plugin** and activate it.
2. Open **Settings → مدیریت کارایی** (or the "تنظیمات" link on the Plugins page).
3. On the **Overview** page click "Recommended settings" to enable the low-risk options, then review each section.

> **Important:** test on a staging site and keep a backup first. Options marked "needs review" or "high sensitivity" can change your site's look or behavior.

### How to use it

- **Search** from the header (`/` focuses it) in Persian or English. **Enable all / Disable all** sits opposite each section title and asks for confirmation first.
- **Overlap warnings:** if another plugin (Perfmatters, WP Rocket, Disable Bloat, Wordfence, …) already does the same thing, you'll see a badge and a warning before enabling.
- Options for plugins that aren't installed (WooCommerce, Elementor, Yoast, …) are visible but disabled.
- **Overview → Tools:** Recommended settings, Reset, and settings **Export / Import** (JSON).
- **Hidden notices:** in "Selected" mode, hidden dashboard notices are listed under the option and can be restored one by one.
- **Menu hiding:** the Settings menu and this plugin's page cannot be hidden; menus with identical names are told apart by their slug.
- **Updating:** when a new GitHub release exists, click the cloud button next to the dark-mode icon; it runs the standard WordPress plugin update.
- Locked out by the custom login URL? Add `define( 'MB_DISABLE_LOGIN_URL', true );` to `wp-config.php`.

### What it covers (196 options)

- **Dashboard:** notices (hide all / per notice), widgets (per widget), admin bar, Heartbeat, revisions, login page.
- **Visitor side:** emojis, head clutter, unused scripts/styles, feeds, sitemap, Google Maps, block styles.
- **Comments:** disable fully or per post type, pingbacks, Gravatar, automatic spam cleanup.
- **Editor & widgets, Updates:** disable Gutenberg and its extras, core widgets, automatic updates.
- **WooCommerce:** marketing/promo UI, WooCommerce Home, cart fragments, scripts on non-shop pages, reviews.
- **Fonts & plugins:** remove or self-host Google Fonts; Elementor; hide promos of Jetpack, Yoast, CF7, ACF, UpdraftPlus and more.
- **AI:** WordPress AI and Connectors page, Elementor AI/MCP, Rank Math, Yoast, Jetpack AI.
- **Security:** hardening, security headers, login protection (attempt limit, custom login URL), a lightweight firewall (attack patterns, upload checks, scanner blocking, rate limiting).
- **Domain blocking:** blacklist or whitelist mode, automatic domain learning from normal visits (sampled, light on the server), runtime blocking in the browser, telemetry/update-check blocking, request timeout cap.
- **Menu hiding:** hide admin menus/submenus and admin-bar items; newly installed plugins appear automatically.

Full English feature list: **[docs/FEATURES-En.md](docs/FEATURES-En.md)** · Persian: **[docs/FEATURES-Fa.md](docs/FEATURES-Fa.md)**.

### FAQ

- **Will it slow my site?** No. Only the options you switch on run. Domain learning samples a small share of page views, and update checks run in the background.
- **Something broke after enabling an option?** Turn that option off or use Reset. If you can't reach the dashboard, rename the plugin folder via FTP.
- **What does uninstalling remove?** The plugin's settings and temporary data only; your content is untouched.
- **Bugs and ideas:** open an [Issue](https://github.com/mehdibinam/disable-bloat-manager/issues) with your WordPress version, plugin version, and steps to reproduce.

### License

The code is public to be read and audited, but **copying, forking and redistributing it is not allowed**; running it on your own sites is free. See [LICENSE](LICENSE).
