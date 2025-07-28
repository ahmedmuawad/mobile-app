<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Repair;
use App\Models\Setting;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('customer', 'sparePart')->latest()->get();
        return view('admin.views.repairs.index', compact('repairs'));
    }

    public function create()
    {
        $customers = Customer::all();
        $categories = Category::all();
        return view('admin.views.repairs.create', compact('customers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'          => 'nullable|exists:customers,id',
            'customer_name'        => 'nullable|string|max:255',
            'device_type'          => 'required|string|max:255',
            'problem_description'  => 'required|string',
            'spare_part_id'        => 'nullable|exists:products,id',
            'repair_cost'          => 'required|numeric|min:0',
            'status'               => 'required|in:جاري,تم الإصلاح,لم يتم الإصلاح',
            'discount'             => 'nullable|numeric|min:0',
        ]);

        $sparePartPrice = 0;
        if ($request->spare_part_id) {
            $sparePart = Product::find($request->spare_part_id);
            $sparePartPrice = $sparePart?->sale_price ?? 0;
        }

        $total = $sparePartPrice + $request->repair_cost - ($request->discount ?? 0);
        if ($total < 0) $total = 0;

        $repair = new Repair();
        $repair->customer_id         = $request->customer_id;
        $repair->customer_name       = $request->customer_name;
        $repair->device_type         = $request->device_type;
        $repair->problem_description = $request->problem_description;
        $repair->spare_part_id       = $request->spare_part_id;
        $repair->repair_cost         = $request->repair_cost;
        $repair->discount            = $request->discount ?? 0;
        $repair->total               = $total;
        $repair->status              = $request->status;
        $repair->save();

        return redirect()->route('admin.repairs.index')->with('success', '✅ تم حفظ فاتورة الصيانة بنجاح.');
    }

    public function edit($id)
    {
        $repair     = Repair::findOrFail($id);
        $customers  = Customer::all();
        $categories = Category::all();
        $spareParts = Product::all();

        return view('admin.views.repairs.edit', compact('repair', 'customers', 'categories', 'spareParts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id'          => 'nullable|exists:customers,id',
            'customer_name'        => 'nullable|string|max:255',
            'device_type'          => 'required|string|max:255',
            'problem_description'  => 'required|string',
            'spare_part_id'        => 'nullable|exists:products,id',
            'repair_cost'          => 'required|numeric|min:0',
            'status'               => 'required|in:جاري,تم الإصلاح,لم يتم الإصلاح',
            'discount'             => 'nullable|numeric|min:0',
        ]);

        $repair = Repair::findOrFail($id);

        $sparePartPrice = 0;
        if ($request->spare_part_id) {
            $sparePart = Product::find($request->spare_part_id);
            $sparePartPrice = $sparePart?->sale_price ?? 0;
        }

        $total = $sparePartPrice + $request->repair_cost - ($request->discount ?? 0);
        if ($total < 0) $total = 0;

        $repair->customer_id         = $request->customer_id;
        $repair->customer_name       = $request->customer_name;
        $repair->device_type         = $request->device_type;
        $repair->problem_description = $request->problem_description;
        $repair->spare_part_id       = $request->spare_part_id;
        $repair->repair_cost         = $request->repair_cost;
        $repair->discount            = $request->discount ?? 0;
        $repair->total               = $total;
        $repair->status              = $request->status;

        $repair->save();

        return redirect()->route('admin.repairs.index')->with('success', '✅ تم تحديث الفاتورة بنجاح.');
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return redirect()->route('admin.repairs.index')->with('success', '🗑️ تم حذف الفاتورة بنجاح.');
    }

    public function show($id)
{
    $repair = Repair::with(['sparePart', 'customer'])->findOrFail($id);

    // جلب الإعدادات العامة
    $globalSetting = Setting::first(); // هذا هو الصحيح

  

    return view('admin.views.repairs.show', [
        'repair'        => $repair,
        'sparePart'     => $repair->sparePart,
        'customer'      => $repair->customer,
        'globalSetting' => $globalSetting
    ]);
}

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get(['id', 'name', 'sale_price']);
        return response()->json($products);
    }
}
