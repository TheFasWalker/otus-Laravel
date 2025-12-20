<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\tag\CreateTagRequest;
use App\Http\Requests\tag\UpdateTagRequest;
use App\Services\TagsServece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagsController extends Controller
{
    public function index(TagsServece $tagsServece){
        $tags = $tagsServece->getAllTags();
        return view('pages.admin.tags.index', compact('tags'));
    }
    
    public function create(){
        Gate::authorize('only-admin');
        return view('pages.admin.tags.create');
    }
    public function edit(TagsServece $tagsServece, $id){
        Gate::authorize('only-admin');
        $tag = $tagsServece->getTagById($id);
        return view('pages.admin.tags.edit', compact('tag'));
    }

    public function store(TagsServece $tagsServece, CreateTagRequest $request){
        Gate::authorize('only-admin');
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
        Gate::authorize('only-admin');
        try{
            $tagsServece->deleteTagById($id);
            return redirect()->route('admin.tags');
        }catch (\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function update(TagsServece $tagsServece, UpdateTagRequest $request, int $id){
        Gate::authorize('only-admin');
        try{
            $tag = $tagsServece->update($id,$request->validated());
            return redirect()->route('admin.tags');

        }catch (\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

    }
}
