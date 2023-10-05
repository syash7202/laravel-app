<?php

namespace App\Http\Controllers;

use App\Events\SendRegistrationToCodeIgniter;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Listeners\SendRegistrationDataToCodeIgniter;

class CustomRegisterController extends Controller
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        event(new SendRegistrationToCodeIgniter($user));
        return redirect()->route('dashboard');
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'passwordview' => $data['password'],
        ]);

        return $user;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

}
