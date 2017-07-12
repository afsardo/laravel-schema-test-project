<?php

namespace App\Schema;

use App\Schema\Interpreters\MysqlInterpreter;
use App\Schema\Interpreters\PostgresInterpreter;

class InterpreterFactory {
    
    protected $connection;

    protected $interpreters = [
        'mysql' => MysqlInterpreter::class,
        'pgsql' => PostgresInterpreter::class,
    ];

    public function connection($connection) {
        $this->connection = $connection;

        return $this;
    }

    public function fromQuery($query) {
        return $this->resolve()::fromQuery($query);
    }


    public function fromDump($dump) {
        return $this->resolve()::fromDump($dump);
    }

    private function resolve()
    {
        return $this->interpreters[$this->connection->getDriverName()];
    }
    
}

/*
<?php

namespace App\Schema\Interpreters;

class Interpreter
{

    private function difference($query, $dump)
    {
        if ($this->isTableQuery($query)) {
            $current = $this->getTableQuery($dump);
            if ($current) {
                $before = new TableSchema($current);
                $after = new TableSchema($query);
                if (! $before->equals($after)) {
                    return $after->diffSql($before);
                }

                return null;
            }
        }

        if ($this->isIndexQuery($query)) {
            $current = $this->getIndexQuery($dump, $this->getIndexName($query));
            if ($current) {
                // return null;
                throw new \Exception("Not implemented yet.");
            }
        }

        return [$query];
    }

    private function isIndexQuery($query)
    {
        return strpos(strtolower($query), 'create index') !== false 
            || strpos(strtolower($query), 'create unique index') !== false;
    }

    private function getIndexName($query)
    {
        $query = str_replace('create unique index ', '', $query);
        $query = str_replace('create index ', '', $query);
        return str_replace('"', '', explode(" ", $query)[0]);
    }

    private function getIndexQuery($dump, $index)
    {
        foreach($dump as $query) {
            if ($query->type == 'index' && $query->name == $index) {
                return strtolower($query->sql);
            }
        }

        return null;
    }

    private function isTableQuery($query)
    {
        return strpos(strtolower($query), 'create table') !== false;
    }

    private function getTableQuery($dump)
    {
        foreach($dump as $query) {
            if ($query->type == 'table') {
                return strtolower($query->sql);
            }
        }

        return null;
    }
}
 */