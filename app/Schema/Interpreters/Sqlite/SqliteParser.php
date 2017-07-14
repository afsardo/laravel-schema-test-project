<?php

namespace App\Schema\Interpreters\Sqlite;

abstract class SqliteParser {

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
            
            $columns[] = new SqliteColumn($name, $type, $size, $default, $nullable, $increments);
        }

        return collect($columns);
    }

    protected function parseColumns($query)
    {
        preg_match("/create table \"(.*)\" \((.*)\)/", $query, $fields);
        if (! array_key_exists(2, $fields)) {
            throw new \Exception("Parser: columns couldn't be parsed.");
        }

        return explode(",", $fields[2]);
    }

    protected function parseName($field)
    {
        preg_match("/\"(.*)\"/", $field, $field);
        if (! array_key_exists(1, $field)) {
            $fieldError = var_export($field, true);
            throw new \Exception("Parser: couldn't parse column name from '{$fieldError}'");
        }

        return $field[1];
    }

    protected function parseType($field, $name)
    {
        $attributes = explode(" ", trim(str_replace("\"{$name}\"", "", $field)));
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
        return strpos(strtolower($field), 'primary key autoincrement') !== false;
    }
    
}