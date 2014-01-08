<?php

namespace unaspbr\Iugu;

class Plan extends APIResource
{
    public static function fetchByIdentifier($identifier) {
        try {
            $response = self::API()->request("GET", static::url() . "/identifier/" . $identifier);
            
            if (isset($response->errors)) {
                return false;
            }
            
            $new_object = self::createFromResponse( $response );
            return $new_object;
        } catch (Exception $e) {
            return false;
        }
        
        return false;
    }
}
