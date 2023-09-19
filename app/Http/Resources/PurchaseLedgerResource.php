<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseLedgerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->created_at->format('Y-m-d'),
            'invoice' => $this->facture()->latest()->first()->Ref,
            'manual' => $this->Ref,
            'debit' => $this->GrandTotal,
            'credit' => $this->paid_amount,
            'tax' => $this->TaxNet,
            'discount' => $this->discount,
            'balance' => 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
