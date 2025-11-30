<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\tag\CreateTagRequest;
use App\Http\Requests\tag\UpdateTagRequest;
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

    public function save(TagsServece $tagsServece, CreateTagRequest $request){
        try{
            $tag = $tagsServece->createTag($request->validated());
            return redirect()->route('admin.tags')
            ->with('success', 'Тег "' . $tag->name . '" успешно создан!');
        }catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

    }

    public function delete(TagsServece $tagsServece, int $id)
    {
        try{
            $tagsServece->deleteTagById($id);
            return redirect()->route('admin.tags');
        }catch (\Exception $e) {
            return redirect()->back()->whith('error',$e->getMessage());
        }
    }

    public function update(TagsServece $tagsServece, UpdateTagRequest $request, int $id){
        try{
            $tag = $tagsServece->update($id,$request->validated());
            return redirect()->route('admin.tags');

        }catch (\Exception $e) {
            return redirect()->back()->whith('error',$e->getMessage());
        }

    }
}
