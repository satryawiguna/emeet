<?php

namespace App\Transformers;

use App\Models\Blog;
use League\Fractal\TransformerAbstract;

class BlogTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'blogCategory', 'tags', 'comments'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Blog $blog)
    {
        return [
            'title' => $blog->title,
            'slug' => $blog->slug,
            'content' => $blog->content
        ];
    }

    public function includeTags(Blog $blog)
    {
        $tags = $blog->tags;

        return $this->collection($tags, new TagTransformer());
    }

    public function includeComments(Blog $blog)
    {
        $comments = $blog->comments;

        return $this->collection($comments, new CommentTransformer());
    }

    public function includeBlogCategory(Blog $blog)
    {
        $blogCategory = $blog->blogCategory;

        return $this->item($blogCategory, new BlogCategoryTransformer());
    }
}
