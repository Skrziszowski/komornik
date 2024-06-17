<?php

namespace App\Models;

use Nette;

class CareerFacade
{
    private const
		TableName = 'career',
		ColumnId = 'career_id',
        ColumnDateArrive = 'date_arrive',
        ColumnName = 'name',
        ColumnSurname = 'surname',
        ColumnAge = 'age',
        ColumnGender = 'gender',
        ColumnbirthDate = 'birthDate',
        ColumnPhone = 'phone',
        ColumnEmail = 'email',
        ColumnEducation = 'education',
        ColumnPosition ='position',
        ColumnHobbies = 'hobbies',
        ColumnWhyUs = 'why',
        ColumnSkills = 'skills',
        ColumnStatus = "career_status";

    public function __construct(private Nette\Database\Explorer $database)
    {
    }
    
    public function updateApplication(int $id)
    {
        $this->database->table(self::TableName)->where(self::ColumnId, $id)->update([self::ColumnStatus => 1]);
    }

    public function getAllCareer()
    {
        return $this->database->table(self::TableName)->where(self::ColumnStatus, 0)->fetchAll();
    }
    
    public function getNumberOfCareer()
    {
        return $this->countRecordsWhereColumnEquals(self::ColumnStatus, 0);
    }

    public function getNumberOfAllCareer()
    {
        return $this->countRecords();
    }

    public function countGender(string $gender)
    {
        return $this->countRecordsWhereColumnEquals(self::ColumnGender,$gender);
    }

    public function averageAge()
    {
        $ages = $this->database->table(self::TableName)->select(self::ColumnAge)->fetchAll();
        $totalAge = array_sum(array_column($ages, self::ColumnAge));
        $count = count($ages);
        return $count > 0 ? $totalAge / $count : null;
    }

    public function addCareer(array $careerData)
    {    
        $this->database->table(self::TableName)->insert([$careerData]);
    }

    private function countRecordsWhereColumnEquals(string $columnName, $value): int
    {
        return $this->database->table(self::TableName)->where($columnName, $value)->count();
    }

    private function countRecords(): int
    {
        return $this->database->table(self::TableName)->count();
    }
}