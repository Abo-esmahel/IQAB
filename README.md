# IQAB — README

<p align="center">
  <strong>منصة متكاملة للأرقام الافتراضية والخدمات الرقمية — الشراء والدعم عبر تيليجرام.</strong>
</p>

<p align="center">
  🌐 <strong>الموقع الحي:</strong> <a href="http://188.40.151.183">http://188.40.151.183</a> &nbsp;·&nbsp; ✅ يعمل الآن
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Redis-7-DC382D?style=for-the-badge&logo=redis" alt="Redis">
  <img src="https://img.shields.io/badge/Tests-64_passing-2ea44f?style=for-the-badge" alt="Tests">
</p>

---

## 📌 نظرة عامة

**IQAB** متجر رقمي متكامل مبني على **Laravel 13**: يتصفح المستخدم الأرقام الافتراضية والخدمات الرقمية والعروض، ويشتري عبر دعم العملاء على **تيليجرام** — لا محفظة داخلية ولا بوابة دفع، مما يبسّط البنية ويقلل المخاطر الأمنية.

> فصل "المنتج" عن "الدفع": كل العمليات المالية تتم خارج الموقع عبر تيليجرام بوساطة فريق الدعم.

---

## ✨ المميزات

### للمستخدمين
- 📞 **سوق أرقام افتراضية** — تصفح، بحث، فلترة (دولة/سعر)، ترتيب (الأحدث/السعر)، وصفحات تفاصيل.
- 🗂️ **أرقامي** — إدارة كاملة: لقب شخصي، **استبدال** الرقم بآخر متاح، **تحرير/إلغاء** الرقم.
- 📬 **صندوق وارد SMS** فوري لكل رقم + لوحة تحكم بإحصائيات ورسائل حية.
- 🤖 **أدوات تيليجرام** — بحث/تبليغ/معلومات الحسابات مع تقارير جميلة وحالة لحظية وسجل طلبات.
- 🔐 **حسابات آمنة** — تسجيل بخطوات تحقق قوية، دخول مع "تذكّرني"، **استرجاع كلمة المرور بالبريد**، تغيير كلمة المرور، ملف شخصي.
- 📧 **إشعارات بريدية** — ترحيب، إسناد رقم، نتائج تيليجرام، تنبيهات قرب الانتهاء.
- 📱 **متجاوب كلياً** مع الجوال + منع زوم iOS + صور متجاوبة.

### للإدارة (`/admin`)
- 👥 **إدارة مستخدمين كاملة** — إضافة (مستخدم/أدمن بكلمة سر)، تعديل، إيقاف/تفعيل، حذف، إسناد أرقام — مع حماية ذاتية.
- 📊 **لوحة KPIs** — إيرادات (مفصلة)، مستخدمون، مشتريات، طلبات تيليجرام، رسم تسجيلات 30 يوماً، أعلى الدول.
- 📞 أرقام، ⚡ خدمات، 🔥 عروض، 📣 طرق تواصل (20 منصة بشعارات حقيقية + رفع شعار مخصص)، 🤖 خدمات تيليجرام، 📝 إعدادات، 🪝 سجلات Webhooks، 🕵️ سجلات تدقيق.
- 🖼️ **رفع صور محلي** (JPG/PNG/GIF/WebP حتى 2MB) للخدمات والعروض والأرقام وجهات التواصل — مع معاينة وحذف تلقائي للقديم.

---

## 🏗️ البنية التقنية

