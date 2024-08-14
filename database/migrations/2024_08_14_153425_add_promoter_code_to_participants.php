<?php

use App\Models\StudentAssociation;
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
        Schema::table('participants', function (Blueprint $table) {
            $table->foreignIdFor(StudentAssociation::class, column: 'promoter')->nullable()->constrained(table: with(new StudentAssociation)->getTable())->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(StudentAssociation::class, column: 'promoter');
        });
    }
};
