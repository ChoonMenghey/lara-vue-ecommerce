<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class UserController extends Controller
{
    public function index(){
        return Inertia:: render('user/index', [
        'canLogin' => app('router')->has('login'),
        'canRegister' => app('router')->has('register'),
    ]);
    }
}
