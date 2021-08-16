<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Serializer\ArraySerializer;

class CommentController extends Controller
{
    public function actionViewAllComment()
    {
        if (!Auth::user()->can('view', [Comment::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $tags = (new Comment())->orderBy('id', 'DESC')
            ->get();

        return fractal($tags, new CommentTransformer())
            ->toArray();
    }

    public function actionViewComment(int $id)
    {
        if (!Auth::user()->can('view', [Comment::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $tag = Comment::find($id);

        return fractal($tag, new CommentTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionUpdateComment(Request $request, int $id)
    {
        if (!Auth::user()->can('update', [Comment::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedComment = Validator::make($request->all(), [
            'message' => 'required'
        ]);

        if ($validatedComment->fails()) {
            $messages = $validatedComment->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $comment = Comment::find($id);
        $comment->message = $request->input('message');
        $comment->save();

        return fractal($comment, new CommentTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionDestroyComment(int $id)
    {
        if (!Auth::user()->can('delete', [Comment::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        Comment::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog category was delete'
        ], 200);
    }

    public function actionDestroyBulkComment(Request $request)
    {
        if (!Auth::user()->can('delete', [Comment::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $commentIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        Comment::whereIn('id', $commentIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog categories was delete'
        ], 200);
    }
}
