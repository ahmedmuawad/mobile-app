@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">قائمة العملاء</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary mb-3">إضافة عميل جديد</a>

    @if($customers->count() > 0)
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- روابط الترقيم --}}
        {{ $customers->links('pagination::bootstrap-5') }}

    @else
        <p>لا يوجد عملاء حالياً.</p>
    @endif
</div>
@endsection
