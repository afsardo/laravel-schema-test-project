<?php

namespace App\Schema\Interpreters\Mysql;

use App\Schema\Interpreters\Parser;
use App\Schema\Interpreters\TableSchema;
use App\Schema\Exceptions\TableQueryNotParsableException;

class MysqlDumpParser extends MysqlParser implements Parser {

    protected $dump;

    public function __construct($connection, $dump)
    {
        parent::__construct($connection);
        $this->dump = $dump;
    }

    public function parse()
    {
        $table = new TableSchema($this->connection);

        $table->setName($this->dump['table']);
        $table->setColumns($this->createColumns($this->dump['columns']));
        
        return $table;
    }

    protected function parseName($field)
    {
        return $field->Field;
    }

    protected function parseType($field, $name)
    {
        $attributes = explode(" ", $field->Type);
        if (! array_key_exists(0, $attributes)) {
            throw new \Exception("Parser: couldn't parse column type from '{$field}'");
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
        return $field->Default;
    }

    protected function parseNullable($field)
    {
        return $field->Null == "YES";
    }

    protected function parseIncrements($field)
    {
        return strpos($field->Extra, 'auto_increment') !== false;
    }
    
    protected function parseUnsigned($field)
    {
        return strpos($field->Type, 'unsigned') !== false;
    }

}