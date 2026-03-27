<?php

use App\Enum\AnonymousEnum;
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
        Schema::table('reacts', function (Blueprint $table) {
            if (! Schema::hasColumn('reacts', 'is_anonymous')) {
                $table->integer('is_anonymous')->default(AnonymousEnum::NOT_ANONYMOUS->value)->after('react');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reacts', function (Blueprint $table) {
            if (Schema::hasColumn('reacts', 'is_anonymous')) {
                $table->dropColumn('is_anonymous');
            }
        });
    }
};
