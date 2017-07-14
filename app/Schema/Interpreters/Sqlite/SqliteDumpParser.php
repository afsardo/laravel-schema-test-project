<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\TableSchema;
use App\Schema\Exceptions\TableQueryNotParsableException;

class SqliteDumpParser extends SqliteParser implements Parser {

    protected $dump;

    public function __construct($connection, $dump)
    {
        parent::__construct($connection);
        $this->dump = $dump;
    }

    public function parse()
    {
        $table = new TableSchema($this->connection);

        foreach ($this->dump as $dump) {
            if ($this->isCreate($dump)) {
                $table->setName($this->parseTableName($dump));
                $table->setColumns($this->createColumns($this->parseColumns(strtolower($dump->sql))));
            } else if ($this->isIndex($dump)) { // IMPLEMENT INDEXES
            } else {
                throw new TableQueryNotParsableException("Table Dump Parser: Couldn't parse dump '{$dump}'");
            }
        }
        
        return $table;
    }

    protected function isCreate($dump)
    {
        return $dump->type == 'table';
    }

    protected function isIndex($dump)
    {
        return $dump->type == 'index';
    }

    protected function parseTableName($dump)
    {
        return $dump->tbl_name;
    }

}