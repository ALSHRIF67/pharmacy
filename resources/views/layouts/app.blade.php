<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Pharmacy ERP') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <div class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col transition-all duration-300">
        <div class="p-6 flex items-center justify-center border-b border-slate-800">
            <span class="text-2xl font-bold tracking-wider text-emerald-400">فارما سنتر</span>
        </div>
        
        <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
            <a href="{{ url('/') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('/') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>الرئيسية</span>
            </a>

            <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('products*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span>المنتجات</span>
            </a>

            <a href="{{ route('pos.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('pos*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>نقطة البيع (POS)</span>
            </a>

            <a href="{{ route('purchases.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('purchases*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>المشتريات</span>
            </a>

            <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('categories*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <span>الأقسام</span>
            </a>

            <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('suppliers*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>الموردين</span>
            </a>

            <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('customers*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>العملاء</span>
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('reports*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>التقارير</span>
            </a>

            <a href="{{ route('sync.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ Request::is('sync*') ? 'bg-emerald-500 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>حالة المزامنة</span>
            </a>
        </nav>

        <div class="p-4 bg-slate-800 text-slate-400 text-xs text-center">
            &copy; {{ date('Y') }} Pharmacy ERP
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen">
        <!-- Topbar -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
            <h2 class="text-xl font-semibold text-gray-800">
                @yield('header_title', 'لوحة القيادة')
            </h2>
            <div class="flex items-center space-y-2">
                <span class="text-sm text-gray-500 ml-4">أهلاً بك، {{ auth()->user()->name ?? 'مدير النظام' }}</span>
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold">
                    أ
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-auto p-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
