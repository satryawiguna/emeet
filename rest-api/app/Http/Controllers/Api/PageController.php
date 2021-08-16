<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Page;
use App\Transformers\PageTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Serializer\ArraySerializer;

class PageController extends Controller
{
    public function actionViewAllPage()
    {
        if (!Auth::user()->can('view', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogs = (new Page())->orderBy('id', 'DESC')
            ->get();

        return fractal($blogs, new PageTransformer())
            ->parseIncludes(['tags', 'comments'])
            ->toArray();
    }

    public function actionStorePage(Request $request)
    {
        if (!Auth::user()->can('create', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedPage = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validatedPage->fails()) {
            $messages = $validatedPage->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blog = new Page([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        $blog->save();

        $tags = (!is_array($request->input('tags'))) ?
            explode(',', $request->input('tags')) :
            $request->input('tags');

        $blog->tags()->attach($tags);

        return fractal($blog, new PageTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionStoreComment(Request $request, int $id)
    {
        if (!Auth::user()->can('createComment', [Page::class])) {
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

        $blog = Page::find($id);

        $comment = new Comment([
            'user_id' => Auth::user()->id,
            'commentable_id' => $blog->id,
            'commentable_type' => 'blogs',
            'message' => $request->input('message')
        ]);

        $comment->save();

        return fractal($blog, new PageTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionViewPage(int $id)
    {
        if (!Auth::user()->can('view', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blog = Page::find($id);

        return fractal($blog, new PageTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionUpdatePage(Request $request, int $id)
    {
        if (!Auth::user()->can('update', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedPage = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validatedPage->fails()) {
            $messages = $validatedPage->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blog = Page::find($id);
        $blog->title = $request->input('title');
        $blog->content = $request->input('content');
        $blog->save();

        $tags = (!is_array($request->input('tags'))) ?
            explode(',', $request->input('tags')) :
            $request->input('tags');

        $blog->tags()->sync($tags);

        return fractal($blog, new PageTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionDestroyPage(int $id)
    {
        if (!Auth::user()->can('delete', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        Page::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Page was delete'
        ], 200);
    }

    public function actionDestroyBulkPage(Request $request)
    {
        if (!Auth::user()->can('delete', [Page::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        Page::whereIn('id', $blogIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Page was delete'
        ], 200);
    }
}
