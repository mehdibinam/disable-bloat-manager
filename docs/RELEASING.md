# انتشار نسخه‌ی جدید / Releasing a new version

دکمه‌ی به‌روزرسانی داخل افزونه از آخرین **Release** گیت‌هاب نسخه را می‌خواند.
The in-plugin update button reads the latest GitHub **Release**.

1. شماره‌ی نسخه را در `disable-bloat-manager.php` (هدر `Version:` و ثابت `MB_VERSION`)، `readme.txt` و `CHANGELOG.md` بالا ببرید.
2. پوشه‌ی افزونه را با نام ریشه‌ی `disable-bloat-manager` فشرده کنید و فایل را `disable-bloat-manager-vX.Y.zip` بنامید.
3. در گیت‌هاب یک Release بسازید: تگ `vX.Y.Z` (باید از نسخه‌ی نصب‌شده بزرگ‌تر باشد)، فایل ZIP را پیوست کنید. پیش‌نویس و Pre-release بررسی نمی‌شوند.
4. حداکثر یک ساعت بعد (یا به‌محض باز کردن صفحه‌ی افزونه، اگر کش قدیمی باشد) دکمه‌ی به‌روزرسانی ظاهر می‌شود.

اگر ZIP پیوست نشود، افزونه از بسته‌ی خودکار گیت‌هاب (zipball) استفاده می‌کند و نام پوشه را خودش اصلاح می‌کند.
If no ZIP asset is attached, the plugin falls back to GitHub's zipball and fixes the folder name itself.
