@extends('layouts.app')

@section('title', 'قائمة المبيعات')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">قائمة المبيعات</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.sales.create') }}" class="btn btn-primary mb-3">إضافة فاتورة جديدة</a>

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>اسم العميل (يدوي)</th>
                <th>الإجمالي</th>
                <th>الربح</th>
                <th>التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->customer?->name ?? '-' }}</td>
                    <td>{{ $sale->customer_name ?? '-' }}</td>
                    <td>{{ number_format($sale->total, 2) }} جنيه</td>
                    <td>{{ number_format($sale->profit, 2) }} جنيه</td>
                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a href="{{ route('admin.sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">تعديل</a>

                        <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7">لا توجد مبيعات حتى الآن</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $sales->links() }}
    </div>
</div>
@endsection
