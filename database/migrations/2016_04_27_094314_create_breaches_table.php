<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breaches', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('organisation_id')->index();
            $table->enum('method', [
                'Hack',
                'Employee',
                'Company',
                'Social',
                'Physical',
                'Other',
                'Unknown'
            ])->index();
            $table->text('summary');
            $table->text('data_leaked');
            $table->date('date_occurred');
            $table->integer('people_affected')->nullable();
            $table->boolean('previously_known');
            $table->enum('response_stance', [
                'Ignored',
                'Denied',
                'Partial',
                'Full'
            ])->nullable();
            $table->enum('response_patched', [
                'NotRequired',
                'Patched1',
                'Patched2',
                'Patches4',
                'Patched7',
                'Patches30',
                'NotPatched'
            ])->nullable();
            $table->enum('response_customers_informed', [
                'Informed1',
                'Informed2',
                'Informed4',
                'Informed7',
                'Informed30',
                'NotInformed'
            ])->nullable();
            $table->text('source_url');
            $table->string('source_name', 100);
            $table->text('more_url')->nullable()->default(null);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('breaches');
    }
}
