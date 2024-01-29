<?php
namespace App\Service;

use App\Models\User;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}