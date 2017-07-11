<?php

namespace App\Schema;

class TableSchema {
    
    protected $name;
    protected $fields;

    protected $sql;

    public function __construct($sql) {
        $this->name = $this->parseName($sql);
        $this->fields = $this->parseFields($sql);
        $this->sql = $sql;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function equals(TableSchema $other)
    {
        if (count($other->getFields()) != count($this->fields)) {
            return false;
        }

        foreach($other->getFields() as $field => $attributes) {
            if ($this->getFields()[$field] != $attributes) {
                return false;
            }
        }
        return true;
    }

    public function diff(TableSchema $other)
    {   
        $diff = [];

        foreach($this->getFields() as $field => $attributes) {
            if (! array_key_exists($field, $other->getFields()) 
                || ( array_key_exists($field, $other->getFields()) && $other->getFields()[$field] != $attributes)) {
                $diff[$field] = $attributes;
            }
        }

        return $diff;
    }

    /**
     * SQL Server / MS Access: ALTER TABLE table_name ALTER COLUMN column_name datatype;
     * My SQL / Oracle (prior version 10G): ALTER TABLE table_name MODIFY COLUMN column_name datatype;
     * Oracle 10G and later: ALTER TABLE table_name MODIFY column_name datatype;
     */
    public function diffSql(TableSchema $other)
    { 
        $diff = [];
        foreach($this->diff($other) as $field => $attributes) {
            if (! array_key_exists($field, $other->getFields())) {
                $diff[] = "alter table \"{$this->name}\" add \"{$field}\" {$attributes}";
            } else {
                $diff[] = "alter table \"{$this->name}\" modify column \"{$field}\" {$attributes}";
            }
        }

        return $diff;
    }

    private function parseName($sql) {
        preg_match("/create table \"(.*)\" \(/", $sql, $name);
        if (array_key_exists(1, $name)) {
            return $name[1];
        }

        throw new \Exception("Table Schema: name couldn't be parsed.");
    }

    private function parseFields($sql) {
        preg_match("/create table \"(.*)\" \((.*)\)/", $sql, $fields);
        if (array_key_exists(2, $fields)) {
            $fieldsStr = explode(",", $fields[2]);
            $fields = [];
            foreach($fieldsStr as $fieldStr) {
                preg_match("/\"(.*)\"/", $fieldStr, $field);
                if (array_key_exists(1, $field)) {
                    $fields[$field[1]] = trim(str_replace("\"{$field[1]}\"", "", $fieldStr));
                } else {
                    throw new \Exception("Table Schema: couldn't parse the field string '{$fieldStr}'");
                }
            }

            return $fields;
        }

        throw new \Exception("Table Schema: fields couldn't be parsed.");
    }

}