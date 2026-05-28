<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function setRole(Request $request)
    {
        $request->validate(['role' => 'required|in:admin,waiter,cook']);
        session(['role' => $request->role]);
        return back();
    }
}
