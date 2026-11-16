<?php
if (!defined('ABSPATH')) exit;
add_action('admin_menu', function(){ add_options_page('Bloat Manager','Bloat Manager','manage_options','bloat-manager','bloat_manager_render_page'); });
add_action('admin_init', function(){ register_setting('bloat_manager_group', BLOAT_MANAGER_OPT, ['sanitize_callback'=>'bloat_manager_sanitize','default'=>[]]); });
function bloat_manager_sanitize($input){
    if(!is_array($input)) return [];
    $out=[]; $defaults=bloat_manager_defaults();
    foreach($defaults as $k=>$def){
        if(is_array($def)){
            if(isset($input[$k]) && is_array($input[$k])){
                if($k==='admin_bar_items'){ $tmp=[]; foreach($input[$k] as $kk=>$vv) if($vv) $tmp[sanitize_key($kk)]=1; $out[$k]=$tmp; }
                elseif($k==='sidebar_widgets'){ $tmp=[]; foreach($input[$k] as $kk=>$vv) if($vv) $tmp[sanitize_text_field($kk)]=1; $out[$k]=$tmp; }
                else $out[$k]=array_filter(array_map('sanitize_text_field',$input[$k]));
            } else $out[$k]=[];
        } elseif(in_array($k,['heartbeat_frequency','revisions_num','autosave_seconds'])) $out[$k]=isset($input[$k])?intval($input[$k]):$def;
        elseif($k==='admin_notices_mode') $out[$k]=in_array($input[$k]??'',['not_hide','only_selected','all','compact_panel'])?$input[$k]:'not_hide';
        else $out[$k]=!empty($input[$k])?1:0;
    }
    return $out;
}
add_action('admin_enqueue_scripts',function($hook){
    if($hook!=='settings_page_bloat-manager') return;
    wp_enqueue_style('bloat-manager-admin',BLOAT_MANAGER_URL.'assets/css/admin.css',[],BLOAT_MANAGER_VERSION);
    wp_enqueue_script('bloat-manager-admin',BLOAT_MANAGER_URL.'assets/js/admin.js',[],BLOAT_MANAGER_VERSION,true);
    // per-notice hide script
    $opts=bloat_manager_get_options();
    if($opts['admin_notices_mode']!=='not_hide'){
        wp_add_inline_script('bloat-manager-admin','window.BM_NOTICES_MODE="'.esc_js($opts['admin_notices_mode']).'";');
    }
});

function bm_item($key,$label,$desc,$important=false,$tip=''){
    $opts=bloat_manager_get_options(); $ck=!empty($opts[$key])?'checked':'';
    $on=!empty($opts[$key])?' on':'';
    $imp=$important?'<span class="bm-imp">⭐ مهم</span>':'';
    $ti=$tip?' <span class="bm-tip" data-tip="'.esc_attr($tip).'">؟</span>':'';
    // if tip empty use desc as tip for extra info
    if(!$tip && $desc) $ti=' <span class="bm-tip" data-tip="'.esc_attr($desc).'">؟</span>';
    echo '<div class="bm-item'.$on.'"><label class="bm-switch"><input type="hidden" name="'.esc_attr(BLOAT_MANAGER_OPT).'['.esc_attr($key).']" value="0"><input type="checkbox" name="'.esc_attr(BLOAT_MANAGER_OPT).'['.esc_attr($key).']" value="1" '.$ck.'><span></span></label><div class="bm-item-text"><div class="bm-item-title">'.esc_html($label).' '.$imp.$ti.'</div><div class="bm-item-desc">'.esc_html($desc).'</div></div></div>';
}
function bm_num_item($key,$label,$desc,$min,$max){
    $opts=bloat_manager_get_options(); $val=intval($opts[$key]??$min);
    echo '<div class="bm-item"><div class="bm-item-text"><div class="bm-item-title">'.esc_html($label).'</div><div class="bm-item-desc">'.esc_html($desc).'</div><div class="bm-number-row" style="margin-top:6px"><input type="number" min="'.$min.'" max="'.$max.'" name="'.esc_attr(BLOAT_MANAGER_OPT).'['.esc_attr($key).']" value="'.esc_attr($val).'" class="bm-number"></div></div></div>';
}

