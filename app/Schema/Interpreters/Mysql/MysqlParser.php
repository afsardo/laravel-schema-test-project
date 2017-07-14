<?php

namespace App\Schema\Interpreters\Mysql;

abstract class MysqlParser {

    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    protected function createColumns($fields)
    {
        $columns = [];
        foreach($fields as $field) {
            $name = $this->parseName($field);
            $type = $this->parseType($field, $name);
            $size = $this->parseSize($type);
            if ($size) {
                $type = $this->stripSizeFromType($type, $size);
            }

            $default = $this->parseDefaultValue($field);
            $nullable = $this->parseNullable($field);
            $increments = $this->parseIncrements($field);
            $unsigned = $this->parseUnsigned($field);
            
            $column = new MysqlColumn($name, $type, $size, $default, $nullable, $increments, $unsigned);

            $columns[] = $column;
        }

        return collect($columns);
    }

}