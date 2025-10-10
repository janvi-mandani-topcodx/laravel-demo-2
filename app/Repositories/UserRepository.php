<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\UsersDemo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function store($input)
    {
        $userData = Arr::only($input, ['first_name' , 'last_name', 'email' , 'gender' , 'phone_number']);
        $userData['password'] = Hash::make($input['password']);
        $userData['hobbies'] = json_encode($input['hobbies']);
        User::create($userData);
    }

    public function update($input, $data)
    {
        $userData = Arr::only($input, ['first_name' , 'last_name', 'email' , 'gender' , 'phone_number']);
        $userData['hobbies'] = json_encode($input['hobbies']);
        $data->update($userData);
    }
}
