<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @codeCoverageIgnore
 */
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table
                ->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
            $table->foreignId('parent_id')->nullable()->index();
            $table->integer('lft')->nullable()->index();
            $table->integer('rgt')->nullable()->index();
            $table->integer('depth')->nullable();
            $table->string('name');

            /*
             * This field is for scoping categories, use it if you
             * want to store multiple nested sets on the same table
             */
            $table->string('belongs_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
