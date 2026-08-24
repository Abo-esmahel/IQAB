<p align="center">
  <img src="./logo.svg" alt="IQAB" width="340">
</p>

<p align="center">
  <strong>منصة متكاملة للأرقام الافتراضية والخدمات الرقمية، مدعومة بنظام محفظة مالي آمن ومدفوعات موثّقة.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Tests-PHPUnit-4F5D95?style=for-the-badge&logo=phpunit" alt="Tests">
  <img src="https://img.shields.io/badge/License-Custom-2ea44f?style=for-the-badge" alt="License">
</p>

---

## 📌 نظرة عامة

**IQAB** مشروع ويب تعليمي/تطبيقي مبني على **Laravel**، يقدّم تجربة "متجر رقمي" كاملة: يستطيع المستخدم تصفّح وشراء **الأرقام الافتراضية** و**الخدمات الرقمية** (مثل التحقق من حسابات تليجرام)، مع نظام **محفظة مالية** مرتبط ببوابة دفع حقيقية.

> الهدف المعماري: فصل "المنتج" عن "طريقة الدفع" — أي عملية مالية لا تُعتمد إلا بعد تأكيد خارجي من بوابة الدفع، مع حماية كاملة ضد التلاعب بالرصيد.

---

## ✨ المميزات

- 📞 **سوق أرقام افتراضية** — تصفّح، شراء، واستقبال الرسائل الواردة.
- 🤖 **خدمات رقمية** — التحقق/التبليغ/جلب معلومات حسابات تليجرام عبر مزوّد خارجي.
- 💳 **محفظة آمنة** — شحن (موثّق)، خصم، واسترداد مع سجل حركات كامل.
- 🛡️ **لوحة تحكم للأدمن** — مستخدمون، أرقام، خدمات، مدفوعات، تدقيق، Webhooks، وإعدادات.
- 🔌 **تكامل مرن** — بوابة الدفع، مزوّد الأرقام، والتليجرام تُضبط من الإعدادات دون تعديل الكود.
- 🔔 **إشعارات** — شراء ناجح، رسالة واردة، واقتراب انتهاء الرقم.

---

## 🏗️ البنية التقنية

| المجال | التقنية / الأداة |
|--------|------------------|
| الإطار | Laravel 13 (PHP) |
| اللغة | PHP 8.4 |
| قاعدة البيانات | SQLite (تطوير) / MySQL (إنتاج) |
| المهام الخلفية | Queue Jobs (مزامنة الرسائل وحالة الأرقام) |
| التكامل الخارجي | Laravel HTTP Client + تحقق Webhook (HMAC) |
| الواجهة | Blade + Tailwind-style utility classes |
| الاختبارات | PHPUnit |
| الأمان | Policies، Rate Limit، تواقيع، معاملات DB |

---

## 🔐 الأمان (ممارسات الإنتاج)

تم بناء المشروع مع مراعاة معايير الأمان:

- **إيداع موثّق**: لا يُضاف أي رصيد إلا بعد تأكيد الدفع من بوابة الدفع عبر webhook — لا وجود لإيداع وهمي.
- **حماية Webhooks**: كل الـ webhooks محمية بتواقيع (HMAC / Secret) وترفض الأسرار غير المُهيّأة أو الوهمية (`TODO_…`).
- **سلامة المعاملات**: العمليات المالية ضمن `DB::transaction` مع `lockForUpdate` لمنع سباقات الرصيد (race conditions).
- **الجلسات**: مشفّرة (`SESSION_ENCRYPT`) مع كوكيز آمنة تُفعّل تلقائياً في الإنتاج.
- **تحديد المعدل**: `throttle` على المسارات الحسّاسة (الإيداع).
- **تفويض على مستوى الكائن**: `Policies` تمنع الوصول لبيانات المستخدمين الآخرين (IDOR protection).
- **وضع صيانة**: قابل للتفعيل من الإدمن ويمنع عمليات العملاء.

---

## 🚀 التشغيل المحلي

