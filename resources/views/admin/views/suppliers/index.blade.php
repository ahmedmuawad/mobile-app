@extends('layouts.app')

@section('title', 'قائمة الموردين')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">قائمة الموردين</h3>

<a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> إضافة مورد
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد</th>
                        <th>العنوان</th>
                        <th>الرصيد</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone ?? '---' }}</td>
                            <td>{{ $supplier->email ?? '---' }}</td>
                            <td>{{ $supplier->address ?? '---' }}</td>
                            <td>
                                @php $balance = $supplier->balance; @endphp
                                <span class="{{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($balance, 2) }} جنيه
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-info">تعديل</a>

                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">لا يوجد موردون حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
