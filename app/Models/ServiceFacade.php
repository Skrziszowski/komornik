<?php

namespace App\Models;

use Nette;

class ServiceFacade
{
    public function __construct(private Nette\Database\Explorer $database)
    {
    }

    private const
		TableName = 'services',
		ColumnId = 'service_id',
        ColumnServiceName = 'heading_card',
        ColumnPackages = 'cards';

    public function getServices()
    {
        return $this->database->table(self::TableName)->fetchAll();
    }

    public function getServicesById(int $id) {
        return $this->database->table(self::TableName)
            ->where(self::ColumnId, $id)
            ->fetchAll();
    }    

    public function getServiceNameAndPriceById(int $service_id, int $program_id)
    {
        $result = $this->database->table(self::TableName)
        ->where(self::ColumnId, $service_id)
        ->fetch();

        if (!$result) return false;

        $programList = explode('|', $result['cards']);

        if (count($programList) < $program_id || $program_id < 1 || $program_id > 3){
            return false;
        }

        $programParts = explode(',', $programList[$program_id-1]);
        $program = trim($programParts[0]);
        $amount = $programParts[1];

        return [
            'serviceName' => $result['heading_card'],
            'amount' => $amount,
            'program' => $program,
        ];
    }
}