```bash
# 1. استنساخ المشروع
git clone https://github.com/Abo-esmahel/IQAB.git
cd IQAB

# 2. تثبيت الاعتماديات
composer install

# 3. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 4. قاعدة البيانات والبيانات الأولية
php artisan migrate --seed

# 5. تشغيل الخادم
php artisan serve
```

افتح `http://localhost:8000`.

### حسابات افتراضية (بعد الـ seed)

| الدور | البريد | كلمة المرور |
|------|--------|-------------|
| أدمن | `admin@iqab.com` | `password` |
| مستخدم تجريبي | `demo@iqab.com` | `password` |

---

## ⚙️ الإعداد للإنتاج

في ملف `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=...
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_USERNAME=...
MAIL_PASSWORD=...

SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
```

ومن لوحة الإدمن `/admin/settings` أدخل بيانات الربط:

- **بوابة الدفع**: `gateway_url`، `gateway_key`، `gateway_secret`
- **مزوّد الأرقام**: `base_url`، `token`، `webhook_secret`
- **التليجرام**: `api_url`، `api_token`، `bot_token`، `webhook_secret`

اربط الـ webhooks لدى المزوّدين:

| الخدمة | الرابط |
|--------|-------|
| بوابة الدفع | `https://your-domain.com/api/webhooks/payments/deposit` (هيدر `X-Gateway-Secret`) |
| مزوّد الأرقام | `https://your-domain.com/api/webhooks/phone/messages` |
| التليجرام | `https://your-domain.com/api/webhooks/telegram` |

---

## 🧪 الاختبارات

```bash
vendor/bin/phpunit
```

تشمل التغطية: منطق المحفظة، تدقيق الإدمن، حماية الإيداع (عدم إضافة رصيد قبل التأكيد)، ورفض الـ webhooks غير المُهيّأة.

---

## 📂 بنية المشروع

```
app/
├── Actions/         # منطق الشراء والمزامنة
├── Enums/           # الأنواع المعدّدة (الحالات، المعاملات، الدفع...)
├── Http/
│   ├── Controllers/ # متحكّمات المستخدمين والإدمن
│   ├── Middleware/  # الصيانة، الصلاحيات، تفعيل المستخدم
│   └── Requests/    # قواعد تحقق المدخلات
├── Models/          # النماذج (User, Wallet, Payment, PhoneNumber, Service...)
├── Policies/        # تفويض على مستوى الكائن
└── Services/        # تكامل البوابة، المزوّد، التليجرام، المحفظة
```

---

## 💼 المهارات التقنية المكتسبة (Portfolio)

هذا المشروع يجسّد المهارات التالية ضمن بيئة عمل واقعية:

- **تطوير Full-Stack بـ Laravel** (Routing, Controllers, Models, Migrations, Seeders).
- **تصميم أنظمة دفع آمنة** مع فصل المسؤوليات وتأكيد خارجي (payment gateway + webhook verification).
- **حماية المعاملات المالية** عبر قيود قاعدة البيانات (`lockForUpdate`) ومنع السباقات.
- **تطبيق مفاهيم الأمان**: سياسات تفويض، حماية CSRF، تواقيع HMAC، Rate Limiting.
- **هندسة التكامل الخارجي** (Provider/Adapter pattern) مع قابلية الضبط من لوحة التحكم.
- **كتابة اختبارات آلية** (PHPUnit) تغطي السيناريوهات الحرجة والأمنية.
- **مراجعة كود أمنية** وتصحيح ثغرات منطقية (مثل منع الإيداع الوهمي).

---

## 👤 المؤلف

**تم تطوير المشروع بواسطة** [@Abo-esmahel](https://github.com/Abo-esmahel).

- الدور: مطوّر Full-Stack (Laravel / PHP).
- المسؤوليات: بناء المتجر، نظام المحفظة والمدفوعات، طبقة التكامل الخارجي، والأمان.

---

## 📄 الترخيص

مشروع مقدّم لأغراض التعلم والعمل. راجع مالك المشروع للترخيص والاستخدام.
