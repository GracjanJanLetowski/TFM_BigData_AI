<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user(); 
        return view('profile.mi-perfil', compact('user'));
    }

    public function addBalance()
    {
        $user = Auth::user();
        $user->setBalance($user->getBalance() + 1000);
        $user->save();

        return back()->with('success', __('auth.balance_added_success'));
    }
}
