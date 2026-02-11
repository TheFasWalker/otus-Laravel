<?php

namespace App\Http\Controllers\admin\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\tag\CreateTagRequest;
use App\Http\Resources\api\TagResource;
use App\Http\Resources\api\TagsResource;
use App\Http\Requests\tag\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\TagsServece;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return TagResource::collection(Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTagRequest $request, TagsServece $tagsService)
    {
         Gate::authorize('only-admin');
        try {
            // Создание тега через сервис
            $tag = $tagsService->createTag($request->validated());
            
            // Успешный ответ
            return response()->json([
                'success' => true,
                'message' => 'Тег "' . $tag->name . '" успешно создан!',
                'data' => new TagResource($tag),
                'created_at' => $tag->created_at,
                'id' => $tag->id
            ], 201); // 201 Created
            
        } catch (\Exception $e) {
            // Ошибка при создании
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании тега',
                'error' => $e->getMessage(),
                'code' => 500,
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return  new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagsServece $tagsServece, UpdateTagRequest $request, int $id)
    {
        Gate::authorize('only-admin');
                try{
            $tag = $tagsServece->update($id,$request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Тег "' . $tag->name . '" успешно обновлен!',
                'data' => new TagResource($tag),
                'created_at' => $tag->created_at,
                'id' => $tag->id
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при обновлении тега',
                    'error' => $e->getMessage(),
                    'code' => 500,
                    'timestamp' => now()->toISOString()
                ], 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TagsServece $tagsServece, int $id)
    {
        Gate::authorize('only-admin');
        try{
            $tagsServece->deleteTagById($id);
            return response()->json([
                'success' => true,
                'message' => 'Тег успешно Удалён!',
            ], 201);
        }catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при удалении тега',
                    'error' => $e->getMessage(),
                    'code' => 500,
                    'timestamp' => now()->toISOString()
                ], 500);
            }
    }
}
