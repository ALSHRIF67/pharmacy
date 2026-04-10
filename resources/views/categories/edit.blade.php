@extends('layouts.app')

@section('header_title', 'تعديل القسم')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700">تعديل: {{ $category->name }}</h3>
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم القسم</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('categories.index') }}" class="px-6 py-3 text-gray-500 hover:text-gray-700 font-bold transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                    تحديث القسم
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
