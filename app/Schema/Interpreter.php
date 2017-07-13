<?php

namespace App\Schema;

class Interpreter {
    
    protected $connection;

    protected $queriesParsers = [
        'sqlite' => \App\Schema\Interpreters\Sqlite\SqliteQueryParser::class,
    ];

    protected $dumpParsers = [
        'sqlite' => \App\Schema\Interpreters\Sqlite\SqliteDumpParser::class,
    ];

    public function connection($connection) {
        $this->connection = $connection;

        return $this;
    }

    public function fromQueries($queries) {
        return $this->resolveQueriesParser($queries)->parse();
    }

    public function fromDump($dump) {
        return $this->resolveDumpParser($dump)->parse();
    }

    private function resolveQueriesParser($query)
    {
        return new $this->queriesParsers[$this->connectionDriver()]($this->connection, $query);
    }

    private function resolveDumpParser($dump)
    {
        return new $this->dumpParsers[$this->connectionDriver()]($this->connection, $dump);
    }

    private function connectionDriver()
    {
        return $this->connection->getDriverName();
    }
    
}