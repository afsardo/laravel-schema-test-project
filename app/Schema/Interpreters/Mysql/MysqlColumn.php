<?php

namespace App\Schema\Interpreters\Mysql;

use App\Schema\Interpreters\ColumnSchema;

class MysqlColumn implements ColumnSchema {

    protected $name;
    protected $type;
    protected $size;
    protected $default;
    protected $nullable;
    protected $increments;
    protected $unsigned;

    private static $dictionary = [
        "char" => "char",
        "varchar" => "string",
        "text" => "text",
        "mediumtext" => "mediumText",
        "longtext" => "longText",
        "bigint" => "bigInteger",
        "int" => "integer",
        "mediumint" => "mediumInteger",
        "tinyint" => "tinyInteger",
        "smallint" => "smallInteger",
        "double" => "double",
        "decimal" => "decimal",
        "enum" => "enum",
        "json" => "json",
        "date" => "date",
        "datetime" => "datetime",
        "time" => "time",
        "timestamp" => "timestamp",
        "blob" => "binary",
    ];

    public function __construct($name, $type, $size = null, $default = null, $nullable = true, $increments = false, $unsigned = false) {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->default = $default;
        $this->nullable = $nullable;
        $this->increments = $increments;
        $this->unsigned = $unsigned;
    }

    public function name()
    {
        return $this->name;
    }

    public function type()
    {

        return self::dictionary[$this->type];
    }

    public function size()
    {
        return $this->size;
    }

    public function default()
    {
        return $this->default;
    }

    public function nullable()
    {
        return $this->nullable;
    }

    public function unsigned()
    {
        return $this->unsigned;
    }

    public function increments()
    {
        return $this->increments;
    }

    public function equals(ColumnSchema $other)
    {
        return $this->name() === $other->name();
    }

}