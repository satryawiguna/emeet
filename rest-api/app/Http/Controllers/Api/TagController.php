<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Serializer\ArraySerializer;

class TagController extends Controller
{
    public function actionViewAllTag()
    {
        if (!Auth::user()->can('view', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $tags = (new Tag())->orderBy('id', 'DESC')
            ->get();

        return fractal($tags, new TagTransformer())
            ->toArray();
    }

    public function actionStoreTag(Request $request)
    {
        if (!Auth::user()->can('create', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedTag = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validatedTag->fails()) {
            $messages = $validatedTag->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $tag = new Tag([
            'name' => $request->input('name')
        ]);

        $tag->save();

        return fractal($tag, new TagTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionViewTag(int $id)
    {
        if (!Auth::user()->can('view', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $tag = Tag::find($id);

        return fractal($tag, new TagTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionUpdateTag(Request $request, int $id)
    {
        if (!Auth::user()->can('update', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedTag = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validatedTag->fails()) {
            $messages = $validatedTag->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $tag = Tag::find($id);
        $tag->name = $request->input('name');
        $tag->save();

        return fractal($tag, new TagTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionDestroyTag(int $id)
    {
        if (!Auth::user()->can('delete', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        Tag::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog category was delete'
        ], 200);
    }

    public function actionDestroyBulkTag(Request $request)
    {
        if (!Auth::user()->can('delete', [Tag::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $tagIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        Tag::whereIn('id', $tagIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog categories was delete'
        ], 200);
    }
}
