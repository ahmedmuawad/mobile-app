@extends('layouts.app') {{-- غيّر لو عندك لياوت مختلف --}}

@section('title', 'تقرير المبيعات')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">تقرير المبيعات</h3>

    {{-- نموذج فلترة بالتاريخ --}}
    <form method="GET" action="{{ route('admin.reports.sales')
 }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>من تاريخ</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>إلى تاريخ</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">عرض التقرير</button>
        </div>
    </form>

    {{-- عرض النتائج --}}
    @if($report)
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">عدد الفواتير</h5>
                        <p class="card-text fs-4">{{ $report->invoices_count }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المبيعات</h5>
                        <p class="card-text fs-4">{{ number_format($report->total_sales, 2) }} ج</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي الربح</h5>
                        <p class="card-text fs-4">{{ number_format($report->total_profit, 2) }} ج</p>
                    </div>
                </div>
            </div>
        </div>

        @if(count($sales))
    <h3 class="mt-5 mb-3">تفاصيل الفواتير</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>العميل</th>
                <th>المجموع</th>
                <th>الربح</th>
                <th>التاريخ</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->customer->name ?? 'غير معروف' }}</td>
                    <td>{{ number_format($sale->total, 2) }} ج</td>
                    <td>{{ number_format($sale->profit, 2) }} ج</td>
                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            عرض الفاتورة
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="mt-4">لا توجد فواتير في الفترة المحددة.</p>
@endif

    @elseif(request('from') && request('to'))
        <div class="alert alert-warning">لا توجد بيانات في هذه الفترة.</div>
    @endif


</div>
@endsection
