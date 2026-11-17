<?php
if (!defined('ABSPATH')) exit;

/** فهرست‌های پیشنهادیِ دامنه (برای دکمه‌های «افزودن سریع») */
function mb_block_presets() {
    return [
        ['label' => 'آمار و رهگیری', 'items' => ['google-analytics.com', 'googletagmanager.com', 'connect.facebook.net', 'stats.wp.com', 'pixel.wp.com', 'static.hotjar.com', 'script.hotjar.com', 'api-js.mixpanel.com', 'cdn.mxpnl.com']],
        ['label' => 'سرویس‌های وردپرس و ووکامرس', 'items' => ['tracking.woocommerce.com', 'woocommerce.com', 'public-api.wordpress.com', 'jetpack.com', 'my.elementor.com', 'library.elementor.com', 'rankmath.com', 'api.yoast.com']],
        ['label' => 'فونت و CDN خارجی', 'items' => ['fonts.googleapis.com', 'fonts.gstatic.com', 'ajax.googleapis.com', 'maxcdn.bootstrapcdn.com', 'stackpath.bootstrapcdn.com', 'use.fontawesome.com', 'kit.fontawesome.com', 'unpkg.com', 'cdn.jsdelivr.net']],
        ['label' => 'شبکه‌های اجتماعی', 'items' => ['platform.twitter.com', 'platform.linkedin.com', 'assets.pinterest.com', 'www.instagram.com']],
    ];
}
function mb_allow_presets() {
    return [
        ['label' => 'درگاه‌های پرداخت', 'items' => ['zarinpal.com', 'pay.ir', 'idpay.ir', 'nextpay.org', 'shaparak.ir']],
        ['label' => 'پیامک و پیام‌رسان', 'items' => ['kavenegar.com', 'sms.ir', 'ippanel.com', 'melipayamak.com', 'farazsms.com', 'bale.ai']],
        ['label' => 'نقشه و حمل‌ونقل', 'items' => ['neshan.org', 'map.ir', 'balad.ir', 'tipax.ir', 'post.ir']],
        ['label' => 'سرویس‌های وردپرس', 'items' => ['api.wordpress.org', 'downloads.wordpress.org', 'wordpress.org']],
    ];
}

