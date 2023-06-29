<?php

namespace App\Http\Controllers\Admin\Event;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
  public function __invoke()
  {
    return view('admin.event.index');
  }
}
