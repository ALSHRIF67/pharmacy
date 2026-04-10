# 🏗️ PharmaHub ERP | نظام فارما هب المتكامل لادارة الصيدليات

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)

**PharmaHub** هو نظام ERP متطور ومصمم خصيصاً للصيدليات الرائدة. يتميز النظام بواجهة عصرية تدعم اللغة العربية (RTL) بشكل كامل، مع التركيز على الموثوقية العالية والعمل في بيئات متنوعة (Offline-First).

---

## ✨ المميزات الرئيسية | Core Features

### 🛒 نقطة البيع الذكية (POS)
- واجهة بيع سريعة وسهلة الاستخدام.
- دعم كامل لماسح الباركود.
- معالجة المبيعات وفق مبدأ **FEFO** (الأقدم صلاحية يباع أولاً).
- إصدار فواتير فورية.

### 📦 إدارة المخزون والمستودعات
- تتبع الأصناف بالتشغيلات (Batch Numbers).
- تنبيهات متقدمة للكميات المنخفضة.
- تتبع تواريخ الانتهاء تلقائياً.
- سجل كامل لحركات الاستلام والصرف (Stock Movements).

### 📊 التقارير والإحصائيات
- لوحة تحكم تفاعلية (Dashboard) تعرض مبيعات اليوم والشهر.
- تحليل الأصناف الأكثر مبيعاً.
- تتبع ديون الموردين والعملاء.

### 🔄 المزامنة والأمان
- نظام **Offline-First**: القدرة على تسجيل المبيعات حتى عند انقطاع الإنترنت.
- مزامنة البيانات تلقائياً عند استعادة الاتصال.
- نظام صلاحيات متكامل (Admin Panel).

---

## 🛠 التكنولوجيا المستخدمة | Tech Stack

- **Backend**: Laravel 11 (Latest Stable).
- **Frontend**: Tailwind CSS + Blade Components.
- **Database**: MySQL (Central) + IndexedDB (Local POS Cache).
- **Real-time**: Ajax / Axios for seamless data flow.

---

## 🚀 البدء والتركيب | Quick Start

### 📋 المتطلبات
- PHP >= 8.2
- Composer
- MySQL Server

### 🔧 خطوات التثبيت
1. **استنساخ المستودع**:
   ```bash
   git clone https://github.com/ALSHRIF67/pharmacy.git
   cd pharmacy
   ```

2. **تثبيت الملحقات**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **إعداد البيئة**:
   - قم بنسخ ملف `.env.example` إلى `.env`.
   - قم بتحديث بيانات قاعدة البيانات.
   ```bash
   php artisan key:generate
   ```

4. **تهجير قاعدة البيانات**:
   ```bash
   php artisan migrate --seed
   ```

5. **تشغيل النظام**:
   ```bash
   php artisan serve
   ```

---

## 📸 نظرة على الواجهة | UI Preview
النظام مصمم بأحدث معايير تجربة المستخدم (UX) ويستخدم تقنية **Glassmorphism** في لوحة التحكم الرئيسية لتقديم تجربة بصرية مريحة واحترافية.

---

## 📄 الترخيص | License
هذا المشروع مفتوح المصدر ومتاح تحت رخصة **MIT**.

---

<p align="center">تم التطوير بكل ❤️ لدعم قطاع الصيدلة</p>
