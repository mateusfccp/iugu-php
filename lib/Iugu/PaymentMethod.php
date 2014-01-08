<?php

namespace unaspbr\Iugu;

class PaymentMethod extends APIResource
{
    public static function url($object = null)
    {
        if (!isset($object['customer_id'])) {
            throw new IuguException('Missing Customer ID');
        }

        $customer_id = $object['customer_id'];
        $object_id = null;

        if (isset($object['id'])) {
            $object_id = $object['id'];
        }

        return self::endpointAPI($object_id, "/customers/{$object['customer_id']}");
    }

    // TODO: (FUTURE) Allow charge from here
}