| المجال | التقنية |
|--------|---------|
| الإطار / اللغة | Laravel 13 · PHP 8.4 |
| قاعدة البيانات | MySQL 8 (مضبوطة لذاكرة منخفضة) |
| الكاش/الجلسات/الطوابير | Redis 7 |
| الخادم | Nginx + PHP-FPM + OPcache + JIT |
| الواجهة | Blade + Tailwind CSS (بناء Vite) + Alpine.js |
| البريد | SMTP (Gmail) عبر طابور Redis |
| المهام المجدولة | تنبيهات الانتهاء (ساعياً)، إغلاق المنتهية (يومياً)، مزامنة المزود (كل 15 دقيقة) |
| الاختبارات | PHPUnit — **64 اختباراً** (مصادقة، رفع صور، بحث، تيليجرام، بريد، أمان) |
| الأمان | CSRF، حدّ معدل، سياسات تفويض، تحقق HMAC للـ webhooks، fail2ban، UFW، نسخ احتياطي يومي |

---

## 🔐 الأمان

- كلمات مرور قوية (8+ أحرف وأرقام)، تشفير جلسات، كوكيز `HttpOnly` و`SameSite`.
- منع تثبيت الجلسة (تجديد بعد الدخول)، حماية IDOR عبر Policies، منع تصعيد الصلاحيات.
- رفع ملفات مقيد الأنواع والحجم، وروابط خارجية لا تُمس عند الحذف.
- على السيرفر: جدار ناري، حظر التخمين، إخفاء إصدارات البرمجيات، ترويسات أمنية، حدود معدل Nginx، نسخ احتياطي يومي مع احتفاظ 7 أيام.
- ⚠️ **ملاحظة:** الموقع يعمل عبر HTTP حالياً — ربط دومين + HTTPS (مجاناً) موصى به بشدة لحماية كلمات المرور.

---

## 🚀 التشغيل المحلي

```bash
git clone https://github.com/Abo-esmahel/IQAB.git
cd IQAB

composer install
cp .env.example .env
php artisan key:generate

# قاعدة بيانات SQLite للتطوير السريع
touch database/database.sqlite
php artisan migrate --seed

npm install && npm run build
php artisan serve
```

افتح `http://localhost:8000`.

| الدور | البريد | كلمة المرور |
|------|--------|-------------|
| أدمن | `admin@iqab.com` | `password` |
| مستخدم | `demo@iqab.com` | `password` |

---

## ⚙️ إعداد الإنتاج

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=http://188.40.151.183

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=iqab
DB_USERNAME=iqab
DB_PASSWORD=...

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
```

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
# عامل الطابور (systemd): php artisan queue:work redis
# المجدول (cron): * * * * * php artisan schedule:run
```

### Webhooks لدى المزوّدين

| الخدمة | الرابط |
|--------|-------|
| مزوّد الأرقام | `APP_URL/api/webhooks/phone/messages` |
| تيليجرام | `APP_URL/api/webhooks/telegram` |

> محمية بتوقيع HMAC/secret وتُرفض بدون سر مهيأ + حدّ معدل 120/دقيقة.

---

## 🧪 الاختبارات

```bash
php artisan test
# 64 اختباراً — مصادقة واسترجاع، رفع صور، بحث وفلترة، تيليجرام، بريد، أرقامي، أمان
```

---

## 📂 بنية المشروع

```
app/
├ Actions/ Console/ Enums/
├ Http/
│   ├── Controllers/ (Auth, Admin/*, Marketplace, MyNumber, Telegram, ...)
│   ├── Middleware/ (Admin, Maintenance, Active)
│   ├── Requests/ (Auth, Admin, CatalogFilter, Telegram...)
│   └── Concerns/ (HandlesImageUploads)
├ Mail/ (Welcome, NumberAssigned, TelegramStatus)
├ Notifications/ (QueuedResetPassword, Expiring, MessageReceived)
├ Models/ Policies/ Services/
database/ (migrations, seeders)   resources/views/   routes/   tests/
```

---

## 👤 المؤلف

**[@Abo-esmahel](https://github.com/Abo-esmahel)** — مطوّر Full-Stack (Laravel / PHP): المتجر، دمج تيليجرام والبريد، الأمان، وإدارة السيرفر.

## 📄 الترخيص

لأغراض التعلم والعمل — راجع مالك المشروع للاستخدام التجاري.
