<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\TableSchema;
use App\Schema\Exceptions\TableQueryNotParsableException;

class SqliteQueryParser extends SqliteParser implements Parser {

    protected $queries;

    public function __construct($connection, $queries)
    {
        parent::__construct($connection);
        $this->queries = $queries;
    }

    public function parse()
    {
        $table = new TableSchema($this->connection);

        foreach ($this->queries as $query) {
            $query = $query['query'];

            if ($this->isCreate($query)) {
                $table->setName($this->parseTableName($query));
                $table->setColumns($this->createColumns($this->parseColumns($query)));
            } else if ($this->isIndex($query)) { // IMPLEMENT INDEXES
            } else if ($this->isDrop($query)) {
                $table->setToDrop($query);
            } else {
                throw new TableQueryNotParsableException("Table Query Parser: Couldn't parse query '{$query}'");
            }
        }
        
        return $table;
    }

    protected function isCreate($query)
    {
        return strpos(strtolower($query), 'create table') !== false;
    }

    protected function isDrop($query)
    {
        return strpos(strtolower($query), 'drop table') !== false;
    }

    protected function isIndex($query)
    {
        return strpos(strtolower($query), 'create unique index') !== false
            || strpos(strtolower($query), 'create index') !== false;
    }

    protected function parseTableName($query)
    {
        preg_match("/create table \"(.*)\" \(/", $query, $name);
        if (! array_key_exists(1, $name)) {
            throw new \Exception("Query Parser: table name couldn't be parsed.");
        }

        return $name[1];
    }
    
}