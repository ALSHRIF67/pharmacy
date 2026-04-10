@extends('layouts.app')

@section('header_title', 'إضافة عميل جديد')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700">معلومات العميل</h3>
            <a href="{{ route('customers.index') }}" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم العميل</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"
                    placeholder="اسم العميل الرباعي">
            </div>

            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"
                    placeholder="05xxxx xxxx">
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none"
                    placeholder="example@mail.com">
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                    حفظ بيانات العميل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
