<?php

namespace App\Schema;

use Illuminate\Database\Migrations\Migration;

class SchemaMigration extends Migration {

    /**
     * Dump the migration state.
     *
     * @return void
     */
    public function dump()
    {
        return DBDump::schema($this->schema);
    }
    
}