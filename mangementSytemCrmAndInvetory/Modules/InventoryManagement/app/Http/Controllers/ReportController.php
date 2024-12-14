<?php

namespace Modules\InventoryManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\InventoryManagement\Extra\InvoiceType;
use Modules\InventoryManagement\Models\ProductInvoice;

class ReportController extends Controller
{
    public function __construct(ProductInvoice $model){
        parent::__construct($model);
    }

    public function makeReport(Request $request){
        if (!$request->date){
            $date = Carbon::now()->subMonth();
        } else {
            $date = Carbon::parse($request->date);
        }
        $result =[
            "purchase" => $this->getReportElments($date, InvoiceType::Out),
            "sells" => $this->getReportElments($date, InvoiceType::In)
        ];
        return response()->json(["message" => "done", "data" => $result], 200);
    }

    public function getReportElments($date, InvoiceType $type){
        return  DB::table("product_invoices")
            ->join('invoices', 'product_invoices.invoice_id', '=', 'invoices.id')
            ->join('products', 'product_invoices.product_id', '=', 'products.id')
            ->select("products.id",
                "products.name",
                "invoices.type",
                DB::raw("SUM(product_invoices.quantity) as total_quantity"),
                DB::raw('SUM(product_invoices.price) as total_price')
            )
            ->whereMonth("invoices.created_at", $date->month)
            ->whereYear(    "invoices.created_at", $date->year)
            ->where("invoices.type", $type)
            ->groupBy('products.id', 'products.name', "invoices.type")
            ->get();
    }
}
