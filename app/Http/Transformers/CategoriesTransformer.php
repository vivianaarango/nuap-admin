<?php
namespace App\Http\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

/**
 * Class CategoriesTransformer
 * @package App\Http\Transformers
 */
class CategoriesTransformer extends TransformerAbstract
{
    /**
     * @param Category $category
     * @return array
     */
    public function transform(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'image' => $category->image
        ];
    }
}
