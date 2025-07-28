@extends('layouts.app')

@section('title', 'قائمة المصروفات')

@section('content')
<div class="container">
    <h4>قائمة المصروفات</h4>
    <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary mb-3">➕ إضافة مصروف</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الوصف</th>
                <th>القيمة</th>
                <th>التاريخ</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->name }}</td>
                <td>{{ $expense->description ?? '-' }}</td>
                <td>{{ number_format($expense->amount, 2) }} جنيه</td>
                <td>{{ $expense->date }}</td>
                <td>
                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('هل أنت متأكد؟')" class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $expenses->links() }}
</div>
@endsection
