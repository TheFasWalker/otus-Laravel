<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index(){
        return view('pages.admin.tags.index');
    }
    
    public function create(){
        return view('pages.admin.tags.create');
    }
    public function edit(){
        return view('pages.admin.tags.edit');
    }
}
