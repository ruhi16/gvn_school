<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('mysessions') && !Schema::hasTable('sessions')) {
            Schema::rename('mysessions', 'sessions');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sessions') && !Schema::hasTable('mysessions')) {
            Schema::rename('sessions', 'mysessions');
        }
    }
};