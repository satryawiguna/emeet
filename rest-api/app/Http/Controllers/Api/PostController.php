<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Transformers\BlogCategoryTransformer;
use App\Transformers\BlogTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Serializer\ArraySerializer;

class PostController extends Controller
{
    public function actionViewAllBlogCategory()
    {
        if (!Auth::user()->can('view', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogCategories = (new BlogCategory())->orderBy('id', 'DESC')
            ->get();

        return fractal($blogCategories, new BlogCategoryTransformer())
            ->toArray();
    }

    public function actionStoreBlogCategory(Request $request)
    {
        if (!Auth::user()->can('create', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedBlogCategory = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validatedBlogCategory->fails()) {
            $messages = $validatedBlogCategory->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blogCategory = new BlogCategory([
            'name' => $request->input('name')
        ]);

        $blogCategory->save();

        return fractal($blogCategory, new BlogCategoryTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionViewBlogCategory(int $id)
    {
        if (!Auth::user()->can('view', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogCategory = BlogCategory::find($id);

        return fractal($blogCategory, new BlogCategoryTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionUpdateBlogCategory(Request $request, int $id)
    {
        if (!Auth::user()->can('update', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedBlogCategory = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validatedBlogCategory->fails()) {
            $messages = $validatedBlogCategory->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blogCategory = BlogCategory::find($id);
        $blogCategory->name = $request->input('name');
        $blogCategory->save();

        return fractal($blogCategory, new BlogCategoryTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionDestroyBlogCategory(int $id)
    {
        if (!Auth::user()->can('delete', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        BlogCategory::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog category was delete'
        ], 200);
    }

    public function actionDestroyBulkBlogCategory(Request $request)
    {
        if (!Auth::user()->can('delete', [BlogCategory::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogCategoryIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        BlogCategory::whereIn('id', $blogCategoryIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog categories was delete'
        ], 200);
    }



    public function actionViewAllBlog()
    {
        if (!Auth::user()->can('view', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogs = (new Blog())->orderBy('id', 'DESC')
            ->get();

        return fractal($blogs, new BlogTransformer())
            ->parseIncludes(['blogCategory', 'tags', 'comments'])
            ->toArray();
    }

    public function actionStoreBlog(Request $request)
    {
        if (!Auth::user()->can('create', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedBlog = Validator::make($request->all(), [
            'blog_category_id' => 'required|integer',
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validatedBlog->fails()) {
            $messages = $validatedBlog->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blog = new Blog([
            'blog_category_id' => $request->input('blog_category_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        $blog->save();

        $tags = (!is_array($request->input('tags'))) ?
            explode(',', $request->input('tags')) :
            $request->input('tags');

        $blog->tags()->attach($tags);

        return fractal($blog, new BlogTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionStoreComment(Request $request, int $id)
    {
        if (!Auth::user()->can('createComment', [Blog::class])) {
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

        $blog = Blog::find($id);

        $comment = new Comment([
            'user_id' => Auth::user()->id,
            'commentable_id' => $blog->id,
            'commentable_type' => 'blogs',
            'message' => $request->input('message')
        ]);

        $comment->save();

        return fractal($blog, new BlogTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionViewBlog(int $id)
    {
        if (!Auth::user()->can('view', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blog = Blog::find($id);

        return fractal($blog, new BlogTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionUpdateBlog(Request $request, int $id)
    {
        if (!Auth::user()->can('update', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $validatedBlog = Validator::make($request->all(), [
            'blog_category_id' => 'required|integer',
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validatedBlog->fails()) {
            $messages = $validatedBlog->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ], 500);
        }

        $blog = Blog::find($id);
        $blog->blog_category_id = $request->input('blog_category_id');
        $blog->title = $request->input('title');
        $blog->content = $request->input('content');
        $blog->save();

        $tags = (!is_array($request->input('tags'))) ?
            explode(',', $request->input('tags')) :
            $request->input('tags');

        $blog->tags()->sync($tags);

        return fractal($blog, new BlogTransformer())
            ->serializeWith(new ArraySerializer())
            ->toArray();
    }

    public function actionDestroyBlog(int $id)
    {
        if (!Auth::user()->can('delete', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        Blog::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog was delete'
        ], 200);
    }

    public function actionDestroyBulkBlog(Request $request)
    {
        if (!Auth::user()->can('delete', [Blog::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ], 403);
        }

        $blogIds = (!is_array($request->input('ids'))) ?
            explode(',', $request->input('ids')) :
            $request->input('ids');

        Blog::whereIn('id', $blogIds)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Blog was delete'
        ], 200);
    }
}
