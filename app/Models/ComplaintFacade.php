<?php

namespace App\Models;

use Nette;

class ComplaintFacade
{
    private const
		TableName = 'complaint',
		ColumnId = 'complaint_id',
        ColumnDateArrive = 'date_arrive',
        ColumnEmail = 'email',
        ColumnTheme = 'theme',
        ColumnMainText = 'mainText';

    public function __construct(private Nette\Database\Explorer $database)
    {
    }

    public function getAllComplaints()
    {
        return $this->database->table(self::TableName)->fetchAll();
    }

    public function removeComplaint(int $id)
    {
        $this->database->table(self::TableName)->where(self::ColumnId, $id)->delete();
    }
    
    public function countComplaints()
    {
        return $this->database->table(self::TableName)->count();
    }

    public function addComplaint(array $complaintData)
    {    
        $this->database->table(self::TableName)->insert([$complaintData]);
    }
}