<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request, $id) {
    	$user = User::find($id);

    	return Response::json(array(
    		'error'=>false,
    		'user'=>$user),
    		200
    	);
    }
}
