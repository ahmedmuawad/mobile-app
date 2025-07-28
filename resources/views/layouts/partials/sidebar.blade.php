<aside class="main-sidebar sidebar-dark-primary elevation-4" style="right: 0; left: auto;">
    <!-- اسم المتجر -->
    <a href="/" class="brand-link text-center">
        @if($globalSetting?->logo)
            <img src="{{ asset('storage/' . $globalSetting->logo) }}" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light d-block">
            {{ $globalSetting?->store_name ?? 'اسم المتجر' }}
        </span>
    </a>

    <!-- القائمة -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ Request::is('admin/home*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-house-user"></i>
                        <p>الصفحة الرئيسية</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>التصنيفات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>المنتجات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>العملاء</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sales.index') }}" class="nav-link {{ Request::is('admin/sales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>المبيعات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.repairs.index') }}" class="nav-link {{ Request::is('admin/repairs*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>فواتير الصيانة</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.purchases.index') }}" class="nav-link {{ Request::is('admin/purchases*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>المشتريات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ Request::is('admin/suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>الموردين</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ Request::is('admin/expenses*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>المصروفات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>الاعدادات</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ Request::is('admin/reports/*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('admin/reports/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
            التقارير
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview pl-3">
        <li class="nav-item">
            <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ Request::is('admin/reports/sales*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير المبيعات</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reports.purchases') }}" class="nav-link {{ Request::is('admin/reports/purchases*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>تقرير المشتريات</p>
            </a>
        </li>
    </ul>
</li>

                {{-- روابط إضافية مستقبلاً --}}
            </ul>
        </nav>
    </div>
</aside>
