<?php

namespace App\Models;

use Nette;

class UserFacade
{
    private const
        TableName = 'users',
        ColumnId = 'user_id',
        ColumnName = 'name',
        ColumnSurname = 'surname',
        ColumnGender = 'gender',
        ColumnEmail = 'email',
        ColumnPassword = 'password',
        ColumnPhone = 'phone',
        ColumnDayB = 'dayB',
        ColumnAddress = 'address',
        ColumnCity = 'city',
        ColumnRoleId = 'role_id';

    public function __construct(private Nette\Database\Explorer $database)
    {
    }

    public function getUserByEmail(string $email)
    {
        return $this->database->table(self::TableName)->where(self::ColumnEmail, $email)->fetch();
    }

    public function getAllUsers()
    {
        return $this->database->table(self::TableName)->where(self::ColumnRoleId, 2)->fetchAll();
    }

    public function getUserById(int $id)
    {
        return $this->database->table(self::TableName)->get($id);
    }

    public function getPasswordHashByUserId(int $user_id)
    {
        return $this->database->table(self::TableName)->select('password')->get($user_id);
    }

    public function getNumberOfUsers()
    {
        return $this->database->table(self::TableName)->where(self::ColumnRoleId, 2)->count();
    }

    public function countGender(string $gender)
    {
        return $this->database->table(self::TableName)->where(self::ColumnRoleId, 2)->where(self::ColumnGender, $gender)->count();
    }

    public function getUserGenderById(int $id)
    {
        $user = $this->getUserById($id);
        return $user ? $user->gender === 'M' : false;
    }

    public function updateUserData(int $id, array $data)
    {
        $this->database->table(self::TableName)->where(self::ColumnId, $id)->update($data);
    }

    public function updateUserAddress(int $id, string $address, string $city)
    {
        $this->updateUserData($id, [self::ColumnAddress => $address, self::ColumnCity => $city]);
    }

    public function addUser(array $userData)
    {
        $userData[self::ColumnPassword] = password_hash($userData[self::ColumnPassword], PASSWORD_DEFAULT);
        $this->database->table(self::TableName)->insert($userData);
    }

    public function deleteUser(int $id)
    {
        $this->database->table(self::TableName)->where(self::ColumnId, $id)->delete();
    }

    public function changePassword(int $id, string $password)
    {
        $this->updateUserData($id, [self::ColumnPassword => password_hash($password, PASSWORD_DEFAULT)]);
    }
}