
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فارما هب | نظام إدارة الصيدلية المتكامل</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=IBM+Plex+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM+Plex+Sans+Arabic', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(2, 164, 156, 0.05) 0%, rgba(255, 255, 255, 0) 100%), #f8fafc;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(2, 164, 156, 0.1);
            border-color: rgba(2, 164, 156, 0.3);
        }
        .text-gradient {
            background: linear-gradient(135deg, #02a49c 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-shadow {
            box-shadow: 0 10px 20px -5px rgba(2, 164, 156, 0.4);
        }
    </style>
</head>
<body class="antialiased text-slate-800">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="max-w-7xl mx-auto px-6 py-8 flex justify-between items-center">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-lg transform -rotate-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <span class="text-2xl font-black text-slate-900 tracking-tight">فارما<span class="text-emerald-600">هب</span></span>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="<?php echo e(route('pos.index')); ?>" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition btn-shadow">ابدأ البيع الآن</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h1 class="text-6xl font-black text-slate-900 leading-tight mb-6">
                    إدارة صيدليتك أصبحت <br>
                    <span class="text-gradient">أكثر ذكاءً وسهولة</span>
                </h1>
                <p class="text-xl text-slate-500 mb-10 leading-relaxed max-w-lg">
                    نظام متكامل لإدارة المخزون، المبيعات، الطلبيات والتقارير المالية مع دعم كامل للعمل بدون إنترنت (Offline-First).
                </p>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <div class="text-3xl font-black text-slate-900"><?php echo e(number_format($stats['today_sales'], 0)); ?></div>
                        <div class="text-sm text-slate-500 font-bold">مبيعات اليوم</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900"><?php echo e($stats['total_products']); ?></div>
                        <div class="text-sm text-slate-500 font-bold">إجمالي الأصناف</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-rose-500"><?php echo e($stats['low_stock_count']); ?></div>
                        <div class="text-sm text-slate-500 font-bold">نواقص المخزون</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s"></div>
                
                <div class="relative glass-card rounded-[2.5rem] p-4 p-8 border-white p-2 border-emerald-100 p-2 shadow-2xl overflow-hidden min-h-[400px] flex items-center justify-center">
                   <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-50 rounded-full mb-6">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 mb-2">النظام جاهز ومؤمن</h4>
                        <p class="text-slate-500">تم فحص جميع البيانات والمخزون الحالي بنجاح.</p>
                   </div>
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="max-w-7xl mx-auto px-6 pb-24">
            <h2 class="text-3xl font-black text-slate-900 mb-12 flex items-center">
                <span class="w-10 h-1 bg-emerald-600 block ml-4 rounded-full"></span>
                لوحة التحكم السريعة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- POS -->
                <a href="<?php echo e(route('pos.index')); ?>" class="glass-card p-8 rounded-[2rem] text-right group">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 15v6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 font-black">نقطة البيع (POS)</h3>
                    <p class="text-slate-500 text-sm">واجهة بيع سريعة تدعم الباركود والعمل بدون إنترنت.</p>
                </a>

                <!-- Products -->
                <a href="<?php echo e(route('products.index')); ?>" class="glass-card p-8 rounded-[2rem] text-right group">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 font-black">الأدوية والمخزون</h3>
                    <p class="text-slate-500 text-sm">متابعة الأرصدة، تاريخ انتهاء الصلاحية، والتشغيلات.</p>
                </a>

                <!-- Purchases -->
                <a href="<?php echo e(route('purchases.index')); ?>" class="glass-card p-8 rounded-[2rem] text-right group">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 font-black">المشتريات</h3>
                    <p class="text-slate-500 text-sm">تسجيل فواتير الموردين وتحديث المخزون تلقائياً.</p>
                </a>

                <!-- Reports -->
                <a href="<?php echo e(route('reports.index')); ?>" class="glass-card p-8 rounded-[2rem] text-right group">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 font-black">التقارير</h3>
                    <p class="text-slate-500 text-sm">تحليل المبيعات، الأرباح، والنواقص والديون.</p>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-200">
            <div class="flex flex-col md:flex-row justify-between items-center text-slate-400 text-sm font-bold">
                <div>&copy; <?php echo e(date('Y')); ?> فارما هب - جميع الحقوق محفوظة</div>
                <div class="mt-4 md:mt-0 flex space-x-6 space-x-reverse">
                    <a href="<?php echo e(route('sync.index')); ?>" class="hover:text-emerald-600 transition">حالة المزامنة</a>
                    <a href="<?php echo e(route('customers.index')); ?>" class="hover:text-emerald-600 transition">العملاء</a>
                    <a href="<?php echo e(route('suppliers.index')); ?>" class="hover:text-emerald-600 transition">الموردين</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\xamppp\htdocs\ph\resources\views/welcome.blade.php ENDPATH**/ ?>