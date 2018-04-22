<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Requests\Admin\ArticleCategoryRequest;
use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use ImageUpload;
use Validator;

class ArticleController extends Controller {

    public function listArticle(Request $request) {
        $response = [
            'title' => "Tin tức"
        ];
        $article_query = Article::select([
                    'articles.article_id',
                    'articles.article_title',
                    'articles.article_slug',
                    'articles.article_status',
                    'articles.article_suggest',
                    'articles.article_featured',
                    'articles.article_created_at',
                    'articles.article_created_by',
                    'articles.article_updated_at',
                    'articles.article_updated_by',
                    'articles.article_meta_title',
                    'categories.category_name',
                ])
                ->join('categories', 'categories.category_id', '=', 'articles.article_category_id')
                ->orderBy('articles.article_created_at', 'DESC');
        if ($request->has('title') && $request->input('title') != "") {
            $article_query->where('articles.article_title', 'LIKE', '%' . $request->input('title') . '%');
        }
        if ($request->has('category') && is_numeric($request->input('category'))) {
            $article_query->where('articles.article_category_id', $request->input('category'));
        }
        if ($request->has('status') && is_numeric($request->input('status'))) {
            $article_query->where('articles.article_status', $request->input('status'));
        }
        if ($request->has('suggest') && is_numeric($request->input('suggest'))) {
            $article_query->where('articles.article_suggest', $request->input('suggest'));
        }
        $response['categories'] = Category::where('category_type', 2)
                        ->orderBy('category_name', 'ASC')->get();
        $response['articles'] = $article_query->paginate(env('PAGINATE_ITEM', 20));
        return view('admin.article.listArticle', $response);
    }

    public function addArticle() {
        $response = [
            'title' => "Thêm tin tức"
        ];
        $response['categories'] = Category::where('category_type', 2)
                        ->orderBy('category_name', 'ASC')->get();
        return view('admin.article.addArticle', $response);
    }

