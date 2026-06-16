<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('chat_public_key')->nullable()->after('is_private');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->text('encrypted_key_sender')->nullable()->after('body');
            $table->text('encrypted_key_recipient')->nullable()->after('encrypted_key_sender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('chat_public_key');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['encrypted_key_sender', 'encrypted_key_recipient']);
        });
    }
};
