@extends('layouts.app')

@section('title', 'إعدادات المتجر')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">إعدادات المتجر</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>اسم المتجر:</label>
            <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $setting->store_name) }}" required>
        </div>

        <div class="form-group">
            <label>عنوان المتجر:</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $setting->address) }}">
        </div>

        <div class="form-group">
            <label>رقم الهاتف:</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $setting->phone) }}">
        </div>

        <div class="form-group">
            <label>رسالة أسفل الفاتورة:</label>
            <textarea name="invoice_footer" class="form-control">{{ old('invoice_footer', $setting->invoice_footer) }}</textarea>
        </div>

        <div class="form-group">
            <label>شعار المتجر:</label><br>
            @if($setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" width="100" class="mb-2">
            @endif
            <input type="file" name="logo" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
    </form>
</div>
@endsection
