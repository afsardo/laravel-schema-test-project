<?php

namespace App\Schema\Interpreters\Sqlite;

use App\Schema\Interpreters\TableSchema;
use Illuminate\Database\Schema\Blueprint;

class SqliteTable implements TableSchema {

    protected $connection;

    protected $name;
    protected $columns;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function countColumns()
    {
        return count($this->columns);
    }

    public function equals(TableSchema $other)
    {
        if ($other->countColumns() != $this->countColumns()) {
            return false;
        }

        return count($this->difference($other)) == 0;
    }

    public function difference(TableSchema $other)
    {   
        $difference = [];

        foreach($this->getColumns() as $column) {
            $contains = $other->getColumns()->contains(function ($c) use ($column) {
                return $c->equals($column);
            });
            if (! $contains) {
                $difference[] = $column;
            }
        }

        return $difference;
    }

    public function differenceSql(TableSchema $other)
    {
        $difference = $this->difference($other);

        $blueprint = new Blueprint($this->name, function($table) use ($difference) {
            foreach($difference as $column) {
                if ($column->size()) {
                    $c = $table->{$column->type()}($column->name(), $column->size());
                } else {
                    $c = $table->{$column->type()}($column->name());
                }

                if (! is_null($column->default())) {
                    $c->default($column->default());
                }

                if ($column->nullable()) {
                    $c->nullable();
                }

                if ($column->unsigned()) {
                    $c->unsigned();
                }

                if ($column->increments()) {
                    $c->increment();
                }
            }
        });
        
        //dd($blueprint->toSql($this->connection, $this->connection->getSchemaGrammar()));
        return $blueprint->toSql($this->connection, $this->connection->getSchemaGrammar());
    }

}