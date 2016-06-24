<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->index();
            $table->string('name');
            $table->string('styled_name');
            $table->string('registration_number', 16);
            $table->date('incorporated_on');
            $table->float('score')->default(10.0);
            $table->smallInteger('breach_count')->default(0);
            $table->enum('status', [
                'Submitted',
                'RejectedInfo',
                'RejectedDuplicate',
                'RejectedSource',
                'Accepted',
                'Superseded'
            ])->default('Submitted')->index();
            $table->integer('user_id')->index();
            $table->integer('previous_id')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('organisation_tags', function(Blueprint $table) {
            $table->integer('organisation_id');
            $table->string('tag_id');
            $table->primary(['organisation_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organisations');
        Schema::drop('organisation_tags');
    }
}
