<?php
// UserController

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function top(){
        return view('top');
    }
    
}
