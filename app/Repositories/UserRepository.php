<?php

namespace App\Repositories;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use App\Models\UsersDemo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function store($input)
    {
        $userData = Arr::only($input, ['first_name' , 'last_name', 'email' , 'gender' , 'phone_number']);
        $userData['credit'] = 0;
        $userData['password'] = Hash::make($input['password']);
        $userData['hobbies'] = json_encode($input['hobbies']);
        $media = Arr::only($input, 'image');
        $user =  User::create($userData);
        $userRole  = Role::where('name' , 'user')->first();
        $role = Role::find($input['roles'] ?? $userRole->id);
        $user->syncRoles($role);
        $user->syncPermissions(Permission::pluck('name'));
        if ($media) {
            foreach ($media['image'] as $file) {
                $user->addMedia($file)->toMediaCollection('user');
            }
        }
        if(isset($input['register'])){
            Auth::login($user);
        }
    }

    public function update($input, $data)
    {
        $userData = Arr::only($input, ['first_name' , 'last_name', 'email' , 'gender' , 'phone_number']);
        $userData['hobbies'] = json_encode($input['hobbies']);
        $userData['password'] = $input['password'] ? Hash::make($input['password']) : $data->password;
        $data->update($userData);
        $role = Role::find($input['roles']);
        $data->syncRoles($role);
        $deleteImg = $data->getMedia('product');
        if(isset($input['image'])){
            if($input->hasFile('image')){
                foreach ($input['image'] as $file) {
                    $data->addMedia($file)->toMediaCollection('user');
                }
                if($deleteImg){
                    foreach ($deleteImg as $images){
                        $images->delete();
                    }
                }
            }
        }
        $data->syncPermissions(Permission::pluck('name'));
    }
}
