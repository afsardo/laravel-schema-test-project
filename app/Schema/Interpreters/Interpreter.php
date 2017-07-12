<?php

namespace App\Schema\Interpreters;

interface Interpreter {
    
    public function differenceToSql(Interpreter $other);
    
}