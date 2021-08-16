<?php

namespace App\Transformers;

use App\Models\BlogCategory;
use League\Fractal\TransformerAbstract;

class BlogCategoryTransformer extends TransformerAbstract
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
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BlogCategory $blogCategory)
    {
        return [
            'id' => $blogCategory->id,
            'name' => $blogCategory->name,
            'created_at' => $blogCategory->created_at
        ];
    }
}
