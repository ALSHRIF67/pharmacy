@extends('layouts.app')

@section('header_title', 'الموردين')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-700">جميع الموردين</h3>
            <a href="{{ route('suppliers.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                إضافة مورد جديد
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">اسم المورد</th>
                        <th class="px-6 py-4 font-semibold">الشخص المسؤول</th>
                        <th class="px-6 py-4 font-semibold">رقم الهاتف</th>
                        <th class="px-6 py-4 font-semibold">عدد المشتريات</th>
                        <th class="px-6 py-4 font-semibold text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $supplier->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $supplier->contact_person ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono">
                                {{ $supplier->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $supplier->purchases_count }} فاتورة
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2 space-x-reverse">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="inline-block text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                لا يوجد موردين حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
