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

    public static $dictionary = [
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

        $this->processTypeSize();
    }

    private function processTypeSize()
    {
        $size = explode(", ", str_replace("'", "", $this->size));

        if (count($size) > 1) {
            $this->size = $size;
        } else {
            $this->size = empty($size[0]) ? null : $size[0];
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function type()
    {

        return self::$dictionary[$this->type];
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

    public function isDouble()
    {
        return $this->type == "double";
    }

    public function isDecimal()
    {
        return $this->type == "decimal";
    }

    public function isEnum()
    {
        return $this->type == "enum";
    }

    public function isBoolean()
    {
        return $this->type == "tinyint" && $this->default == 1;
    }

}