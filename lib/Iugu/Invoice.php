<?php

namespace unaspbr\Iugu;

class Invoice extends APIResource
{
    public function customer()
    {
        if (!isset($this->customer_id)) {
            return false;
        }
        
        if (!$this->customer_id) {
            return false;
        }

        return Customer::fetch($this->customer_id);
    }

    public function cancel()
    {
        if ($this->is_new()) {
            return false;
        }

        try {
            $response = self::API()->request('PUT', static::url($this) . '/cancel');
            
            if (isset($response->errors)) {
                throw new IuguRequestException($response->errors);
            }
            
            $new_object = self::createFromResponse($response);
            $this->copy($new_object);
            $this->resetStates();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function refund()
    {
        if ($this->is_new()) {
            return false;
        }

        try {
            $response = self::API()->request('POST', static::url($this).'/refund');
            
            if (isset($response->errors)) {
                throw new IuguRequestException($response->errors);
            }
            
            $new_object = self::createFromResponse($response);
            $this->copy($new_object);
            $this->resetStates();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
<<<<<<< HEAD
	
	public function duplicate($options=Array()) {
		if ($this->is_new()) return false;

		try {
			$response = self::API()->request("POST", static::url($this) . "/duplicate", $options);
            
			if (isset($response->errors)) {
				throw new IuguRequestException($response->errors);
			}
			return self::createFromResponse($response);
		} catch (Exception $e) {
			return false;
		}

	return true;
  }  
	
=======

    public function capture()
    {
        if ($this->is_new()) {
            return false;
        }

        try {
            $response = self::API()->request('POST', static::url($this) . '/capture');
            
            if (isset($response->errors)) {
                throw new IuguRequestException($response->errors);
            }
            
            $new_object = self::createFromResponse($response);
            $this->copy($new_object);
            $this->resetStates();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
>>>>>>> 79a7541... Implementation of the capture invoice method
}
