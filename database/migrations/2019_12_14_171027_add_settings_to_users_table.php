<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('start_at')->default(600);
            $table->unsignedTinyInteger('end_at')->default(1200);

            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set NULL');

            $table->boolean('is_active')->default(false)->index();
            $table->boolean('is_ready')->default(false)->index();

            $table->index(['is_ready', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'start_at',
                'end_at',
                'level_id',
                'is_active',
                'is_ready'
            ]);
        });
    }
}
