<?php

use App\Models\Edition;
use App\Models\StudentAssociation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function DatabaseHelpers\createManyToManyRelation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->string('name');
            $table->string('code');
            // $table->integer('points')->unsigned()->default(0);
        });

        createManyToManyRelation(StudentAssociation::class, Edition::class, function (Blueprint $table) {
            $table->integer('points')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edition_student_association');
        Schema::dropIfExists('student_associations');
    }
};
