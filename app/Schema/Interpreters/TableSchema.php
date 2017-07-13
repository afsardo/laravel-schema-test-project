<?php

namespace App\Schema\Interpreters;

interface TableSchema {

    public function setName($name);

    public function setColumns($columns);

    public function equals(TableSchema $other);

    public function differenceSql(TableSchema $other);

}