function mb_features_secure() {
    $f = [];

    /* ───── امنیت ───── */
    $S = 'سخت‌سازی وردپرس';
    $f[] = mb_f('sec_xmlrpc', 'security', $S, 'غیرفعال‌سازی XML-RPC', 'درگاه قدیمی xmlrpc.php که هکرها برای حدس رمز و حمله‌ی پینگ‌بک از آن سوءاستفاده می‌کنند بسته می‌شود.', ['badge' => 'rec', 'rec' => 1, 'warn' => 'اپلیکیشن موبایل وردپرس و افزونه‌هایی مثل Jetpack که از XML-RPC استفاده می‌کنند از کار می‌افتند.']);
    $f[] = mb_f('sec_user_enum', 'security', $S, 'جلوگیری از کشف نام کاربری', 'کاوش ?author=1، فهرست کاربران در REST و نقشه‌ی سایت و نام نویسنده در oEmbed برای مهمان‌ها بسته می‌شود.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('sec_author_pages', 'security', $S, 'غیرفعال‌سازی صفحه‌ی نویسنده', 'آدرس‌های بایگانی نویسنده به صفحه‌ی اصلی هدایت می‌شود.', ['badge' => 'caution', 'warn' => 'اگر سایت چندنویسنده دارید و صفحه‌ی نویسنده‌ها را لازم دارید، روشن نکنید.']);
    $f[] = mb_f('sec_file_editor', 'security', $S, 'غیرفعال‌سازی ویرایشگر فایل قالب و افزونه', 'ویرایش مستقیم کد از داخل پیشخوان بسته می‌شود تا با دزدیده‌شدن حساب مدیر، کد مخرب تزریق نشود.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('sec_app_passwords', 'security', $S, 'غیرفعال‌سازی رمزهای برنامه', 'امکان ساخت رمز مخصوص برنامه‌ها (Application Passwords) برای دسترسی REST خاموش می‌شود.', ['warn' => 'برنامه‌هایی که با این رمزها به سایت وصل هستند دیگر دسترسی ندارند.']);
    $f[] = mb_f('sec_rest_batch', 'security', $S, 'بستن مسیر batch در REST برای مهمان‌ها', 'درخواست‌های گروهی REST فقط برای کاربران واردشده مجاز است.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('unregister_tags', 'admin', 'ذخیره‌ی نوشته‌ها', 'حذف برچسب‌ها از نوشته‌ها', 'برچسب‌های پیش‌فرض نوشته (Tags) غیرفعال می‌شود؛ مناسب سایت‌هایی که فقط دسته‌بندی دارند.', ['badge' => 'caution', 'warn' => 'برچسب‌های موجود دیگر برای نوشته‌ها نمایش داده نمی‌شوند.']);
    $f[] = mb_f('sec_rest_access', 'security', 'دسترسی به REST API', 'چه کسانی می‌توانند از REST API استفاده کنند؟', 'دسترسی REST را محدود کنید. ویرایشگر بلوک برای کاربران مجاز همچنان کار می‌کند.',
        ['type' => 'select', 'default' => 'everyone', 'warn' => 'ممکن است افزونه‌هایی که از REST در سمت بازدیدکننده استفاده می‌کنند (مثل فرم‌ها) از کار بیفتند. نام‌فضای آن‌ها را در کادر زیر بنویسید.',
         'options' => ['everyone' => 'همه', 'logged_in' => 'فقط کاربران واردشده', 'editors' => 'فقط ویرایشگرها و بالاتر']]);
    $f[] = mb_f('sec_rest_namespaces', 'security', 'دسترسی به REST API', 'نام‌فضاهای عمومی REST', 'هر نام‌فضا در یک خط؛ این مسیرها همیشه عمومی می‌مانند. مثلاً contact-form-7 یا wc/store.', ['type' => 'textarea', 'default' => '', 'rows' => 3, 'placeholder' => "contact-form-7\nwc/store"]);
    $S = 'هدرهای امنیتی';
    $f[] = mb_f('sec_headers', 'security', $S, 'ارسال هدرهای امنیتی', 'هدرهای X-Content-Type-Options، X-Frame-Options، Referrer-Policy و Permissions-Policy برای محافظت مرورگر ارسال می‌شود. هدرهایی که قبلاً تنظیم شده‌اند عوض نمی‌شوند.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('sec_hsts', 'security', $S, 'فعال‌سازی HSTS', 'به مرورگر می‌گوید یک سال فقط با HTTPS به سایت وصل شود.', ['badge' => 'caution', 'warn' => 'فقط وقتی روشن کنید که همه‌ی صفحات دامنه با HTTPS کار می‌کنند؛ برگشت از آن در مرورگر کاربران زمان‌بر است.']);
    $S = 'محافظت از ورود';
    $f[] = mb_f('login_limit', 'security', $S, 'محدود‌کردن تلاش‌های ناموفق ورود', 'بعد از چند بار ورود اشتباه، آدرس آی‌پی برای مدتی اجازه‌ی تلاش ندارد. آی‌پی‌های مجاز (فایروال) هرگز قفل نمی‌شوند.', ['badge' => 'rec', 'rec' => 1,
        'fields' => [
            ['id' => 'login_limit_tries', 'type' => 'number', 'default' => 5, 'min' => 2, 'max' => 50, 'unit' => 'تلاش مجاز'],
            ['id' => 'login_limit_window', 'type' => 'number', 'default' => 15, 'min' => 1, 'max' => 1440, 'unit' => 'دقیقه (بازه‌ی شمارش)'],
            ['id' => 'login_limit_lock', 'type' => 'number', 'default' => 30, 'min' => 1, 'max' => 10080, 'unit' => 'دقیقه (مدت قفل)'],
        ]]);
    $f[] = mb_f('login_generic_error', 'security', $S, 'پیام خطای یکسان هنگام ورود', 'برای نام کاربری اشتباه و رمز اشتباه یک پیام یکسان نمایش داده می‌شود تا نام‌های کاربری معتبر لو نروند.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('login_slug', 'security', $S, 'آدرس سفارشی صفحه‌ی ورود', 'صفحه‌ی ورود به آدرس مخفی شما منتقل می‌شود و wp-login.php و wp-admin برای افراد واردنشده «۴۰۴» می‌دهد. اگر قفل شدید، در wp-config.php این خط را اضافه کنید: define( \'MB_DISABLE_LOGIN_URL\', true );',
        ['badge' => 'caution', 'warn' => 'آدرس جدید را حتماً یادداشت کنید. در شبکه‌های چندسایتی کار نمی‌کند.',
         'fields' => [['id' => 'login_slug_val', 'type' => 'text', 'default' => '', 'placeholder' => 'مثلاً my-panel (فقط حروف انگلیسی و عدد)']]]);
    $S = 'فایروال';
    $f[] = mb_f('fw_signatures', 'security', $S, 'بررسی الگوهای حمله', 'پارامترهای درخواست برای نشانه‌های XSS، SQL Injection و پیمایش مسیر بررسی و درخواست مشکوک مسدود می‌شود. مدیران و کاربران نویسنده مستثنا هستند.', ['badge' => 'caution', 'warn' => 'ممکن است درخواست‌های درست (مثلاً نوشتن کد در فرم‌های عمومی) را اشتباهی مسدود کند. بعد از فعال‌سازی فرم‌ها را امتحان کنید.']);
    $f[] = mb_f('fw_uploads', 'security', $S, 'بررسی فایل‌های بارگذاری‌شده', 'فایل‌هایی با پسوند اجرایی (حتی پسوند دوگانه مثل .php.jpg) یا حاوی کد PHP مسدود می‌شود.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('fw_scanners', 'security', $S, 'مسدودکردن اسکنرهای آسیب‌پذیری', 'درخواست‌هایی که User-Agent خالی یا متعلق به ابزارهای شناخته‌شده‌ی حمله (sqlmap، nikto، wpscan و ...) دارند رد می‌شوند.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('fw_same_origin', 'security', $S, 'بررسی هم‌مبدأ‌بودن فرم‌ها', 'ارسال فرم دیدگاه و ورود از سایت‌های دیگر رد می‌شود.', ['warn' => 'اگر از سرویسی برای ارسال خودکار دیدگاه یا ورود از دامنه‌ی دیگر استفاده می‌کنید، مسدود می‌شود.']);
    $f[] = mb_f('fw_methods', 'security', $S, 'محدودکردن متدهای HTTP', 'فقط GET، POST، HEAD و OPTIONS مجاز است (برای REST: PUT، PATCH و DELETE هم).', ['warn' => 'اگر سرویس یا برنامه‌ای با متدهای دیگر به سایت درخواست می‌دهد، مسدود می‌شود.']);
    $f[] = mb_f('fw_rate', 'security', $S, 'محدودیت تعداد درخواست', 'اگر یک آی‌پی در بازه‌ی کوتاه درخواست زیادی بفرستد، موقتاً با خطای ۴۲۹ مسدود می‌شود. کاربران واردشده محدود نمی‌شوند.', ['badge' => 'caution',
        'warn' => 'اگر آی‌پی واقعی بازدیدکنندگان را درست تشخیص ندهد (پشت CDN)، ممکن است همه با هم مسدود شوند. اول منبع آی‌پی را درست انتخاب کنید.',
        'fields' => [
            ['id' => 'fw_rate_max', 'type' => 'number', 'default' => 120, 'min' => 10, 'max' => 10000, 'unit' => 'درخواست'],
            ['id' => 'fw_rate_window', 'type' => 'number', 'default' => 60, 'min' => 10, 'max' => 3600, 'unit' => 'ثانیه (بازه)'],
            ['id' => 'fw_rate_block', 'type' => 'number', 'default' => 300, 'min' => 30, 'max' => 86400, 'unit' => 'ثانیه (مدت انسداد)'],
        ]]);
    $f[] = mb_f('fw_ip_source', 'security', 'آی‌پی و فهرست‌ها', 'منبع تشخیص آی‌پی بازدیدکننده', 'اگر سایت پشت Cloudflare یا پروکسی است، آی‌پی واقعی را از هدر مربوط بخوانید؛ فقط وقتی انتخاب کنید که واقعاً پشت آن سرویس هستید.',
        ['type' => 'select', 'default' => 'remote_addr', 'options' => ['remote_addr' => 'اتصال مستقیم', 'cloudflare' => 'Cloudflare (CF-Connecting-IP)', 'xff' => 'X-Forwarded-For', 'xreal' => 'X-Real-IP']]);
    $f[] = mb_f('fw_allow_ips', 'security', 'آی‌پی و فهرست‌ها', 'آی‌پی‌های مجاز', 'هر آی‌پی یا بازه (مثل 1.2.3.0/24) در یک خط؛ از فایروال و قفل ورود معاف می‌شوند.', ['type' => 'textarea', 'default' => '', 'rows' => 3]);
    $f[] = mb_f('fw_block_ips', 'security', 'آی‌پی و فهرست‌ها', 'آی‌پی‌های مسدود', 'هر آی‌پی یا بازه در یک خط؛ برای همیشه مسدود می‌شوند.', ['type' => 'textarea', 'default' => '', 'rows' => 3]);
    $f[] = mb_f('fw_allow_paths', 'security', 'آی‌پی و فهرست‌ها', 'مسیرهای مستثنا', 'مسیرهایی که از بررسی فایروال معاف می‌شوند (مثلاً /webhooks/payment). هر مسیر در یک خط.', ['type' => 'textarea', 'default' => '', 'rows' => 3, 'placeholder' => '/webhooks/payment']);

    /* ───── مسدودسازی دامنه‌ها ───── */
    $S = 'حالت و فهرست‌ها';
    $f[] = mb_f('net_mode', 'domains', $S, 'حالت کنترل درخواست‌ها', 'در «فهرست سیاه» فقط دامنه‌های فهرست مسدود می‌شوند. در «فهرست سفید» همه‌چیز به‌جز دامنه‌های فهرست سفید مسدود است (مناسب قطعی اینترنت بین‌الملل). درخواست‌ها به خود سایت هرگز مسدود نمی‌شود.',
        ['type' => 'select', 'default' => 'off', 'badge' => 'caution', 'warn' => 'در حالت فهرست سفید، اتصال سایت به همه‌ی سرویس‌های بیرونی که در فهرست سفید نباشند قطع می‌شود (حتی به‌روزرسانی وردپرس).',
         'options' => ['off' => 'خاموش', 'blacklist' => 'فهرست سیاه (مسدودکردن دامنه‌های انتخابی)', 'whitelist' => 'فهرست سفید (فقط دامنه‌های مجاز)']]);
    $f[] = mb_f('net_blacklist', 'domains', $S, 'فهرست سیاه دامنه‌ها', 'هر دامنه در یک خط. زیردامنه‌ها هم شامل می‌شوند. با دکمه‌های افزودن سریع یا نتیجه‌ی اسکن سایت می‌توانید فهرست را پر کنید.', ['type' => 'textarea', 'default' => '', 'rows' => 8, 'placeholder' => 'example.com', 'presets' => mb_block_presets()]);
    $f[] = mb_f('net_scan', 'domains', $S, 'اسکن سایت برای یافتن دامنه‌های خارجی', 'چند صفحه‌ی مهم سایت (اصلی و نوشته‌های اخیر) را می‌خواند و دامنه‌هایی که صفحات به آن‌ها درخواست می‌دهند را نشان می‌دهد تا بتوانید با یک کلیک مسدودشان کنید.', ['type' => 'action', 'action' => 'mb_scan_site', 'button' => 'شروع اسکن', 'target' => 'net_blacklist']);
    $f[] = mb_f('net_whitelist', 'domains', $S, 'فهرست سفید دامنه‌ها', 'در حالت فهرست سفید فقط این دامنه‌ها مجاز هستند. هر دامنه در یک خط.', ['type' => 'textarea', 'default' => '', 'rows' => 6, 'placeholder' => 'example.ir', 'presets' => mb_allow_presets()]);
    $S = 'نحوه‌ی مسدودسازی';
    $f[] = mb_f('net_block_assets', 'domains', $S, 'حذف فایل‌های خارجی مسدودشده از صفحه', 'فایل‌های CSS، جاوااسکریپت و iframe که از دامنه‌ی مسدودشده می‌آیند از کد صفحه (سایت و پیشخوان) حذف می‌شوند تا صفحه منتظر آن‌ها نماند.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('net_block_runtime', 'domains', $S, 'مسدودکردن درخواست‌های مرورگر', 'درخواست‌هایی که جاوااسکریپت صفحه به دامنه‌های مسدود می‌فرستد (fetch و XHR) و تگ‌هایی که بعد از بارگذاری اضافه می‌شوند هم مسدود می‌شود.');
    $f[] = mb_f('net_mixpanel', 'domains', $S, 'مسدودکردن Mixpanel', 'سرویس آنالیز Mixpanel که بعضی افزونه‌ها بارگذاری می‌کنند در هر حالت مسدود می‌شود.');
    $S = 'پایداری و حریم خصوصی';
    $f[] = mb_f('net_telemetry', 'domains', $S, 'قطع ارسال اطلاعات و خبرهای وردپرس', 'درخواست به سرویس‌های آمار و خبر وردپرس (stats، news، events) متوقف می‌شود و ویجت‌های خبر هم برداشته می‌شوند.', ['badge' => 'rec', 'rec' => 1]);
    $f[] = mb_f('net_updates_off', 'domains', $S, 'قطع بررسی به‌روزرسانی از سرور بیرونی', 'بررسی نسخه‌ی جدید هسته، افزونه‌ها و قالب‌ها از سرور وردپرس متوقف می‌شود تا پیشخوان معطل نماند.', ['badge' => 'caution', 'warn' => 'دیگر خبری از نسخه‌ی جدید و به‌روزرسانی امنیتی نمی‌گیرید. هر چند وقت یک بار آن را خاموش کنید و به‌روزرسانی کنید.']);
    $f[] = mb_f('net_timeout', 'domains', $S, 'محدودکردن زمان انتظار درخواست‌های خارجی', 'اگر سرویسی دیر جواب بدهد، بیشتر از این مدت منتظرش نمی‌ماند تا سایت و پیشخوان کند نشود.', ['badge' => 'rec', 'rec' => 1, 'fields' => [['id' => 'net_timeout_sec', 'type' => 'number', 'default' => 5, 'min' => 1, 'max' => 30, 'unit' => 'ثانیه']]]);

    /* ───── مخفی‌سازی منوها ───── */
    $f[] = mb_f('menus_hide', 'menus', 'منوی کناری پیشخوان', 'منوها و زیرمنوهایی که مخفی شوند', 'تیک بزنید تا منو یا زیرمنو پنهان شود. افزونه‌ی تازه‌نصب‌شده خودکار در همین فهرست پیدا می‌شود. توجه: پنهان‌کردن فقط منو را برمی‌دارد و دسترسی با آدرس مستقیم بسته نمی‌شود.', ['type' => 'tree', 'source' => 'menus', 'default' => []]);
    $f[] = mb_f('menus_bar', 'menus', 'نوار بالای سایت', 'موارد نوار مدیریت که مخفی شوند', 'موارد نوار بالا (مثل سایت‌های من، دیدگاه‌ها و منوی افزونه‌ها) را پنهان کنید. فهرست خودکار با افزونه‌های نصب‌شده به‌روز می‌شود.', ['type' => 'tree', 'source' => 'bar', 'default' => []]);

    return $f;
}
