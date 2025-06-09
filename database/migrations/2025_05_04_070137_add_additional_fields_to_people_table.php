<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('biography');
            $table->string('email')->nullable()->after('phone_number');
            $table->string('address')->nullable()->after('email');
            $table->boolean('is_deceased')->default(false)->after('address');
            $table->date('date_of_death')->nullable()->after('is_deceased');
        });
    }

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'email',
                'address',
                'is_deceased',
                'date_of_death',
            ]);
        });
    }
};
