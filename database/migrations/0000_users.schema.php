<?php

use App\Schema\SchemaMigration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UsersSchema extends SchemaMigration
{
    /**
     * The table for this schema.
     * 
     * @var string
     */
    protected $schema = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('handle')->unique();
            $table->rememberToken();
            $table->timestamps();
            
            // ONE MORE
            $table->unsignedInteger('company_id')->nullable();
            $table->integer('team_id')->nullable();
            $table->mediumIncrements('brand_id')->nullable();

            // JUST FOR TESTING
            $table->string('checking')->default('1');
            $table->binary('data')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->integer('coach_id')->unsigned()->nullable();
            $table->uuid('uuid')->nullable();
            $table->timeTz('sunrise')->nullable();
            $table->string('checking30', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
