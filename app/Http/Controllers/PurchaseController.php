<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    // عرض فواتير المشتريات
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('admin.views.purchases.index', compact('purchases'));
    }

    // صفحة إنشاء فاتورة
    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        return view('admin.views.purchases.create', compact('suppliers', 'products'));
    }

    // حفظ الفاتورة الجديدة
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items'       => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.purchase_price'=> 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'notes'       => $request->notes,
                'total_amount'=> 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                $oldStock = $product->stock;
                $oldCost  = $product->purchase_price;
                $qty      = $item['quantity'];
                $price    = $item['purchase_price'];

                $newStock = $oldStock + $qty;
                $avgCost  = ($oldStock * $oldCost + $qty * $price) / ($newStock ?: 1);

                $product->update([
                    'stock'          => $newStock,
                    'purchase_price' => $avgCost,
                ]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'subtotal'    => $qty * $price,
                ]);

                $totalAmount += $qty * $price;
            }

            $purchase->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', '✅ تم حفظ الفاتورة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    // صفحة تعديل الفاتورة
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        $purchase->load('items');

        return view('admin.views.purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    // تعديل الفاتورة
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items'       => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.purchase_price'=> 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // استرجاع الكميات القديمة للمخزون
            foreach ($purchase->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                $product->stock -= $oldItem->quantity;
                $product->save();
            }

            // حذف العناصر القديمة
            $purchase->items()->delete();

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'notes'       => $request->notes,
            ]);

            $totalAmount = 0;

            // إضافة الأصناف الجديدة
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $qty     = $item['quantity'];
                $price   = $item['purchase_price'];

                // تحديث المخزون ومتوسط التكلفة
                $oldStock = $product->stock;
                $oldCost  = $product->purchase_price;

                $newStock = $oldStock + $qty;
                $avgCost  = ($oldStock * $oldCost + $qty * $price) / ($newStock ?: 1);

                $product->update([
                    'stock' => $newStock,
                    'purchase_price' => $avgCost,
                ]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'subtotal'    => $qty * $price,
                ]);

                $totalAmount += $qty * $price;
            }

            $purchase->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', '✅ تم تعديل الفاتورة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء التعديل: ' . $e->getMessage());
        }
    }

    // حذف الفاتورة
    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();

        try {
            // استرجاع الكميات للمخزون
            foreach ($purchase->items as $item) {
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            // حذف الأصناف والفاتورة
            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->route('admin.purchases.index')->with('success', '✅ تم حذف الفاتورة بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
