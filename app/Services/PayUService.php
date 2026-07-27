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
        $this->key = config('services.payu.key') ?: env('PAYU_MERCHANT_KEY');
        $this->salt = config('services.payu.salt') ?: env('PAYU_MERCHANT_SALT');
        $this->mode = config('services.payu.mode') ?: env('PAYU_MODE', 'production');
        $this->url = $this->mode === 'production' 
            ? (config('services.payu.production_url') ?: env('PAYU_PRODUCTION_URL', 'https://secure.payu.in/_payment')) 
            : (config('services.payu.test_url') ?: env('PAYU_TEST_URL', 'https://test.payu.in/_payment'));
            
        if (empty($this->key) || empty($this->salt)) {
            Log::warning('PayU credentials are not set in the configuration.');
        }
    }

    /**
     * Generate hash for PayU request
     * Hash format: sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||salt)
     */
    public function generateHash(array $params): string
    {
        if (empty($this->key) || empty($this->salt)) {
            throw new \RuntimeException('PayU merchant key or salt is missing. Please set PAYU_MERCHANT_KEY and PAYU_MERCHANT_SALT in .env.');
        }

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
