<?php

namespace App\Schema;

use Illuminate\Support\Facades\DB;

class DBDump {

    protected $conn;

    protected $table;

    public function __construct($table, $conn)
    {
        $this->table = $table;
        $this->conn = $conn;
    }

    public static function schema($table, $conn = null)
    {
        if (is_null($conn)) {
            $conn = config('database.default');
        }

        return (new static($table, $conn))->{$conn . 'Dump'}();
    }

    public function sqliteDump()
    {
        return DB::connection($this->conn)->select("SELECT * FROM sqlite_master WHERE tbl_name='{$this->table}'");
    }

    public function mysqlDump()
    {
        return DB::connection($this->conn)->select("DESCRIBE {$this->table}");
    }

    public function pgsqlDump()
    {
        return DB::connection($this->conn)->select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$this->table}'");
    }

}