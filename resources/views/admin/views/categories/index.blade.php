@extends('layouts.app')

@section('content')
<div class="container">
    <!-- عنوان الصفحة وزر الإضافة -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">قائمة التصنيفات</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة تصنيف جديد
        </a>
    </div>

    <!-- جدول التصنيفات -->
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>الاسم</th>
                        <th style="width: 150px;">تاريخ الإنشاء</th>
                        <th style="width: 180px;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا التصنيف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا توجد تصنيفات حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
