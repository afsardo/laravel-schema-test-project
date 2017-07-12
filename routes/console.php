<?php

use Illuminate\Database\Schema\Blueprint;

Artisan::command('test', function() {

    $blueprint = new Blueprint('users', function($table) {
        $table->string('checking')->default('1');
        $table->binary('data');
        $table->boolean('is_admin')->default(false);
        $table->integer('coach_id')->unsigned()->after('id');
        $table->uuid('uuid')->first();
        $table->timeTz('sunrise')->comment('my comment')->virtualAs("rise_up");
        $table->string('checking30', 30)->nullable();
        $table->timestampsTz();
    });

    $conn = app('db')->connection();
    $conn->useDefaultSchemaGrammar();
    $queries = $blueprint->toSql($conn, $conn->getSchemaGrammar());

    dd($queries);
});