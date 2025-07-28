@extends('layouts.app')

@section('title', 'فواتير الصيانة')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📄 فواتير الصيانة</h3>
        <a href="{{ route('admin.repairs.create') }}" class="btn btn-primary">➕ فاتورة جديدة</a>
    </div>

    @if ($repairs->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>نوع الجهاز</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقى</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td>{{ $repair->id }}</td>
                            <td>
                                @if($repair->customer)
                                    {{ $repair->customer->name }}
                                @else
                                    {{ $repair->customer_name ?? '---' }}
                                @endif
                            </td>
                            <td>{{ $repair->device_type }}</td>
                            <td>
                                @php
                                    $color = match($repair->status) {
                                        'جاري' => 'warning',
                                        'تم الإصلاح' => 'success',
                                        'لم يتم الإصلاح' => 'danger',
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $repair->status }}</span>
                            </td>
                            <td>{{ number_format($repair->total, 2) }} جنيه</td>
                            @php
                                    $paidAmount = $repair->payments->sum('amount');
                                    $remaining = $repair->total - $paidAmount;
                                @endphp
                            <td><strong>{{ number_format($repair->payments->sum('amount'), 2) }}</strong> جنيه</td>
                            <td><strong>{{ number_format($repair->total - $repair->payments->sum('amount'), 2) }}</strong> جنيه</td>
                            <td>{{ $repair->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.repairs.show', $repair->id) }}" class="btn btn-sm btn-info">👁️ عرض</a>
                                <a href="{{ route('admin.repairs.edit', $repair->id) }}" class="btn btn-sm btn-warning">✏️ تعديل</a>
                                <form action="{{ route('admin.repairs.destroy', $repair->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑️ حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">لا توجد فواتير صيانة بعد.</div>
    @endif
</div>
@endsection
