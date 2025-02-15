<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Customer as CustomerResource;
use App\Http\Resources\Admin\Country as CountryResource;
use App\Http\Resources\Admin\State as StateResource;
use App\Http\Resources\Admin\Currency as CurrencyResource;
use App\Http\Resources\Admin\OrderDetail as OrderDetailResource;
use App\Models\Admin\Currency;
use App\Http\Resources\Admin\Comment as CommentResource;
class Order extends JsonResource
{
    public function toArray($request)
    {
        $this->exchange_rate = 1;
        $currency = [];
        if (isset($request->currency) && $request->currency != '') {
             $currency = Currency::findByCurrencyId($request->currency)->select('exchange_rate', 'symbol_position', 'code')->first();
             $this->exchange_rate = $currency->exchange_rate;
        }

        $orderType = 'Cash';
        if ($this->is_preorder) {
            $orderType = 'Purchase Order';
        } else if ($this->is_contract) {
            $orderType = 'Kontrak';
        }

        return [
            'order_id' => $this->id,
            'customer_id' => new CustomerResource($this->whenLoaded('customer')),
            'warehouse_id' => $this->warehouse_id,
            'order_from' => $this->order_from,
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_company' => $this->billing_company,
            'billing_street_aadress' => $this->billing_street_aadress,
            'billing_suburb' => $this->billing_suburb,
            'billing_city' => $this->billing_city,
            'billing_postcode' => $this->billing_postcode,
            'billing_country' => $this->billing_country,
            'billing_country1' => new CountryResource($this->whenLoaded('billing_country1')),
            'billing_state1' => new StateResource($this->whenLoaded('billing_state1')),
            'billing_state' => $this->billing_state,
            'billing_phone' => $this->billing_phone,
            'delivery_first_name' => $this->delivery_first_name,
            'delivery_last_name' => $this->delivery_last_name,
            'delivery_company' => $this->delivery_company,
            'delivery_street_aadress' => $this->delivery_street_aadress,
            'delivery_suburb' => $this->delivery_suburb,
            'delivery_city' => $this->delivery_city,
            'delivery_postcode' => $this->delivery_postcode,
            'delivery_country' => $this->delivery_country,
            'delivery_country1' => new CountryResource($this->whenLoaded('delivery_country1')),
            'delivery_state1' => new StateResource($this->whenLoaded('delivery_state1')),
            'delivery_state' => $this->delivery_state,

            'delivery_phone' => $this->delivery_phone,
            'cc_type' => $this->cc_type,
            'cc_owner' => $this->cc_owner,
            'cc_number' => $this->cc_number,
            'cc_expiry' => $this->cc_expiry,
            'currency_id' => new CurrencyResource(Currency::defaultCurrency()->firstOrFail()),
            'currency' => $currency,
            'currency_value' => $this->currency_value,
            'order_price' => $this->order_price,
            'shipping_cost' => $this->shipping_cost,
            'shipping_method' => $this->shipping_method,
            'shipping_duration' => $this->shipping_duration,
            'order_notes' => $this->order_notes,
            'is_seen' => $this->is_seen,
            'coupon_code' => $this->coupon_code,
            'coupon_amount' => $this->coupon_amount,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'order_status' => $this->order_status,
            'delivery_boy_id' => $this->delivery_boy_id,
            'total_tax' => $this->total_tax,
            'order_date' => date('d/m/y', strtotime($this->created_at)),
            'order_detail' => OrderDetailResource::collection($this->whenLoaded('detail')),
            'order_history' => $this->order_history,
            'order_notes' => $this->ordernotes,
            'order_comments' => CommentResource::collection($this->ordercomments),
            'latlong' => $this->latlong,

            'shipping_ro_method' => \Str::upper($this->shipping_ro_method),
            'shipping_ro_service' => $this->shipping_ro_service,
            'shipping_ro_province_id' => $this->shipping_ro_province_id,
            'shipping_ro_province' => $this->shipping_ro_province,
            'shipping_ro_city_id' => $this->shipping_ro_city_id,
            'shipping_ro_city' => $this->shipping_ro_city,
            'shipping_ro_resi_number' => $this->shipping_ro_resi_number ?? '-',

            'nota' => $this->nota,
            'is_po' => $this->is_preorder,
            'po_use_dp' => $this->po_use_dp,
            'is_contract' => $this->is_contract,
            'contract_payment_date_recurring' => $this->contract_payment_date_recurring,
            'po_due_date' => $this->po_due_date,
            'order_type' => $orderType,
        ];
    }
}
