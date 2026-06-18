<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.hostinger.com',
            'mail_port' => '465',
            'mail_username' => 'hr@rawsio.com',
            'mail_password' => 'Arvind@630635',
            'mail_encryption' => 'ssl',
            'mail_from_address' => 'hr@rawsio.com',
            'mail_from_name' => 'Rawsio.com',
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
