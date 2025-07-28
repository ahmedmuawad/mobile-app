@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- العنوان -->
    <div class="row mb-3">
        <div class="col">
            <h1 class="m-0 text-dark">لوحة التحكم</h1>
        </div>
    </div>

    <!-- كروت الإحصائيات -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>150</h3>
                    <p>الطلبات الجديدة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">المزيد <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53%</h3>
                    <p>نسبة الإنجاز</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <a href="#" class="small-box-footer">المزيد <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>44</h3>
                    <p>المستخدمين المسجلين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">المزيد <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>
                    <p>الزيارات اليوم</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="#" class="small-box-footer">المزيد <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
