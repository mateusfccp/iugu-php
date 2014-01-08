<?php

namespace unaspbr\Iugu;

class Customer extends APIResource
{
    public function payment_methods()
    {
        return new APIChildResource(['customer_id' => $this->id], 'PaymentMethod');
    }

    public function invoices()
    {
        return new APIChildResource(['customer_id' => $this->id], 'Invoice');
    }

    // TODO: (WAITING BUGFIX) get DefaultPaymentMethod and return
    public function default_payment_method()
    {
        if (!isset($this->id)) {
            return false;
        }
      
        if (!isset($this->default_payment_method_id)) {
            return false;
        }
      
        if (!$this->default_payment_method_id) {
            return false;
        }

        return PaymentMethod::fetch([
            'customer_id' => $this->id,
            'id'          => $this->default_payment_method_id,
        ]);
    }
}
