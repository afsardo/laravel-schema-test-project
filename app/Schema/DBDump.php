<?php

namespace App\Schema;

use Illuminate\Support\Facades\DB;

class DBDump {
    
    /**
     * sqlite3: .schema table_name
     * Postgres: \d table_name
     * SQL Server: sp_help table_name (or sp_columns table_name for only columns)
     * Oracle DB2: desc table_name or describe table_name
     * MySQL: describe table_name (or show columns from table_name for only columns)
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public static function schema($table)
    {
        return DB::select("SELECT * FROM sqlite_master WHERE tbl_name='{$table}'");
        //return DB::statement("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='{$table}'");
    }

}