<?php

namespace App\Models;

use Nette;

class VisitsFacade
{
    private const
		TableName = 'visits',
		ColumnId = 'id',
        ColumnIpAddress = 'ip_address';

    public function __construct(private Nette\Database\Explorer $database)
    {
    }
    
    public function addVisitor(string $visitor){
        $this->database->table(self::TableName)->insert([
            self::ColumnIpAddress => password_hash($visitor,PASSWORD_DEFAULT)
        ]);
    }
    
    public function getNumberOfVisitors(){
        return $this->database->table(self::TableName)->count();
    }
}