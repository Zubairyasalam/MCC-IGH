<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class PayUService
{
    protected ?string $key;
    protected ?string $salt;
    protected string $mode;
    protected ?string $url;

    public function __construct()
    {
        $this->key = config('services.payu.key');
        $this->salt = config('services.payu.salt');
        $this->mode = config('services.payu.mode', 'test');
        $this->url = $this->mode === 'production' 
            ? config('services.payu.production_url') 
            : config('services.payu.test_url');
            
        if (empty($this->key) || empty($this->salt)) {
            Log::error('PayU credentials are not set in the configuration.');
            throw new \RuntimeException('PayU credentials (PAYU_KEY / PAYU_SALT) are not configured in .env.');
        }
    }

    /**
     * Generate hash for PayU request
     * Hash format: sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||salt)
     */
    public function generateHash(array $params): string
    {
        $hashString = sprintf(
            '%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s||||||%s',
            $this->key,
            $params['txnid'],
            $params['amount'],
            $params['productinfo'],
            $params['firstname'],
            $params['email'],
            $params['udf1'] ?? '',
            $params['udf2'] ?? '',
            $params['udf3'] ?? '',
            $params['udf4'] ?? '',
            $params['udf5'] ?? '',
            $this->salt
        );

        return hash('sha512', $hashString);
    }

    /**
     * Verify hash for PayU response
     * Response hash format: sha512(salt|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key)
     */
    public function verifyHash(array $params): bool
    {
        $reverseHashString = sprintf(
            '%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s',
            $this->salt,
            $params['status'],
            $params['additionalCharges'] ?? ($params['udf10'] ?? ''),
            $params['udf9'] ?? '',
            $params['udf8'] ?? '',
            $params['udf7'] ?? '',
            $params['udf6'] ?? '',
            $params['udf5'] ?? '',
            $params['udf4'] ?? '',
            $params['udf3'] ?? '',
            $params['udf2'] ?? '',
            $params['udf1'] ?? '',
            $params['email'],
            $params['firstname'],
            $params['productinfo'],
            $params['amount'],
            $params['txnid']
        );
        
        // Append key at the end
        $reverseHashString .= '|' . $this->key;

        $calculatedHash = hash('sha512', $reverseHashString);
        
        return hash_equals($calculatedHash, $params['hash']);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
