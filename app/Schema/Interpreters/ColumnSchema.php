<?php

namespace App\Schema\Interpreters;

interface ColumnSchema {

    public function name();

    public function type();

    public function size();
    
    public function default();

    public function nullable();

    public function unsigned();

    public function increments();

    public function equals(ColumnSchema $other);

    public function isDouble();

    public function isDecimal();

    public function isEnum();

    public function isBoolean();

}