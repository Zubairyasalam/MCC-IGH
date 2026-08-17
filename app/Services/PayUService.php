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
        $statusSetting = \App\Models\Setting::where('key', 'payu_status')->value('value');
        $testModeSetting = \App\Models\Setting::where('key', 'payu_test_mode')->value('value');
        $keySetting = \App\Models\Setting::where('key', 'payu_merchant_key')->value('value');
        $saltSetting = \App\Models\Setting::where('key', 'payu_merchant_salt')->value('value');

        $this->key = !empty($keySetting) ? trim($keySetting) : (config('services.payu.key') ?: env('PAYU_MERCHANT_KEY'));
        $this->salt = !empty($saltSetting) ? trim($saltSetting) : (config('services.payu.salt') ?: env('PAYU_MERCHANT_SALT'));

        if ($testModeSetting !== null) {
            $isTest = in_array(strtolower(trim($testModeSetting)), ['active', '1', 'test', 'true']);
            $this->mode = $isTest ? 'test' : 'production';
        } else {
            $this->mode = config('services.payu.mode') ?: env('PAYU_MODE', 'production');
        }

        $this->url = $this->mode === 'production' 
            ? (config('services.payu.production_url') ?: env('PAYU_PRODUCTION_URL', 'https://secure.payu.in/_payment')) 
            : (config('services.payu.test_url') ?: env('PAYU_TEST_URL', 'https://test.payu.in/_payment'));
            
        if (empty($this->key) || empty($this->salt)) {
            Log::warning('PayU credentials are not set in the configuration.');
        }
    }

    /**
     * Check if PayU payment gateway is active
     */
    public function isActive(): bool
    {
        $statusSetting = \App\Models\Setting::where('key', 'payu_status')->value('value');
        if ($statusSetting !== null) {
            return in_array(strtolower(trim($statusSetting)), ['active', '1', 'true', 'enabled']);
        }
        return true;
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
        if (empty($params['hash'])) {
            return false;
        }

        $udf10 = $params['udf10'] ?? '';
        $udf9 = $params['udf9'] ?? '';
        $udf8 = $params['udf8'] ?? '';
        $udf7 = $params['udf7'] ?? '';
        $udf6 = $params['udf6'] ?? '';
        $udf5 = $params['udf5'] ?? '';
        $udf4 = $params['udf4'] ?? '';
        $udf3 = $params['udf3'] ?? '';
        $udf2 = $params['udf2'] ?? '';
        $udf1 = $params['udf1'] ?? '';

        $email = $params['email'] ?? '';
        $firstname = $params['firstname'] ?? '';
        $productinfo = $params['productinfo'] ?? '';
        $amount = $params['amount'] ?? '';
        $txnid = $params['txnid'] ?? '';
        $status = $params['status'] ?? '';

        $hashSequence = sprintf(
            '%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s',
            $this->salt,
            $status,
            $udf10,
            $udf9,
            $udf8,
            $udf7,
            $udf6,
            $udf5,
            $udf4,
            $udf3,
            $udf2,
            $udf1,
            $email,
            $firstname,
            $productinfo,
            $amount,
            $txnid,
            $this->key
        );

        if (!empty($params['additionalCharges'])) {
            $hashSequence = $params['additionalCharges'] . '|' . $hashSequence;
        }

        $calculatedHash = strtolower(hash('sha512', $hashSequence));
        $receivedHash = strtolower($params['hash']);

        return hash_equals($calculatedHash, $receivedHash);
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