function bloat_manager_render_page(){
    if(!current_user_can('manage_options')) return;
    $opts=bloat_manager_get_options();
    global $wp_admin_bar; $admin_nodes=[];
    // بهبود تشخیص: همه‌ی نودهای سطح اول + نودهای خاص مثل command-palette حتی اگر title خالی باشد
    if(isset($wp_admin_bar) && is_object($wp_admin_bar) && method_exists($wp_admin_bar,'get_nodes')){
        foreach($wp_admin_bar->get_nodes() as $n){
            if(empty($n->parent)){
                $title = trim(strip_tags($n->title));
                // برای command-palette که title شامل <kbd> است
                if($n->id==='command-palette' || strpos($n->id,'command')!==false) $title='پالت فرمان (Ctrl+K)';
                if($title==='') $title=$n->id;
                $admin_nodes[$n->id]=$title;
            }
        }
    }
    if(empty($admin_nodes)) $admin_nodes=['wp-logo'=>'لوگوی وردپرس','site-name'=>'نام سایت','comments'=>'دیدگاه‌ها','new-content'=>'افزودن','updates'=>'به‌روزرسانی‌ها','my-account'=>'حساب من','command-palette'=>'پالت فرمان (Ctrl+K)'];
    // اطمینان از وجود command-palette
    if(!isset($admin_nodes['command-palette'])) $admin_nodes['command-palette']='پالت فرمان (Ctrl+K)';

    global $wp_widget_factory; $all_widgets=[];
    if(isset($wp_widget_factory->widgets)) foreach($wp_widget_factory->widgets as $cls=>$obj) $all_widgets[$cls]=$obj->name??$cls;
    if(empty($all_widgets)) $all_widgets=['WP_Widget_Archives'=>'بایگانی','WP_Widget_Calendar'=>'تقویم','WP_Widget_Categories'=>'دسته‌ها','WP_Widget_Tag_Cloud'=>'ابر برچسب','WP_Widget_Search'=>'جستجو','WP_Widget_Recent_Posts'=>'نوشته‌های اخیر','WP_Widget_Recent_Comments'=>'دیدگاه‌های اخیر','WP_Widget_RSS'=>'RSS','WP_Widget_Meta'=>'متا','WP_Widget_Pages'=>'برگه‌ها','WP_Widget_Text'=>'متن','WP_Widget_Media_Image'=>'تصویر'];
    ?>
    <div class="wrap bm-wrap">
        <div class="bm-head">
            <h1 class="bm-title"><span class="bm-dot"></span> Bloat Manager <small>v<?php echo esc_html(BLOAT_MANAGER_VERSION);?></small></h1>
            <div class="bm-topbar"><button type="submit" form="bm-form" class="button bm-save-top">ذخیره تنظیمات</button></div>
        </div>
        <p class="bm-subtitle">نمای 3 ستونه برای استفاده کامل از عرض 99% — هر کارت سوییچ سمت راست + عنوان و توضیح زیرش + تولتیپ ؟ — توضیحات Featherweight الهام گرفته شده، بدون متن‌های فنی بی‌معنی.</p>
        <div class="bm-tabs">
            <button type="button" class="bm-tab active" data-tab="frontend">فرانت‌اند</button>
            <button type="button" class="bm-tab" data-tab="admin">ادمین</button>
            <button type="button" class="bm-tab" data-tab="woo">ووکامرس</button>
            <button type="button" class="bm-tab" data-tab="block">بلوک ادیتور</button>
            <button type="button" class="bm-tab" data-tab="third">افزونه‌ها</button>
            <button type="button" class="bm-tab" data-tab="ai">هوش مصنوعی</button>
            <button type="button" class="bm-tab" data-tab="privacy">حریم‌خصوصی و امنیت</button>
            <button type="button" class="bm-tab" data-tab="extra">متفرقه</button>
        </div>
        <form method="post" action="options.php" id="bm-form">
            <?php settings_fields('bloat_manager_group'); ?>
            <div class="bm-panel active" id="tab-frontend">
                <div class="bm-card"><h2>فرانت‌اند و هسته — کاهش درخواست و حجم</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('emojis','حذف ایموجی وردپرس','حذف اسکریپت تشخیص ایموجی، استایل‌ها و درخواست DNS به s.w.org — مرورگر ایموجی را خودش نمایش می‌دهد',true,'این گزینه حدود 30KB اسکریپت و یک درخواست خارجی را حذف می‌کند و روی امتیاز PageSpeed تاثیر مستقیم دارد');
                    bm_item('embeds','حذف Embed / oEmbed','جلوگیری از لود wp-embed.js و حذف لینک‌های oembed در head — اگر محتوای یوتیوب/توییتر را Embed نمی‌کنید',true,'اسکریپت oEmbed در همه صفحات لود می‌شود حتی بدون استفاده');
                    bm_item('dashicons','حذف Dashicons برای مهمان','آیکون‌های داشبورد فقط برای کاربران لاگین‌نشده حذف می‌شود — 30KB صرفه‌جویی',true);
                    bm_item('jquery_migrate','حذف jQuery Migrate','حذف لایه سازگاری jQuery قدیمی — اگر افزونه قدیمی ندارید فعال کنید',true);
                    bm_item('remove_ver','حذف ?ver= از CSS/JS','حذف پارامتر نسخه برای کش بهتر مرورگر و CDN',true);
                    bm_item('block_library','حذف استایل Block Library','حذف wp-block-library و wc-blocks-style — اگر از ویرایشگر بلوک استفاده نمی‌کنید',true);
                    bm_item('global_styles','حذف Global Styles','حذف CSS اینلاین global-styles که وردپرس 5.9+ تزریق می‌کند',false);
                    bm_item('dns_prefetch','حذف DNS Prefetch','حذف prefetch به s.w.org و منابع خارجی غیرضروری',false);
                    bm_item('xml_sitemap','غیرفعال‌سازی نقشه سایت هسته','نقشه سایت داخلی وردپرس را خاموش می‌کند — اگر از Yoast/RankMath استفاده می‌کنید',false);
                    bm_item('shortlink','حذف Shortlink','حذف لینک کوتاه ?p=123 از head',false);
                    bm_item('rsd','حذف RSD Link','حذف لینک EditURI برای ویرایش از راه دور — بدون کاربرد برای اکثر سایت‌ها',false);
                    bm_item('wlw','حذف WLW Manifest','حذف لینک Windows Live Writer — منسوخ شده',false);
                    bm_item('generator','حذف نسخه وردپرس','حذف meta generator از head و فید برای امنیت',false);
                    bm_item('feed_links','حذف لینک‌های RSS از head','حذف feed_links اضافی',false);
                    bm_item('disable_feeds','غیرفعال‌سازی کامل فید RSS','همه آدرس‌های /feed با خطای 410 بسته می‌شوند — اگر RSS نمی‌دهید',false);
                    bm_item('adjacent_posts','حذف لینک پست قبلی/بعدی','حذف rel=prev/next از head',false);
                    bm_item('self_pingbacks','غیرفعال‌سازی Self Pingbacks','جلوگیری از پینگ به خود سایت هنگام لینک داخلی',false);
                    bm_item('capital_p','حذف Capital P Dangit','حذف فیلتر خودکار Wordpress → WordPress',false);
                    bm_item('favicon_blank','Favicon خالی','اضافه کردن favicon خالی base64 برای جلوگیری از خطای 404',false);
                    bm_item('password_meter','حذف سنجش قدرت رمز','حذف اسکریپت zxcvbn — فرم‌ها سبک‌تر می‌شوند',true);
                    bm_item('comment_urls','حذف فیلد آدرس سایت در دیدگاه','حذف لینک اسپم در کامنت‌ها',false);
                    bm_item('comment_scripts','لود اسکریپت دیدگاه فقط در صورت نیاز','comment-reply فقط در برگه‌های دارای دیدگاه لود شود',true);
                    bm_item('disable_comments','غیرفعال‌سازی کامل دیدگاه','بستن comments_open، حذف منو و ویجت',false);
                    bm_item('pdf_thumbnails','غیرفعال‌سازی پیش‌نمایش PDF','جلوگیری از ساخت تصویر بندانگشتی PDF',false);
                    bm_item('view_transitions','حذف View Transitions','حذف استایل انتقال نما در ادمین (WP 6.6+)',false);
                    bm_item('command_palette','حذف پالت فرمان (Ctrl+K)','حذف wp-core-commands و آیتم پالت در ادمین‌بار',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-admin">
                <div class="bm-card"><h2>ادمین و داشبورد</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('dashboard_widgets','پاکسازی داشبورد','حذف پنل خوش‌آمد + همه ویجت‌ها (نمای کلی، فعالیت، پیش‌نویس، اخبار)',true,'داشبورد خلوت‌تر و سریع‌تر می‌شود');
                    bm_item('help_tabs','حذف تب راهنما','حذف دکمه راهنما بالای صفحات ادمین',false);
                    bm_item('screen_options','حذف تنظیمات صفحه','مخفی کردن دکمه Screen Options',false);
                    bm_item('admin_bar_logo','حذف لوگوی وردپرس از نوار بالا','حذف منوی W از ادمین‌بار',false);
                    bm_item('admin_footer_text','حذف متن تشکر فوتر','حذف “سپاس بابت ساخت با وردپرس” از پاورقی ادمین',false);
                    bm_item('site_health','غیرفعال‌سازی سلامت سایت','حذف تست‌ها، cron و منوی ابزار > سلامت سایت',false);
                    bm_item('import_export','غیرفعال‌سازی درون‌ریزی/برون‌بری','حذف منوی ابزار و بستن دسترسی به export.php/import.php',false);
                    bm_item('update_nag','مخفی‌سازی هشدار آپدیت برای غیر ادمین','فقط نقش مدیر اصلی هشدار به‌روزرسانی ببیند',false);
                    bm_item('heartbeat_disable','غیرفعال‌سازی Heartbeat','قطع کامل ضربان وردپرس — کاهش درخواست‌های AJAX هر 15 ثانیه در ادمین',true,'اگر روشن باشد، فیلد interval نادیده گرفته می‌شود و ذخیره خودکار و قفل نوشته غیرفعال می‌شود');
                    bm_item('app_passwords','غیرفعال‌سازی رمز اپلیکیشن','حذف قابلیت Application Passwords',false);
                    bm_item('file_editor','غیرفعال‌سازی ویرایشگر فایل','ثبت DISALLOW_FILE_EDIT برای امنیت',false);
                    bm_item('login_logo','مخفی‌سازی لوگو صفحه ورود','مخفی کردن لوگوی وردپرس در wp-login.php',false);
                    bm_item('login_logo_url','تغییر لینک لوگو به صفحه اصلی','کلیک روی لوگو به خانه برود نه wordpress.org',false);
                    bm_item('login_logo_title','تغییر عنوان لوگو به نام سایت','title لوگو = نام سایت',false);
                    bm_item('login_lang_switcher','حذف انتخاب زبان در ورود','حذف dropdown زبان در فرم ورود',false);
                    bm_item('try_gutenberg_nag','حذف پیشنهاد گوتنبرگ','حذف ناگ Try Gutenberg',false);
                    bm_item('admin_email_check','غیرفعال‌سازی تایید ایمیل ادمین','حذف درخواست دوره‌ای تایید ایمیل مدیر',false);
                    bm_item('privacy_nag','غیرفعال‌سازی ابزار حریم‌خصوصی','حذف ابزارهای حریم‌خصوصی هسته',false);
                    bm_item('browser_nag','حذف هشدار مرورگر قدیمی','حذف ناگ مرورگر',false);
                    ?>
                    </div>
                    <h3>تنظیمات عددی</h3>
                    <div class="bm-grid3">
                        <?php bm_num_item('heartbeat_frequency','فاصله Heartbeat (ثانیه)','فاصله ارسال ضربان در ادمین — پیش‌فرض 15، مقدار کمتر = درخواست بیشتر',5,86400); ?>
                        <?php bm_item('revisions_limit','محدودسازی بازبینی‌ها','فعالسازی محدودیت تعداد بازبینی هر نوشته',true,'بدون این گزینه، وردپرس بی‌نهایت بازبینی ذخیره می‌کند'); ?>
                        <?php bm_num_item('revisions_num','تعداد بازبینی نگهداری','تعداد نسخه‌ای که نگه داشته شود — 0 یعنی عدم ذخیره',0,99); ?>
                        <?php bm_item('autosave_interval','تغییر فاصله ذخیره خودکار','فعالسازی تغییر Autosave',false); ?>
                        <?php bm_num_item('autosave_seconds','فاصله ذخیره خودکار (ثانیه)','زمان بین هر ذخیره خودکار پیش‌نویس — پیش‌فرض 60',30,86400); ?>
                    </div>
                    <h3>آیتم‌های نوار بالا — تشخیص هوشمند</h3><p class="bm-item-desc">هر سوییچ روشن شود، آن آیتم از نوار بالا حذف می‌شود. پالت فرمان (Ctrl+K) هم شناسایی می‌شود.</p>
                    <div class="bm-grid6">
                    <?php foreach($admin_nodes as $id=>$title): $ck=!empty($opts['admin_bar_items'][$id])?'checked':''; $on=$ck?' on':''; ?>
                        <div class="bm-witem<?php echo $on;?>"><label class="bm-switch"><input type="checkbox" name="<?php echo esc_attr(BLOAT_MANAGER_OPT);?>[admin_bar_items][<?php echo esc_attr($id);?>]" value="1" <?php echo $ck;?>><span></span></label><span style="flex:1"><?php echo esc_html($title);?><br><code><?php echo esc_html($id);?></code></span></div>
                    <?php endforeach; ?>
                    </div>
                    <h3>ویجت‌های سایدبار — انتخابی (6 ستونه)</h3><p class="bm-item-desc">هر ویجت روشن شود، در بخش نمایش > ابزارک‌ها مخفی می‌شود.</p>
                    <div class="bm-grid6">
                    <?php foreach($all_widgets as $cls=>$name): $ck=!empty($opts['sidebar_widgets'][$cls])?'checked':''; $on=$ck?' on':''; ?>
                        <div class="bm-witem<?php echo $on;?>"><label class="bm-switch"><input type="checkbox" name="<?php echo esc_attr(BLOAT_MANAGER_OPT);?>[sidebar_widgets][<?php echo esc_attr($cls);?>]" value="1" <?php echo $ck;?>><span></span></label><span style="flex:1"><?php echo esc_html($name);?><br><code><?php echo esc_html($cls);?></code></span></div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-woo">
                <div class="bm-card"><h2>ووکامرس — فقط اگر فعال باشد اعمال می‌شود</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('wc_admin','غیرفعال‌سازی ووکامرس ادمین','حذف داشبورد Analytics و ناوبری جدید ووکامرس',false);
                    bm_item('wc_marketing','حذف هاب مارکتینگ','حذف منوی بازاریابی ووکامرس',false);
                    bm_item('wc_marketplace','حذف پیشنهادهای بازار','غیرفعال‌سازی پیشنهاد افزونه در ووکامرس',false);
                    bm_item('wc_addons_submenu','حذف زیرمنوی افزونه‌ها','حذف WooCommerce > Extensions',false);
                    bm_item('wc_status_widget','حذف ویجت وضعیت','حذف متاباکس وضعیت ووکامرس از داشبورد',false);
                    bm_item('wc_widgets','حذف ابزارک‌های ووکامرس','حذف 11 ابزارک فروشگاهی (سبد، فیلتر قیمت و...)',false);
                    bm_item('wc_blocks_frontend','حذف استایل بلوک‌های فروشگاه در فرانت','حذف CSS بلوک‌های ووکامرس در سایت — کاهش CSS',true);
                    bm_item('wc_blocks_backend','حذف استایل بلوک در بک‌اند','حذف wc-block-editor',false);
                    bm_item('wc_scripts','حذف اسکریپت ووکامرس در صفحات غیر فروشگاهی','اسکریپت‌ها فقط در فروشگاه/سبد/تسویه لود شود — کاهش درخواست',true);
                    bm_item('wc_cart_fragments','حذف Cart Fragments','حذف درخواست AJAX سبد خرید در همه صفحات',true);
                    bm_item('wc_stripe','حذف اسکریپت Stripe','غیرفعال‌سازی Payment Request در صفحه محصول',false);
                    bm_item('wc_mobile_footer','حذف فوتر موبایل از ایمیل','حذف متن تبلیغ اپ ووکامرس در ایمیل سفارش',false);
                    bm_item('wc_payment_providers','مخفی‌سازی پیشنهاد درگاه پرداخت','مخفی کردن “Discover other payment providers”',false);
                    bm_item('wc_skyverge','حذف داشبورد SkyVerge','حذف منوی SkyVerge',false);
                    bm_item('wc_recommended_shipping','حذف پیشنهاد حمل‌ونقل','حذف متاباکس حمل‌ونقل پیشنهادی',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-block">
                <div class="bm-card"><h2>ویرایشگر بلوک (گوتنبرگ)</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('disable_gutenberg','غیرفعال‌سازی کامل گوتنبرگ','بازگشت به ویرایشگر کلاسیک — برای همه نوشته‌ها',false);
                    bm_item('widget_block_editor','حذف ویرایشگر بلوکی ابزارک‌ها','بازگشت به ابزارک کلاسیک',false);
                    bm_item('welcome_guide','بستن راهنمای خوش‌آمد','عدم نمایش Welcome Guide در اولین ورود به ویرایشگر',false);
                    bm_item('block_directory','حذف دایرکتوری بلوک','حذف پیشنهاد نصب بلوک از مخزن',false);
                    bm_item('default_patterns','حذف الگوهای پیش‌فرض','حذف پترن‌های پیش‌فرض وردپرس',false);
                    bm_item('fullscreen_mode','خروج خودکار از تمام‌صفحه','ویرایشگر همیشه با حالت عادی باز شود',false);
                    bm_item('template_editor','غیرفعال‌سازی ویرایشگر قالب','حذف پشتیبانی block-templates',false);
                    bm_item('remote_patterns','غیرفعال‌سازی الگوهای راه‌دور','عدم لود الگو از WordPress.org',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-third">
                <div class="bm-card"><h2>افزونه‌های جانبی</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('jetpack_nag','حذف هشدار جت‌پک','حذف ناگ اتصال جت‌پک',false);
                    bm_item('jetpack_promo','حذف تبلیغات جت‌پک','حذف پیشنهادهای jtm',false);
                    bm_item('jetpack_blaze','غیرفعال‌سازی Blaze','حذف تبلیغات Blaze جت‌پک',false);
                    bm_item('elementor_widget','حذف ویجت المنتور از داشبورد','حذف e-dashboard-overview',false);
                    bm_item('elementor_google_fonts','جلوگیری از Google Fonts المنتور','عدم لود فونت گوگل المنتور در فرانت — بهبود حریم‌خصوصی و سرعت',true);
                    bm_item('yoast_premium','حذف تبلیغ پریمیوم یواست','حذف ناگ ارتقا به Premium',false);
                    bm_item('yoast_admin_bar','حذف یواست از نوار بالا','حذف منوی سئو از ادمین‌بار',false);
                    bm_item('yoast_comments','حذف کامنت HTML یواست','حذف کامنت دیباگ یواست از سورس صفحه',false);
                    bm_item('yoast_widget','حذف ویجت یواست','حذف wpseo-dashboard-overview',false);
                    bm_item('cf7_assets','حذف CSS/JS فرم 7','حذف wpcf7_load_js/css — فقط در صفحات دارای فرم لود شود',true);
                    bm_item('acf_menu','مخفی‌سازی منوی ACF','مخفی کردن ACF در ادمین برای مشتری',false);
                    bm_item('wpml_meta','حذف متای WPML','حذف متای چندزبانه از head',false);
                    bm_item('updraftplus_bar','حذف UpdraftPlus از نوار بالا','حذف آیتم پشتیبان‌گیری',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-ai">
                <div class="bm-card"><h2>هوش مصنوعی — غیرفعال‌سازی AI</h2><p class="bm-item-desc">وردپرس 6.8+ و افزونه‌های دارای AI با فیلتر رسمی بدون هک.</p>
                    <div class="bm-grid3">
                    <?php
                    bm_item('wp_ai','غیرفعال‌سازی AI هسته وردپرس','فیلتر wp_supports_ai — غیرفعال‌سازی قابلیت‌های AI داخلی',true);
                    bm_item('elementor_ai','غیرفعال‌سازی AI المنتور','حذف AI Builder و Copilot المنتور',true);
                    bm_item('rankmath_ai','غیرفعال‌سازی AI رنک‌مث','حذف Content AI رنک‌مث',true);
                    bm_item('yoast_ai','غیرفعال‌سازی AI یواست','حذف تولید AI یواست',false);
                    bm_item('jetpack_ai','غیرفعال‌سازی AI جت‌پک','حذف دستیار AI جت‌پک',false);
                    bm_item('bertha_ai','غیرفعال‌سازی Bertha AI','حذف دستیار نوشتاری Bertha',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-privacy">
                <div class="bm-card"><h2>حریم‌خصوصی و امنیت</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('xmlrpc','غیرفعال‌سازی XML-RPC','بستن کامل xmlrpc.php با 403 + حذف هدر X-Pingback — جلوگیری از حملات Brute Force',true);
                    bm_item('rest_api','غیرفعال‌سازی REST API برای مهمان','کاربران لاگین‌نشده 401 می‌گیرند — لاگین کرده‌ها مجازند',false);
                    bm_item('rest_api_links','حذف لینک REST از head','حذف rest_output_link',false);
                    bm_item('user_enumeration','مسدودسازی حدس نام کاربری','بستن ?author= — جلوگیری از شناسایی یوزرها',false);
                    bm_item('author_archives','غیرفعال‌سازی آرشیو نویسنده','ریدایرکت نویسنده به خانه + حذف از نقشه سایت',false);
                    bm_item('unregister_tags','حذف برچسب‌ها','حذف تاکسونومی post_tag از نوشته',false);
                    bm_item('force_gd','اجبار GD به جای Imagick','اولویت کتابخانه GD برای پردازش تصویر',false);
                    bm_item('core_privacy','غیرفعال‌سازی ابزار حریم‌خصوصی','حذف ابزارهای export/erase داده',false);
                    bm_item('auto_update_themes','غیرفعال‌سازی آپدیت خودکار قالب','جلوگیری از آپدیت خودکار پوسته‌ها',false);
                    bm_item('auto_update_plugins','غیرفعال‌سازی آپدیت خودکار افزونه','جلوگیری از آپدیت خودکار افزونه‌ها',false);
                    bm_item('auto_update_core','غیرفعال‌سازی آپدیت خودکار هسته','جلوگیری از آپدیت خودکار وردپرس',false);
                    bm_item('empty_trash','سطل زباله 7 روزه','ثبت EMPTY_TRASH_DAYS=7 — خالی شدن خودکار سطل زباله',false);
                    ?>
                    </div>
                </div>
            </div>
            <div class="bm-panel" id="tab-extra">
                <div class="bm-card"><h2>متفرقه</h2>
                    <div class="bm-grid3">
                    <?php
                    bm_item('plugin_suggestions','حذف پیشنهاد نصب افزونه','حذف تب‌های “ویژه/محبوب/پیشنهادی” در افزودن افزونه — <b>غیرفعال‌سازی واقعی</b> با فیلتر plugins_api_results و install_plugins_tabs، نه فقط CSS. درخواست به api.wordpress.org لغو و تب‌ها حذف می‌شوند — <b>روی سرعت نصب تاثیر مثبت دارد</b>',true,'این گزینه واقعا API را فیلتر می‌کند، نه فقط مخفی‌سازی ظاهری');
                    bm_item('plugin_popular_tags','حذف تگ‌های محبوب','مخفی‌سازی باکس “تگ‌های محبوب” در افزودن افزونه — صرفا ظاهری با CSS',false);
                    ?>
                    </div>
                    <h3>اعلان‌های ادمین — مخفی‌سازی تکی (مثل Disable Admin Notices)</h3><p class="bm-item-desc">در حالت “فقط انتخابی”، کنار هر اعلان دو دکمه کوچک «مخفی» و «مخفی همیشگی» ظاهر می‌شود. اعلان‌های مخفی در نوار بالا قابل بازیابی هستند.</p>
                    <div class="bm-grid3">
                        <div class="bm-item"><div class="bm-item-text"><div class="bm-item-title">حالت نمایش اعلان‌ها</div><div class="bm-item-desc">نحوه مدیریت اعلان‌ها</div><select name="<?php echo esc_attr(BLOAT_MANAGER_OPT);?>[admin_notices_mode]" class="bm-number" style="max-width:220px;margin-top:6px"><option value="not_hide" <?php selected($opts['admin_notices_mode'],'not_hide');?>>عدم مخفی‌سازی</option><option value="only_selected" <?php selected($opts['admin_notices_mode'],'only_selected');?>>فقط انتخابی (با دکمه)</option><option value="all" <?php selected($opts['admin_notices_mode'],'all');?>>مخفی همه</option><option value="compact_panel" <?php selected($opts['admin_notices_mode'],'compact_panel');?>>پنل فشرده</option></select></div></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}
