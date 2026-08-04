<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    /**
     * Get setting value as an array of trimmed email addresses.
     * Supports single email or multiple comma-separated emails.
     *
     * @param string $key
     * @param string|array $default
     * @return array
     */
    public static function getEmails(string $key, $default = ''): array
    {
        $val = static::where('key', $key)->value('value');
        if (is_null($val) || trim((string)$val) === '') {
            if (is_array($default)) {
                return $default;
            }
            $val = $default;
        }

        $emails = array_filter(array_map('trim', explode(',', (string)$val)));
        return array_values($emails);
    }
}
