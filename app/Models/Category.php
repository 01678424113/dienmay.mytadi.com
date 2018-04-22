<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

  /*  public static function recursive($website_id = 0, $category_parent = 0) {
        if ($website_id != 0) {
            $categories = self::select(['content_categories.category_id', 'content_categories.category_name'])
                ->join('content_website_category', 'content_website_category.category_id', '=', 'content_categories.category_id')
                ->where('content_website_category.website_id', $website_id)
                ->where('content_categories.category_parent', $category_parent)->orderBy('content_categories.category_name', 'ASC')->get();
        } else {
            $categories = self::select(['category_id', 'category_name'])
                ->where('category_parent', $category_parent)->orderBy('category_name', 'ASC')->get();
        }
        foreach ($categories as $category) {
            $category->child = self::recursive(0, $category->category_id);
        }
        return $categories;
    }*/

    public function product()
    {
        return $this->hasMany(Product::class,'category_id','category_id');
    }

}
