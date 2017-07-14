<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\ColumnSchema;

class SqliteColumn implements ColumnSchema {

    protected $name;
    protected $type;
    protected $size;
    protected $default;
    protected $nullable;
    protected $increments;

    public static $dictionary = [
        "varchar" => "string",
        "text" => "text",
        "integer" => "integer",
        "float" => "float",
        "numeric" => "decimal",
        "date" => "date",
        "datetime" => "dateTime",
        "time" => "time",
        "blob" => "binary",
        "tinyint" => "boolean",
    ];

    public function __construct($name, $type, $size = null, $default = null, $nullable = true, $increments = false) {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->default = $default;
        $this->nullable = $nullable;
        $this->increments = $increments;
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
        return false;
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
        return false;
    }

    public function isDecimal()
    {
        return false;
    }

    public function isEnum()
    {
        return false;
    }

    public function isBoolean()
    {
        return false;
    }

}