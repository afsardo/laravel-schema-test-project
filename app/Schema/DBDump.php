<?php

namespace App\Schema;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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

        $dump = (new static($table, $conn))->{$conn . 'Dump'}();

        if (is_array($dump) && count($dump) <= 0) {
            return null;
        }

        return $dump;
    }

    public function sqliteDump()
    {
        return DB::connection($this->conn)->select("SELECT * FROM sqlite_master WHERE tbl_name='{$this->table}'");
    }

    public function mysqlDump()
    {
        try {
            $columns = DB::connection($this->conn)->select("DESCRIBE {$this->table}");
            
            if (count($columns) <= 0) {
                return null;
            }

            return [
                "table" => $this->table,
                "columns" => $columns,
            ];

        } catch(QueryException $e) {
            return null;
        }
    }

    public function pgsqlDump()
    {
        return DB::connection($this->conn)->select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$this->table}'");
    }

}