<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
      return view('admin.auth.login');
    }

    public function logout()
    {
      Auth::logout();

      return redirect('/login');
    }
}
