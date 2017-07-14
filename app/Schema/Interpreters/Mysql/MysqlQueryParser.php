<?php

namespace App\Schema\Interpreters\Mysql;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\TableSchema;
use App\Schema\Exceptions\TableQueryNotParsableException;

class MysqlQueryParser extends MysqlParser implements Parser {

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
        
        dd($table);
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
        return strpos(strtolower($query), 'add unique') !== false
            || strpos(strtolower($query), 'add index') !== false;
    }

    protected function parseTableName($query)
    {
        preg_match("/create table `(.*)` \(/", $query, $name);
        if (! array_key_exists(1, $name)) {
            throw new \Exception("Query Parser: table name couldn't be parsed.");
        }

        return $name[1];
    }

     protected function parseColumns($query)
    {
        preg_match("/create table `(.*)` \((.*)\)/", $query, $fields);
        if (! array_key_exists(2, $fields)) {
            throw new \Exception("Parser: columns couldn't be parsed.");
        }

        return explode(", `", $fields[2]);
    }

    protected function parseName($field)
    {
        preg_match("/`(.*)`/", "`" . $field, $field);
        if (! array_key_exists(1, $field)) {
            $fieldError = var_export($field, true);
            throw new \Exception("Parser: couldn't parse column name from '{$fieldError}'");
        }

        return $field[1];
    }

    protected function parseType($field, $name)
    {
        $attributes = explode(" ", trim(str_replace("`{$name}`", "", "`" . $field)));
        if (! array_key_exists(0, $attributes)) {
            $fieldError = var_export($field, true);
            throw new \Exception("Parser: couldn't parse column type from '{$fieldError}'");
        }

        return $attributes[0];
    }

    protected function parseSize($type)
    {
        preg_match("/\((.*)\)/", $type, $size);
        if (! array_key_exists(1, $size)) {
            return null;
        }

        return $size[1];            
    }

    protected function stripSizeFromType($type, $size)
    {
        return str_replace("({$size})", "", $type);
    }

    protected function parseDefaultValue($field)
    {
        preg_match("/default '(.*)'/", $field, $default);
        if (! array_key_exists(1, $default)) {
            return null;
        }

        return $default[1];
    }

    protected function parseNullable($field)
    {
        return strpos(strtolower($field), 'not null') === false;
    }

    protected function parseIncrements($field)
    {
        return strpos(strtolower($field), 'auto_increment primary key') !== false;
    }
    
    protected function parseUnsigned($field)
    {
        return strpos(strtolower($field), 'unsigned') !== false;
    }
    
}