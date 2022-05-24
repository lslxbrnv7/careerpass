<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Job;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('owner')->nullable();
            $table->timestamps();
        });

        Schema::create('job', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('wysiwyg');
            $table->date('starts_at');
            $table->date('expires_at');
            $table->boolean('is_active');
            $table->string('status')->default(Job::STATUS_PENDING);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
        Schema::dropIfExists('company');
    }
};
