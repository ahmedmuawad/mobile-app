@extends('layouts.app')

@section('title', 'فواتير المشتريات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>فواتير المشتريات</h4>
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">+ إضافة فاتورة</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>المورد</th>
                    <th>الإجمالي</th>
                    <th>التاريخ</th>
                    <th>ملاحظات</th>
                    <th>الخيارات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->supplier->name ?? '---' }}</td>
                    <td>{{ number_format($purchase->total, 2) }} جنيه</td>
                    <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $purchase->notes ?? '---' }}</td>
                    <td>
                        <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn-sm btn-info">تعديل</a>

                        <form action="{{ route('admin.purchases.destroy', $purchase->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">لا توجد فواتير حتى الآن.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
