<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\Sqlite\SqliteTable;
use App\Schema\Exceptions\TableQueryNotParsableException;

class SqliteQueryParser implements Parser {

    protected $connection;
    protected $queries;

    public function __construct($connection, $queries)
    {
        $this->connection = $connection;
        $this->queries = $queries;
    }

    public function parse()
    {
        $table = new SqliteTable($this->connection);

        foreach ($this->queries as $query) {
            $query = $query['query'];

            if ($this->isCreate($query)) {
                $table->setName($this->parseName($query));
                $table->setColumns($this->parseColumns($query));
            } else if ($this->isIndex($query)) {
                // IMPLEMENT INDEXES
            } else if ($this->isDrop($query)) {
                $table->setToDrop();
            } else {
                throw new TableQueryNotParsableException("Table Query Parser: Couldn't parse query '{$query}'");
            }
        }
        
        return $table;
    }

    private function isCreate($query)
    {
        return strpos(strtolower($query), 'create table') !== false;
    }

    private function isDrop($query)
    {
        return strpos(strtolower($query), 'drop table') !== false;
    }

    private function parseName($query)
    {
        preg_match("/create table \"(.*)\" \(/", $query, $name);
        if (! array_key_exists(1, $name)) {
            throw new \Exception("Dump Parser: name couldn't be parsed.");
        }

        return $name[1];
    }

    private function parseColumns($query)
    {
        preg_match("/create table \"(.*)\" \((.*)\)/", $query, $fields);
        if (! array_key_exists(2, $fields)) {
            throw new \Exception("Dump Parser: fields couldn't be parsed.");
        }

        $fieldsStr = explode(",", $fields[2]);
        $fields = [];
        foreach($fieldsStr as $fieldStr) {
            preg_match("/\"(.*)\"/", $fieldStr, $field);
            if (! array_key_exists(1, $field)) {
                throw new \Exception("Dump Parser: couldn't parse the field string '{$fieldStr}'");
            }
            $name = $field[1];

            $attributes = explode(" ", trim(str_replace("\"{$name}\"", "", $fieldStr)));
            $type = $attributes[0];

            preg_match("/\((.*)\)/", $type, $size);
            if (array_key_exists(1, $size)) {
                $size = $size[1];
                $type = str_replace("({$size})", "", $type);
            } else {
                $size = null;
            }

            preg_match("/default '(.*)'/", $fieldStr, $default);
            if (array_key_exists(1, $default)) {
                $default = $default[1];
            } else {
                $default = null;
            }
            
            if (strpos(strtolower($fieldStr), 'not null') !== false) {
                $nullable = false;
            } else {
                $nullable = true;
            }

            if (strpos(strtolower($fieldStr), 'primary key autoincrement') !== false) {
                $increments = true;
            } else {
                $increments = false;
            }
            
            $fields[] = new SqliteColumn($name, $type, $size, $default, $nullable, $increments);
        }

        return collect($fields);
    }

    private function isIndex($query)
    {
        return strpos(strtolower($query), 'create unique index') !== false
            || strpos(strtolower($query), 'create index') !== false;
    }
    
}