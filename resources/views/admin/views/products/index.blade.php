@extends('layouts.app')

@section('content')
<div class="container">
    <!-- عنوان الصفحة وزر الإضافة -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">قائمة المنتجات</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> إضافة منتج جديد
        </a>
    </div>

    <!-- جدول المنتجات -->
    @if ($products->isEmpty())
        <p>لا توجد منتجات حالياً.</p>
    @else
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>الاسم</th>
                            <th style="width: 120px;">سعر الشراء</th>
                            <th style="width: 120px;">سعر البيع</th>
                            <th style="width: 80px;">الكمية</th>
                            <th>التصنيف</th>
                            <th style="width: 70px;">الصورة</th>
                            <th style="width: 180px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->purchase_price, 2) }} ج.م</td>
                                <td>{{ number_format($product->sale_price, 2) }} ج.م</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->category->name ?? 'بدون تصنيف' }}</td>
                                <td class="text-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <small class="text-muted">لا توجد صورة</small>
                                    @endif
                                </td>
                                <td>
    <div class="d-flex gap-1">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i> تعديل
        </a>

        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i> حذف
            </button>
        </form>
    </div>
</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
