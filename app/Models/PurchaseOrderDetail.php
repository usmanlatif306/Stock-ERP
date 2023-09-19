<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    protected $fillable = [
        'id', 'product_id', 'purchase_order_id', 'sale_unit_id', 'total', 'quantity', 'product_variant_id', 'price', 'TaxNet', 'discount', 'discount_method', 'tax_method',
    ];

    protected $casts = [
        'total' => 'double',
        'quantity' => 'double',
        'price' => 'double',
        'TaxNet' => 'double',
        'discount' => 'double',
        'purchase_order_id' => 'integer',
        'sale_unit_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
    ];

    public function purchase_order()
    {
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
