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
            $table->mediumIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->nullableTimestamps();

            /* 
            $table->bigInteger('voters1')->nullable();    // BIGINT equivalent for the database.
            $table->binary('data')->nullable(); // BLOB equivalent for the database.
            $table->boolean('confirmed')->default(true);   // BOOLEAN equivalent for the database.
            $table->char('smal__name', 4)->nullable();    // CHAR equivalent with a length.
            $table->date('created_at1')->nullable(); // DATE equivalent for the database.
            $table->dateTime('created_at2')->nullable(); // DATETIME equivalent for the database.
            $table->dateTimeTz('created_at3')->nullable();   // DATETIME (with timezone) equivalent for the database.
            $table->decimal('amount', 5, 2)->nullable();    // DECIMAL equivalent with a precision and scale.
            $table->double('column', 15, 8)->nullable();    // DOUBLE equivalent with precision, 15 digits in total and 8 after the decimal point.
            $table->enum('choices', ['foo', 'bar'])->nullable();    // ENUM equivalent for the database.
            $table->float('amount2', 8, 2)->nullable();  // FLOAT equivalent for the database, 8 digits in total and 2 after the decimal point.
            $table->integer('voters2')->unsigned()->nullable();   //INTEGER equivalent for the database.
            $table->ipAddress('visitor')->virtualAs("ip_address")->nullable();   //IP address equivalent for the database.
            $table->json('options')->nullable();    //JSON equivalent for the database.
            $table->jsonb('options2')->nullable();   //JSONB equivalent for the database.
            $table->longText('description')->nullable();    //LONGTEXT equivalent for the database.
            $table->macAddress('device')->storedAs("mac_address")->nullable();   //MAC address equivalent for the database.
            $table->mediumInteger('numbers')->nullable();   //MEDIUMINT equivalent for the database.
            $table->mediumText('description2')->nullable();  //MEDIUMTEXT equivalent for the database.
            $table->nullableMorphs('taggable2'); //Nullable versions of the morphs() columns.
            $table->smallInteger('voters3')->nullable();  //SMALLINT equivalent for the database.
            $table->softDeletes();  //Adds nullable deleted_at column for soft deletes.
            $table->string('name2', 100)->after('name')->nullable();    // VARCHAR equivalent with a length.
            $table->text('description3')->default("LOOOL LONG TEXT HERE?");    // TEXT equivalent for the database.
            $table->time('sunrise')->nullable();    // TIME equivalent for the database.
            $table->timeTz('sunrise2')->comment('just testing comments')->nullable();  // TIME (with timezone) equivalent for the database.
            $table->tinyInteger('numbers2')->nullable(); // TINYINT equivalent for the database.
            $table->timestamp('added_on')->nullable();  // TIMESTAMP equivalent for the database.
            $table->timestampTz('added_on2')->nullable();    // TIMESTAMP (with timezone) equivalent for the database.
            $table->unsignedBigInteger('votes1')->nullable();    // Unsigned BIGINT equivalent for the database.
            $table->unsignedInteger('votes2')->nullable();   // Unsigned INT equivalent for the database.
            $table->unsignedMediumInteger('votes3')->nullable(); // Unsigned MEDIUMINT equivalent for the database.
            $table->unsignedSmallInteger('votes4')->nullable();  // Unsigned SMALLINT equivalent for the database.
            $table->unsignedTinyInteger('votes5')->nullable();   // Unsigned TINYINT equivalent for the database.
            $table->uuid('uuid')->first()->nullable(); // UUID equivalent for the database.
             */
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
