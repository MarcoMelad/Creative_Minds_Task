<?php

namespace App\Interfaces;


use Illuminate\Http\Request;

interface UserAuthRepositoryInterface
{
    public function login(array $data);

    public function register(array $data);
    public function verify(Request $request);

    public function user_profile();

    public function logout();
    public function nearestDelivery();
}
