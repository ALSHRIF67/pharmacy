@extends('layouts.app')

@section('header_title', 'حالة مزامنة البيانات')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-100 text-center">
            <div class="inline-flex items-center justify-center p-4 bg-emerald-50 rounded-full mb-4">
                <svg class="w-12 h-12 text-emerald-500 {{ $totalPending > 0 ? 'animate-pulse' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-800">حالة المزامنة السحابية</h3>
            <p class="text-gray-500 mt-2">مراقبة البيانات التي تم إنشاؤها أوفلاين وتنتظر الرفع للسيرفر</p>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">المنتجات</div>
                <div class="text-2xl font-black {{ $syncStats['products'] > 0 ? 'text-amber-500' : 'text-gray-400' }}">
                    {{ $syncStats['products'] }}
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">التشغيلات</div>
                <div class="text-2xl font-black {{ $syncStats['batches'] > 0 ? 'text-amber-500' : 'text-gray-400' }}">
                    {{ $syncStats['batches'] }}
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">المبيعات</div>
                <div class="text-2xl font-black {{ $syncStats['sales'] > 0 ? 'text-amber-500' : 'text-gray-400' }}">
                    {{ $syncStats['sales'] }}
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                <div class="text-xs font-bold text-gray-400 uppercase mb-1">المشتريات</div>
                <div class="text-2xl font-black {{ $syncStats['purchases'] > 0 ? 'text-amber-500' : 'text-gray-400' }}">
                    {{ $syncStats['purchases'] }}
                </div>
            </div>
        </div>

        <div class="p-8 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center text-sm font-bold {{ $totalPending > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                @if($totalPending > 0)
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    يوجد {{ $totalPending }} سجل بانتظار المزامنة
                @else
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    جميع البيانات متزامنة بالكامل
                @endif
            </div>
            
            @if($totalPending > 0)
                <form action="{{ route('sync.push') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-10 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                        مزامنة الآن
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
