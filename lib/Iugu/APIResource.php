<?php

namespace unaspbr\Iugu;

class APIResource extends IuguObject
{

    private static $_apiRequester = null;

    public static function convertClassToObjectType()
    {
        $object_type = explode('\\', strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', get_called_class())));

        return mb_strtolower(array_pop($object_type), 'UTF-8');
    }

    public static function objectBaseURI()
    {
        $object_type = self::convertClassToObjectType();
        
        switch ($object_type) {
            // Add Exceptions as needed
        case 'charge':
        case 'payment_token':
        case 'bank_verification':
        case 'marketplace':
            return $object_type; // WORKAROUND MARKETPLACE
        default:
            return $object_type . 's';
        }
    }

    public static function API()
    {
        if (self::$_apiRequester == null) {
            self::$_apiRequester = new APIRequest();
        }

        return self::$_apiRequester;
    }

    public static function endpointAPI($object = null, $uri_path = '')
    {   
        $path = '';

        if (is_string($object)) {
            $path = '/' . $object;
        } elseif (is_object($object) && (isset($object['id']))) {
            $path = '/' . $object['id'];
        } elseif (is_object($object) && (isset($object['account_id']))) { // WORKAROUND MARKETPLACE/ACCOUNT
            $path = '/' . $object['account_id'];
        }
        if (isset($object['action'])) { // WORKAROUND MARKETPLACE/ACCOUNT
            if (isset($object['id']))
                $path .= '/' . $object['id'];
            elseif (isset($object['account_id']))
                $path .= '/' . $object['account_id'];
            $path .= '/' . $object['action'];
        }
        return Iugu::getBaseURI() . $uri_path . '/' . self::objectBaseURI() . $path;
    }

    public static function url($object = null)
    {
        return self::endpointAPI($object);
    }

    protected static function createFromResponse($response)
    {   
        return Factory::createFromResponse(
            self::convertClassToObjectType(), $response
        );
    }

    public static function create($attributes = [])
    {
        $response = self::createFromResponse(
            self::API()->request('POST', static::url($attributes), $attributes)
        );
        
        foreach ($attributes as $attr => $value) {
            $response[$attr] = $value;
        }

        return $response;
    }

    public function delete()
    {
        if ($this['id'] == null) {
            return false;
        }

        try {
            $response = self::API()->request(
                'DELETE', static::url($this)
            );

            if (isset($response->errors)) {
                throw new IuguException();
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public static function search($options = [])
    {
        try {
            $response = self::API()->request(
                'GET', static::url($options), $options
            );

            return self::createFromResponse($response);
        } catch (Exception $e) {

        }

        return [];
    }

    public static function fetch($key)
    {
        try {
            $response = static::API()->request(
                'GET', static::url($key)
            );

            return self::createFromResponse($response);
        } catch (IuguObjectNotFound $e) {
            throw new IuguObjectNotFound(self::convertClassToObjectType(get_called_class()) . ':' . ' not found');
        }
    }

    public function refresh()
    {
        if ($this->is_new()) {
            return false;
        }

        try {
            $response = self::API()->request(
                'GET', static::url($this)
            );

            if (isset($response->errors)) {
                throw new IuguObjectNotFound();
            }

            $new_object = self::createFromResponse($response);
            $this->copy($new_object);
            $this->resetStates();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function save()
    {
        try {
            $response = self::API()->request(
                $this->is_new() ? 'POST' : 'PUT', static::url($this), $this->modifiedAttributes()
            );

            $new_object = self::createFromResponse($response);
            $this->copy($new_object);
            $this->resetStates();

            if (isset($response->errors)) {
                throw new IuguException();
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
