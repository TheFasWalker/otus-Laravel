<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\TagsServece;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index(TagsServece $tagsServece){
        $tags = $tagsServece->getAllTags();
        return view('pages.admin.tags.index', compact('tags'));
    }
    
    public function create(){
        return view('pages.admin.tags.create');
    }
    public function edit(TagsServece $tagsServece, $id){
        $tag = $tagsServece->getTagById($id);
        return view('pages.admin.tags.edit', compact('tag'));
    }
}
