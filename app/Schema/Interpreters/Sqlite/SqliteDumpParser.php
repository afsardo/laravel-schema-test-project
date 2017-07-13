<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\Sqlite\SqliteTable;

class SqliteDumpParser implements Parser {

    protected $connection;
    protected $dump;

    public function __construct($connection, $dump)
    {
        $this->connection = $connection;
        $this->dump = $dump;
    }

    public function parse()
    {
        $table = new SqliteTable($this->connection);

        foreach ($this->dump as $dump) {
            if ($this->isCreate($dump)) {
                $table->setName($this->parseName($dump));
                $table->setColumns($this->parseColumns($dump));
            } else if ($this->isIndex($dump)) {
                // IMPLEMENT INDEXES
            } else {
                throw new \Exception("Query Parser: Couldn't parse dump '{$dump}'");
            }
        }
        
        return $table;
    }

    private function isCreate($dump)
    {
        return $dump->type == 'table';
    }

    private function parseName($dump)
    {
        return $dump->tbl_name;
    }

    private function parseColumns($dump)
    {
        $query = strtolower($dump->sql);

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

            preg_match("/default '(.*)'/", $fieldStr, $default);
            if (array_key_exists(1, $default)) {
                $default = $default[1];
            } else {
                $default = null;
            }
            
            $fields[] = new SqliteColumn($name, $type, $size, $default, $nullable, $increments);
        }

        return collect($fields);
    }

    private function isIndex($dump)
    {
        return $dump->type == 'index';
    }
    
}