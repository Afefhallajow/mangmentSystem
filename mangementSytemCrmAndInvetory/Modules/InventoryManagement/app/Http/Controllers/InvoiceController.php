<?php

namespace Modules\InventoryManagement\Http\Controllers;

use App\Helper\ResponseMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\InventoryManagement\Extra\InvoiceType;
use Modules\InventoryManagement\Models\Invoice;
use Modules\InventoryManagement\Models\Product;

class InvoiceController extends Controller
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function Save(Request $request){
        DB::beginTransaction();  // Start the transaction
        try {


            $request->validate([
                'name' => 'required|string|max:255',
                "type" => 'required|string|max:255',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',

            ]);
            $productIds = collect($request->products)->pluck('id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $invoice = Invoice::create([
                "name" => $request->name,
                "type" => $request->type,
                "total" => 0
            ]);

            $sum = 0;
            foreach ($request->products as $product) {
                $dbProduct = $products->get($product["id"]);
                if ($invoice->type == InvoiceType::Out && ($dbProduct->quantity < $product["quantity"] ||
                    $dbProduct->price < $product["price"])) {
                    throw new \RuntimeException("quantity should be less or same and price should be the same with product price for "
                        . $dbProduct->name . " available quantity " . $dbProduct->quantity . " price " . $dbProduct->price);
                }
                $invoice->products()->attach($product['id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price']
                ]);
                $sum += $product["price"] * $product["quantity"];

                //remove quantity from product
                switch ($invoice->type) {
                    case InvoiceType::Out:
                        $dbProduct->quantity = $dbProduct->quantity - $product["quantity"];
                        $dbProduct->save();
                        break;
                    case InvoiceType::In:
                        $dbProduct->quantity += $product["quantity"];
                        $dbProduct->save();
                        break;
                }
            }
            $invoice->total = $sum;
            $invoice->save();
            DB::commit();
            return ResponseMessage::successResponse("done", $invoice, 201);
        } catch (\Exception $e){
            DB::rollBack();
            // Return the error message
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
