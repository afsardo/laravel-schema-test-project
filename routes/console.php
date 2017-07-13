<?php

use Illuminate\Database\Schema\Blueprint;

Artisan::command('test', function() {

    $blueprint = new Blueprint('users', function($table) {
        // ONE MORE
        $table->unsignedInteger('company_id')->nullable();

        // JUST FOR TESTING
        $table->string('checking')->default('1');
        $table->binary('data');
        $table->boolean('is_admin')->default(false);
        $table->integer('coach_id')->unsigned();
        $table->uuid('uuid');
        $table->timeTz('sunrise');
        $table->string('checking30', 30)->nullable();
    });

    $conn = app('db')->connection();
    $conn->useDefaultSchemaGrammar();
    $queries = $blueprint->toSql($conn, $conn->getSchemaGrammar());

    dd($queries);
});