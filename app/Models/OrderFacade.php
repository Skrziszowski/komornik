<?php

namespace App\Models;

use Nette;

class OrderFacade
{
    private const
		TableName = 'orders',
		ColumnOrderId = 'order_id',
        ColumnUserId = 'user_id',
        ColumnServiceId = 'service_id',
        ColumnPrice = 'price',
        ColumnTypeService = 'type_service',
        ColumnAddress = 'address',
        ColumnCity = 'city',
        ColumnOrderDate = 'orderDate',
        ColumnOrderTime = 'orderTime',
        ColumnArriveDate = 'arriveDate',
        ColumnNote = 'note',
        ColumnOrderStatus = 'order_status';

    public function __construct(private Nette\Database\Explorer $database)
    {
    }

    public function updateStatus(string $order_id, int $value)
    {
        $this->database->table(self::TableName)->where(self::ColumnOrderId, $order_id)->update([
            self::ColumnOrderStatus => $value
        ]);
    }

    public function getUserOrderCount(int $user_id)
    {
        return $this->database->table(self::TableName)->where(self::ColumnUserId, $user_id)->count();
    }

    public function countServiceById(int $service_id)
    {
        return $this->database->table(self::TableName)->where(self::ColumnOrderStatus, 1)->where(self::ColumnServiceId, $service_id)->count();
    }

    public function averagePayment()
    {
        $prices = $this->getAcceptedPayments();
        $totalPrice = array_sum(array_column($prices, self::ColumnPrice));
        $count = count($prices);
        return $count > 0 ? round($totalPrice / $count, 1) : 0;
    }
    
    public function sales()
    {
        $sales = $this->getAcceptedPayments();
        $totalSales = array_sum(array_column($sales, self::ColumnPrice));
        return $totalSales > 0 ? $totalSales : 0;
    }
    
    public function getOrderById($id)
    {
        return $this->database->table(self::TableName)->where(self::ColumnUserId, $id)->order(self::ColumnOrderDate . ' DESC')->fetchAll();
    }

    public function getOrders()
    {
        return $this->database->table(Self::TableName)->where(Self::ColumnOrderStatus, 0)->fetchAll();
    }

    public function getAmountById($id)
    {
        $result = $this->database->table(self::TableName)->select(self::ColumnPrice)->where(self::ColumnOrderId, $id)->fetch();
        return (float)$result['price'];
    }

    public function addOrder(array $orderData)
    {    
        $this->database->table(self::TableName)->insert([$orderData]);
    }

    public function countRecordsWhereOrderStatusEquals($value): int
    {
        return $this->database->table(self::TableName)->where(self::ColumnOrderStatus, $value)->count();
    }

    private function getAcceptedPayments()
    {
        return $this->database->table(self::TableName)->where(self::ColumnOrderStatus, 1)->select(self::ColumnPrice)->fetchAll();
    }
}
