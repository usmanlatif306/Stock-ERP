<?php

namespace App\Http\Controllers;

use App\Models\AdjustmentDetail;
use App\Models\ProductVariant;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\SaleReturnDetails;
use App\Models\Transfer;
use App\Models\TransferDetail;
use Illuminate\Http\Request;

class GeneralReportController extends Controller
{
    public function productReport($warehoue_id, $id)
    {
        // product purchase reports
        $purchase_details = PurchaseDetail::with(['purchase', 'product'])->where('product_id', $id)->whereHas('purchase', function ($query) use ($warehoue_id) {
            $query->where('warehouse_id', $warehoue_id);
        })->get();
        $purchases = [];

        foreach ($purchase_details as $purchase_detail) {
            $purchase['name'] = $purchase_detail->product->name;
            if ($purchase_detail->product_variant_id) {
                $purchase['varient'] = ProductVariant::find($purchase_detail->product_variant_id)->name;
            } else {
                $purchase['varient'] = '-';
            }
            $purchase['supplier'] = $purchase_detail->purchase->provider->name;
            $purchase['status'] = $purchase_detail->purchase->statut;
            $purchase['expiry'] = $purchase_detail->expiry_date;
            $purchase['price'] = $purchase_detail->cost;
            $purchase['quantity'] = $purchase_detail->quantity;
            $purchase['total'] = $purchase_detail->total;
            $purchase['payment_status'] = $purchase_detail->purchase->payment_statut;

            $purchases[] = $purchase;
        }

        // product sales reports
        $sales_details = SaleDetail::with(['sale'])->where('product_id', $id)->whereHas('sale', function ($query) use ($warehoue_id) {
            $query->where('warehouse_id', $warehoue_id);
        })->get();
        $sales = [];

        foreach ($sales_details as $sale_detail) {
            $sale['date'] = $sale_detail->sale->date;
            $sale['Ref'] = $sale_detail->sale->Ref;
            $sale['client_name'] = $sale_detail->sale->client->name;
            $sale['status'] = $sale_detail->sale->statut;
            $sale['GrandTotal'] = $sale_detail->sale->GrandTotal;
            $sale['paid_amount'] = $sale_detail->sale->paid_amount;
            $sale['due'] = $sale_detail->sale->GrandTotal - $sale_detail->sale->paid_amount;
            $sale['payment_status'] = $sale_detail->sale->payment_statut;

            $sales[] = $sale;
        }



        // sales returns reports
        $sale_returns_details = SaleReturnDetails::with(['SaleReturn'])->where('product_id', $id)->get();
        $sale_returns = [];
        dd($sale_returns_details);

        foreach ($sale_returns_details as $sale_returns_detail) {
            $sale_return['date'] = $sale_returns_detail->SaleReturn->date;
            $sale_return['Ref'] = $sale_returns_detail->SaleReturn->Ref;
            $sale_return['client_name'] = $sale_returns_detail->SaleReturn->client->name;
            $sale_return['status'] = $sale_returns_detail->SaleReturn->statut;
            $sale_return['GrandTotal'] = $sale_returns_detail->SaleReturn->GrandTotal;
            $sale_return['paid_amount'] = $sale_returns_detail->SaleReturn->paid_amount;
            $sale_return['due'] = $sale_returns_detail->SaleReturn->GrandTotal - $sale_returns_detail->SaleReturn->paid_amount;
            $sale_return['payment_status'] = $sale_returns_detail->SaleReturn->payment_statut;

            $sale_returns[] = $sale_return;
        }

        // transers reports
        $transfers_details = TransferDetail::with(['transfer', 'product'])->where('product_id', $id)->where('product_id', $id)->whereHas('SaleReturn', function ($query) use ($warehoue_id) {
            $query->where(['from_warehouse_id' => $warehoue_id, 'to_warehouse_id' => $warehoue_id]);
        })->get();

        $transfers = [];

        foreach ($transfers_details as $transfers_detail) {
            $transfer['date'] = $transfers_detail->transfer->date;
            $transfer['Ref'] = $transfers_detail->transfer->Ref;
            $transfer['from'] = $transfers_detail->transfer->from_warehouse->name;
            $transfer['to'] = $transfers_detail->transfer->to_warehouse->name;
            $transfer['product'] = $transfers_detail->product->name;
            $transfer['quantity'] = $transfers_detail->quantity;
            $transfer['total'] = $transfers_detail->total;
            $transfer['status'] = $transfers_detail->transfer->statut;

            $transfers[] = $transfer;
        }

        // adjustments reports
        $adjustments_details = AdjustmentDetail::with(['adjustment', 'product'])->where('product_id', $id)->whereHas('adjustment', function ($query) use ($warehoue_id) {
            $query->where('warehouse_id', $warehoue_id);
        })->get();

        $adjustments = [];

        foreach ($adjustments_details as $adjustments_detail) {
            $adjustment['date'] = $adjustments_detail->adjustment->date;
            $adjustment['Ref'] = $adjustments_detail->adjustment->Ref;
            $adjustment['warehouse'] = $adjustments_detail->adjustment->warehouse->name;
            $adjustment['product'] = $adjustments_detail->product->name;
            $adjustment['quantity'] = $adjustments_detail->quantity;
            $adjustment['status'] = $adjustments_detail->type == 'sub' ? 'substraction' : 'addition';

            $adjustments[] = $adjustment;
        }


        return response()->json([
            'purchases_rows' => count($purchases),
            'purchases' => $purchases,
            'sales_rows' => count($sales),
            'sales' => $sales,
            'sale_returns_rows' => count($sale_returns),
            'sale_returns' => $sale_returns,
            'transfers_rows' => count($transfers),
            'transfers' => $transfers,
            'adjustments_rows' => count($adjustments),
            'adjustments' => $adjustments
        ]);
        // dd($purchases);
    }
}
