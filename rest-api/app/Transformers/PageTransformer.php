<?php

namespace App\Transformers;

use App\Models\Page;
use League\Fractal\TransformerAbstract;

class PageTransformer extends TransformerAbstract
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
        'tags', 'comments'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Page $page)
    {
        return [
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content
        ];
    }

    public function includeTags(Page $page)
    {
        $tags = $page->tags;

        return $this->collection($tags, new TagTransformer());
    }

    public function includeComments(Page $page)
    {
        $comments = $page->comments;

        return $this->collection($comments, new CommentTransformer());
    }
}
