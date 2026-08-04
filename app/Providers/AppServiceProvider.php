<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamically set APP_URL to match the current request's host
        // This ensures email links work for everyone on the same WiFi, even if the .env IP changes.
        if (!app()->runningInConsole()) {
            config(['app.url' => request()->getSchemeAndHttpHost()]);
            // Force HTTPS scheme ONLY on non-localhost domains (live server)
            if (!in_array(request()->getHost(), ['127.0.0.1', 'localhost']) && (request()->isSecure() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || app()->environment('production'))) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            }
        }

        // Auto-Check and Auto-Migrate missing database columns for bookings table on live server
        $this->ensureDatabaseSchemaInSync();

        // Share system settings with all views safely
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
                    $view->with('settings', $settings);
                    $view->with('gstRate', $settings['gst_rate'] ?? '5');
                } else {
                    $view->with('settings', []);
                    $view->with('gstRate', '5');
                }
            } catch (\Throwable $e) {
                $view->with('settings', []);
                $view->with('gstRate', '5');
            }
        });
    }

    /**
     * Automatically ensure bookings table columns exist on server
     */
    private function ensureDatabaseSchemaInSync(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing('bookings');

                $requiredColumns = [
                    'visa_number' => 'string',
                    'passport_visa_attachment' => 'string',
                    'residence_status' => 'string',
                    'reference_id' => 'string',
                    'booking_reason' => 'string',
                    'referral_attachment' => 'string',
                    'is_admin_read' => 'boolean',
                    'nationality' => 'string',
                    'user_type' => 'string',
                    'stream' => 'string',
                    'level' => 'string',
                    'department' => 'string',
                    'primary_guest_name' => 'string',
                    'no_of_persons' => 'integer',
                    'passport_number' => 'string',
                    'approval_status' => 'string',
                ];

                $missing = array_diff(array_keys($requiredColumns), $columns);

                if (!empty($missing)) {
                    // 1. Attempt standard Artisan migration
                    try {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('bookings');
                        $missing = array_diff(array_keys($requiredColumns), $columns);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Auto migration failed: ' . $e->getMessage());
                    }

                    // 2. Direct schema alteration fallback if any column is still missing
                    if (!empty($missing)) {
                        \Illuminate\Support\Facades\Schema::table('bookings', function (\Illuminate\Database\Schema\Blueprint $table) use ($missing) {
                            if (in_array('reference_id', $missing)) $table->string('reference_id')->nullable();
                            if (in_array('nationality', $missing)) $table->string('nationality')->default('Indian')->nullable();
                            if (in_array('user_type', $missing)) $table->string('user_type')->default('Guest')->nullable();
                            if (in_array('residence_status', $missing)) $table->string('residence_status')->nullable();
                            if (in_array('stream', $missing)) $table->string('stream')->nullable();
                            if (in_array('level', $missing)) $table->string('level')->nullable();
                            if (in_array('department', $missing)) $table->string('department')->nullable();
                            if (in_array('primary_guest_name', $missing)) $table->string('primary_guest_name')->nullable();
                            if (in_array('no_of_persons', $missing)) $table->integer('no_of_persons')->default(1)->nullable();
                            if (in_array('passport_number', $missing)) $table->string('passport_number')->nullable();
                            if (in_array('visa_number', $missing)) $table->string('visa_number')->nullable();
                            if (in_array('passport_visa_attachment', $missing)) $table->string('passport_visa_attachment')->nullable();
                            if (in_array('referral_attachment', $missing)) $table->string('referral_attachment')->nullable();
                            if (in_array('is_admin_read', $missing)) $table->boolean('is_admin_read')->default(false);
                            if (in_array('booking_reason', $missing)) $table->text('booking_reason')->nullable();
                            if (in_array('approval_status', $missing)) $table->string('approval_status')->default('Pending')->nullable();
                        });
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ensureDatabaseSchemaInSync failed: ' . $e->getMessage());
        }
    }
}