    public function doAddArticle(ArticleRequest $request) {
        $article = new Article;
        $article->article_title = trim($request->input('txt-title'));
        $article->article_slug = str_slug($request->input('txt-title'));
        $article->article_summary = trim($request->input('txt-summary'));
        $article->article_suggest = trim($request->input('article_suggest'));
        if ($request->has('txt-featured-type')) {
            if ($request->hasFile('file-featured') && $request->input('txt-featured-type') == 'file') {
                $article->article_featured = ImageUpload::image($request->file('file-featured'), md5('article_' . $article->article_title . time()));
            } elseif ($request->input('txt-featured') != "" && $request->input('txt-featured-type') == 'url') {
                $article->article_featured = ImageUpload::image($request->input('txt-featured'), md5('article_' . $article->article_title . time()));
            }
        }
        $article_content = trim($request->input('txt-content'));
        if (preg_match_all("/(<img.*?>)/", $article_content, $matches)) {
            foreach ($matches[1] as $img_tag) {
                if (preg_match("/src=\"(.*?)\"/", $img_tag, $img_src)) {
                    if (preg_match("/^" . str_replace(["/", "."], ["\/", "\."], env('APP_URL', '/')) . "/", $img_src[1])) {
                        $src = str_replace(env('APP_URL'), "", $img_src[1]);
                    } else {
                        $src = ImageUpload::image($img_src[1], md5('image_' . $img_src[1] . time()));
                    }
                    $article_content = str_replace($img_tag, '<img src="' . $src . '" />', $article_content);
                }
            }
        }
        $article->article_content = $article_content;
        $article->article_category_id = $request->input('sl-category');
        if ($request->input('txt-meta-title') != "") {
            $article->article_meta_title = trim($request->input('txt-meta-title'));
        } else {
            $article->article_meta_title = $article->article_title;
        }
        if ($request->input('txt-meta-desc') != "") {
            $article->article_meta_desc = trim($request->input('txt-meta-desc'));
        } else {
            $article->article_meta_desc = $article->article_summary;
        }
        $article->article_status = $request->input('rd-status');
        $article->article_created_at = microtime(true);
        $article->article_created_by = $request->session()->get('user')->user_id;
        try {
            $article->save();
            return redirect()->action('Admin\ArticleController@listArticle')->with('success', 'Thêm tin tức "' . $article->article_title . '" thành công');
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function editArticle($article_id) {
        try {
            $article = Article::where('article_id', $article_id)->first();
            if (!empty($article)) {
                $response = [
                    'title' => "Sửa tin tức: " . $article->article_title
                ];
                $article->article_content = str_replace('src="uploads/', 'src="' . env('APP_URL') . 'uploads/', $article->article_content);
                $response['article'] = $article;
                $response['categories'] = Category::where('category_type', 2)
                                ->orderBy('category_name', 'ASC')->get();
                return view('admin.article.editArticle', $response);
            } else {
                return redirect()->action('Admin\ArticleController@listArticle')->with('error', 'Tin tức không tồn tại');
            }
        } catch (\Exception $ex) {
            return redirect()->action('Admin\ArticleController@listArticle')->with('error', 'Lỗi trong quá trình xử lý dữ liệu ');
        }
    }

    public function doEditArticle(ArticleRequest $request, $article_id) {
        try {
            $article = Article::where('article_id', $article_id)->first();
            if (!empty($article)) {
                $article->article_title = trim($request->input('txt-title'));
                $article->article_slug = str_slug($request->input('txt-title'));
                $article->article_summary = trim($request->input('txt-summary'));
                $article->article_suggest = trim($request->input('article_suggest'));
                if ($request->has('txt-featured-type')) {
                    if ($request->hasFile('file-featured') && $request->input('txt-featured-type') == 'file') {
                        $article->article_featured = ImageUpload::image($request->file('file-featured'), md5('article_' . $article->article_title . time()));
                    } elseif ($request->input('txt-featured') != "" && $request->input('txt-featured-type') == 'url') {
                        $article->article_featured = ImageUpload::image($request->input('txt-featured'), md5('article_' . $article->article_title . time()));
                    }
                }
                $article_content = trim($request->input('txt-content'));
                if (preg_match_all("/(<img.*?>)/", $article_content, $matches)) {
                    foreach ($matches[1] as $img_tag) {
                        if (preg_match("/src=\"(.*?)\"/", $img_tag, $img_src)) {
                            if (preg_match("/^" . str_replace(["/", "."], ["\/", "\."], env('APP_URL', '/')) . "/", $img_src[1])) {
                                $src = str_replace(env('APP_URL'), "", $img_src[1]);
                            } else {
                                $src = ImageUpload::image($img_src[1], md5('image_' . $img_src[1] . time()));
                            }
                            $article_content = str_replace($img_tag, '<img src="' . $src . '" />', $article_content);
                        }
                    }
                }
                $article->article_content = $article_content;
                $article->article_category_id = $request->input('sl-category');
                if ($request->input('txt-meta-title') != "") {
                    $article->article_meta_title = trim($request->input('txt-meta-title'));
                } else {
                    $article->article_meta_title = $article->article_title;
                }
                if ($request->input('txt-meta-desc') != "") {
                    $article->article_meta_desc = trim($request->input('txt-meta-desc'));
                } else {
                    $article->article_meta_desc = $article->article_summary;
                }
                $article->article_status = $request->input('rd-status');
                $article->article_updated_at = microtime(true);
                $article->article_updated_by = $request->session()->get('user')->user_id;
                try {
                    $article->save();
                    return redirect()->back()->with('success', 'Sửa tin tức "' . $article->article_title . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->action('Admin\ArticleController@listArticle')->with('error', 'Tin tức không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }

    public function doHandleImage(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                        'file-image' => "required|image",
                            ], [
                        'file-image.required' => "Hình ảnh không hợp lệ",
                        'file-image.image' => "Hình ảnh không hợp lệ",
            ]);
            if (!$validator->fails()) {
                try {
                    $name = md5('image' . $request->file('file-image')->getClientOriginalName() . time());
                    $path = ImageUpload::image($request->file('file-image'), $name);
                    return response()->json([
                                "status_code" => 200,
                                "data" => env('APP_URL') . $path
                    ]);
                } catch (\Exception $ex) {
                    return response()->json([
                                "status_code" => 500,
                                "message" => "Lỗi trong quá trình xử lý dữ liệu",
                    ]);
                }
            } else {
                return response()->json([
                            "status_code" => 442,
                            "message" => $validator->errors()->first(),
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

    public function doHandleContent(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                        'txt-text' => "required",
                            ], [
                        'txt-text.required' => "Nội dung không được để trống",
            ]);
            if (!$validator->fails()) {
                try {
                    $content = $this->handleContent($request->input('txt-text'));
                    return response()->json([
                                "status_code" => 200,
                                "data" => $content
                    ]);
                } catch (\Exception $ex) {
                    return response()->json([
                                "status_code" => 500,
                                "message" => "Lỗi trong quá trình xử lý dữ liệu",
                    ]);
                }
            } else {
                return response()->json([
                            "status_code" => 442,
                            "message" => $validator->errors()->first(),
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

    public function doDeleteArticle(DeleteRequest $request) {
        try {
            $article = Article::select(['article_title', 'article_id'])->where('article_id', $request->input('txt-id'))->first();
            if (!empty($article)) {
                try {
                    $article->delete();
                    return redirect()->back()->with('success', 'Xóa tin tức "' . $article->article_title . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Tin tức không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }

    private function handleContent($content) {
        $content = preg_replace("/&nbsp;/", " ", $content);
        $content = preg_replace("/style=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/class=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/type=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/id=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/width=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/height=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/height=(\"|\').*?(\"|\')/", "", $content);
        $content = preg_replace("/<\/?span.*?>/", "", $content);
        $content = preg_replace("/<h(\d{1}).*?>/", "<p>", $content);
        $content = preg_replace("/<\/h(\d{1})>/", "</p>", $content);
        $content = preg_replace("/<table.*?>(.*?)<\/table>/", "$1", $content);
        $content = preg_replace("/<tbody.*?>(.*?)<\/tbody>/", "$1", $content);
        $content = preg_replace("/<tr.*?>(.*?)<\/tr>/", "$1", $content);
        $content = preg_replace("/<td.*?>(.*?)<\/td>/", "$1", $content);
        $content = preg_replace("/<img.*?src=(\"|\')(.*?)(\"|\').*?>/", "<p><img src=\"$2\" /></p><p>", $content);
        $content = preg_replace("/<a.*?>(.*?)<\/a>/", "$1", $content);
        $content = preg_replace("/<html.*?>.*?<\/html>/", "", $content);
        $content = preg_replace("/<ins.*?>.*?<\/ins>/", "", $content);
        $content = preg_replace("/<iframe.*?>.*?<\/iframe>/", "", $content);
        $content = preg_replace("/<script.*?>.*?<\/script>/", "", $content);
        $content = preg_replace("/<style.*?>.*?<\/style>/", "", $content);
        $content = preg_replace("/<label.*?>.*?<\/label>/", "", $content);
        $content = preg_replace("/<input.*?>/", "", $content);
        $content = preg_replace("/<!--.*?-->/", "", $content);
        $content = preg_replace("/<(b|h)r.*?>/", "", $content);
        $content = preg_replace("/<div.*?>/", "<p>", $content);
        $content = preg_replace("/<\/div>/", "</p>", $content);
        $content = preg_replace("/<p.*?>/", "</p><p>", $content);
        $content = preg_replace("/<em.*?>/", "<em>", $content);
        $content = preg_replace("/<strong.*?>/", "<strong>", $content);
        $content = preg_replace("/<(em|p|i|strong|b|u)\s*?>(&nbsp;|\s)?<\/(em|p|i|strong|b|u)>/", "", $content);
        $content = preg_replace("/<figure.*?>(.*?)<\/figure>/", "<p>$1</p>", $content);
        $content = preg_replace("/<figcaption.*?>(.*?)<\/figcaption>/", "</p><p>$1</p>", $content);
        $content = preg_replace("/<em><p><em>/", "<p><em>", $content);
        $content = preg_replace("/<\/em><\/p><\/em>/", "</em></p>", $content);
        $content = preg_replace("/<ul.*?>/", "<p>", $content);
        $content = preg_replace("/<\/ul>/", "</p>", $content);
        $content = preg_replace("/<li.*?>/", "<p>", $content);
        $content = preg_replace("/<\/li>/", "</p>", $content);
        $content = html_entity_decode($content);
        $content = preg_replace("/\s(\.|\,|\?|\!)/", "$1 ", $content);
        while (preg_match("/\s{2}/", $content)) {
            $content = preg_replace("/\s{2}/", " ", $content);
        }
        $content = preg_replace("/>\s+</", "><", $content);
        while (preg_match("/<em><em>/", $content)) {
            $content = preg_replace("/<em><em>/", "<em>", $content);
        }
        while (preg_match("/<\/em><\/em>/", $content)) {
            $content = preg_replace("/<\/em><\/em>/", "</em>", $content);
        }
        while (preg_match("/<strong><strong>/", $content)) {
            $content = preg_replace("/<strong><strong>/", "<strong>", $content);
        }
        while (preg_match("/<\/strong><\/strong>/", $content)) {
            $content = preg_replace("/<\/strong><\/strong>/", "</strong>", $content);
        }
        while (preg_match("/<p><p>/", $content)) {
            $content = preg_replace("/<p><p>/", "<p>", $content);
        }
        while (preg_match("/<\/p><\/p>/", $content)) {
            $content = preg_replace("/<\/p><\/p>/", "</p>", $content);
        }
        $content = preg_replace("/<(em|p|i|strong|b|u)><\/(em|p|i|strong|b|u)>/", "", $content);
        $content = preg_replace("/“/", "\"", $content);
        $content = preg_replace("/”/", "\"", $content);
        $content = preg_replace("/‘/", "'", $content);
        $content = preg_replace("/’/", "'", $content);
        $content = preg_replace("/^<\/p>(.*?)/", "$1", $content);
        return trim($content);
    }

    public function listCategory() {
        $response = [
            'title' => "Chuyên mục tin tức"
        ];
        $category_query = Category::where('category_type', 2)
                ->orderBy('category_name', 'ASC');
        $response['categories'] = $category_query->paginate(env('PAGINATE_ITEM', 20));
        return view('admin.article.listCategory', $response);
    }

    public function doAddCategory(ArticleCategoryRequest $request) {
        try {
            $slug = str_slug($request->input('txt-name'));
            $category = Category::select(['category_id'])
                    ->where([
                        'category_slug' => $slug,
                        'category_type' => 2
                    ])
                    ->first();
            if (empty($category)) {
                $category = new Category;
                $category->category_name = trim($request->input('txt-name'));
                $category->category_slug = $slug;
                $category->category_meta_title = $category->category_name;
                if ($request->input('txt-meta-title') != "") {
                    $category->category_meta_title = $request->input('txt-meta-title');
                }
                $category->category_meta_desc = $request->input('txt-meta-desc');
                $category->category_type = 2;
                $category->category_status = 1;
                $category->category_created_at = microtime(true);
                $category->category_created_by = $request->session()->get('user')->user_id;
                try {
                    $category->save();
                    return redirect()->back()->with('success', 'Thêm chuyên mục "' . $category->category_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Chuyên mục tin tức đã tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function loadCategory(Request $request) {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                        'category_id' => "required|alpha_num",
                            ], [
                        'category_id.required' => "Chuyên mục tin tức không hợp lệ",
                        'category_id.alpha_num' => "Chuyên mục tin tức không hợp lệ",
            ]);
            if (!$validator->fails()) {
                try {
                    $category = Category::select([
                                        'category_id',
                                        'category_name',
                                        'category_slug',
                                        'category_meta_desc',
                                        'category_meta_title'
                                    ])
                                    ->where([
                                        'category_id' => $request->input('category_id'),
                                        'category_type' => 2
                                    ])->first();
                    if (!empty($category)) {
                        return response()->json([
                                    "status_code" => 200,
                                    "data" => $category
                        ]);
                    } else {
                        return response()->json([
                                    "status_code" => 404,
                                    "message" => "Chuyên mục tin tức không tồn tại",
                        ]);
                    }
                } catch (\Exception $ex) {
                    return response()->json([
                                "status_code" => 500,
                                "message" => "Lỗi trong quá trình xử lý dữ liệu",
                    ]);
                }
            } else {
                return response()->json([
                            "status_code" => 422,
                            "message" => $validator->errors()->first(),
                ]);
            }
        }
        return redirect()->action('Admin\HomeController@index')->with('error', 'Không được truy cập trực tiếp');
    }

    public function doEditCategory(ArticleCategoryRequest $request) {
        try {
            $category = Category::where([
                        'category_id' => $request->input('txt-id'),
                        'category_type' => 2
                    ])->first();
            if (!empty($category)) {
                $category->category_name = trim($request->input('txt-name'));
                $category->category_slug = str_slug($request->input('txt-name'));
                $category->category_meta_title = $category->category_name;
                if ($request->input('txt-meta-title') != "") {
                    $category->category_meta_title = $request->input('txt-meta-title');
                }
                $category->category_meta_desc = $request->input('txt-meta-desc');
                $category->category_updated_at = microtime(true);
                $category->category_updated_by = $request->session()->get('user')->user_id;
                try {
                    $category->save();
                    return redirect()->back()->with('success', 'Sửa chuyên mục tin tức "' . $category->category_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
                }
            } else {
                return redirect()->back()->with('error', "Chuyên mục không tồn tại");
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', "Lỗi trong quá trình xử lý dữ liệu");
        }
    }

    public function doDeleteCategory(DeleteRequest $request) {
        try {
            $category = Category::select(['category_name', 'category_id'])
                            ->where('category_id', $request->input('txt-id'))->first();
            if (!empty($category)) {
                try {
                    $category->delete();
                    return redirect()->back()->with('success', 'Xóa chuyên mục tin tức "' . $category->category_name . '" thành công');
                } catch (\Exception $exc) {
                    return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
                }
            } else {
                return redirect()->back()->with('error', 'Chuyên mục tin tức không tồn tại');
            }
        } catch (\Exception $exc) {
            return redirect()->back()->with('error', 'Lỗi trong quá trình xử lý dữ liệu');
        }
    }

}
