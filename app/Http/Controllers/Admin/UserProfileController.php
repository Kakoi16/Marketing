<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct()
    {
        view()->share('user', auth()->user());
    }

    public function index()
    {
        $profiles = Profile::with('user')->get();
        return view('admin.userallprofile', compact('profiles'));
    }

    public function show($id)
    {
        $profile = Profile::with('user')->findOrFail($id);
        return view('admin.userprofile_show', compact('profile'));
    }
}
