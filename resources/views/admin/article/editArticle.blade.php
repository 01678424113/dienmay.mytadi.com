@extends('admin.layout')
@section('style')
    {{ Html::style('cms/assets/global/plugins/select2/css/select2.min.css') }}
    {{ Html::style('cms/assets/global/plugins/select2/css/select2-bootstrap.min.css') }}
    {{ Html::style('cms/assets/global/plugins/bootstrap-summernote/summernote.css') }}
    {{ Html::style('cms/assets/global/plugins/icheck/skins/all.css') }}
@endsection
@section('pagecontent')
    <div class="page-bar m-b-20">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{URL::action('Admin\HomeController@index')}}">Bảng điều khiển</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{URL::action('Admin\ArticleController@listArticle')}}">Tin tức</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <span>{!! $title !!}</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <a href="{{URL::action('Admin\ArticleController@listArticle')}}" class="btn default btn-sm uppercase">
                <i class="fa fa-arrow-left m-r-5"></i>Quay lại</a>
        </div>
    </div>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">Nội dung tin tức</span>
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['action' => ['Admin\ArticleController@doEditArticle', 'article_id'=>$article->article_id], 'method' => 'POST', 'id'=> 'article-form', 'files' => true]) !!}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label">Tiêu đề <span class="required"> * </span></label>
                            <input type="text" name="txt-title" id="txt-title" class="form-control"
                                   value="{{$article->article_title}}"/>
                        </div>

                        <div class="form-group" style="width:25%;">
                            <label class="control-label">Tin tức nổi bật</label>
                            <select name="article_suggest" id="" class="form-control">
                                <option value="0"
                                @if($article->article_suggest == 0)
                                    {{"selected"}}
                                        @endif
                                >Không</option>
                                <option value="1"
                                @if($article->article_suggest == 1)
                                    {{"selected"}}
                                        @endif
                                >Có</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Tóm tắt</label>
                            <textarea class="form-control" name="txt-summary"
                                      rows="3">{!! $article->article_summary !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nội dung</label>
                            <textarea class="form-control"
                                      name="txt-content">{!! $article->article_content !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Ảnh tiêu biểu</label>
                            <div>
                                <a role="button" data-toggle="modal" data-target="#featured-modal">
                                    <img src="{{ env('APP_URL') . $article->article_featured}}"
                                         data-old="{{ env('APP_URL') . $article->article_featured }}"
                                         style="max-width: 100%" id="featured-img"
                                         class="img-thumbnail"
                                    />
                                </a>
                            </div>
                            <div id="featured-modal" class="modal fade" tabindex="-1" data-keyboard="false"
                                 style="margin-top: 5%">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"></button>
                                            <h4 class="modal-title text-uppercase">Chọn ảnh tiêu biểu</h4>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="txt-featured-type" value="none">
                                            <div class="form-group">
                                                <label class="control-label">Chọn từ files</label>
                                                <input type="file" class="form-control" name="file-featured"
                                                       accept="image/*">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">URL ảnh</label>
                                                <input type="text" class="form-control" name="txt-featured"
                                                       value="{{ env('APP_URL') . $article->article_featured }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btn-featured" class="btn blue text-uppercase">
                                                Xác nhận
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Chuyên mục <span class="required"> * </span></label>
                            <select class="form-control" name="sl-category">
                                <option value="">Chọn chuyên mục</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->category_id}}"
                                            {{ $category->category_id == $article->article_category_id ? 'selected' : '' }}
                                    >{{$category->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tiêu đề SEO</label>
                            <input type="text" name="txt-meta-title" class="form-control"
                                   value="{{$article->article_meta_title}}"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mô tả SEO</label>
                            <textarea class="form-control" name="txt-meta-desc"
                                      rows="5">{!! $article->article_meta_desc !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Trạng thái</label>
                            <div class="input-group">
                                <div class="icheck-inline">
                                    <label class="control-label" role="button">
                                        <input type="radio" name="rd-status" class="icheck" value="1"
                                               data-radio="iradio_minimal-green"
                                                {{ $article->article_status == 1 ? 'checked' : '' }}
                                        />
                                        <span class="text-success">Công khai</span>
                                    </label>
                                    <label class="control-label" role="button">
                                        <input type="radio" name="rd-status" class="icheck" value="0"
                                               data-radio="iradio_minimal-green"
                                                {{ $article->article_status == 0 ? 'checked' : '' }}
                                        />
                                        <span class="text-warning">Bản nháp</span>
                                    </label>
                                    <label class="control-label" role="button">
                                        <input type="radio" name="rd-status" class="icheck" value="-1"
                                               data-radio="iradio_minimal-green"
                                                {{ $article->article_status == -1 ? 'checked' : '' }}
                                        />
                                        <span class="text-danger">Hủy đăng</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn blue uppercase">Lưu chỉnh sửa</button>
                <a href="{{URL::action('Admin\ArticleController@listArticle')}}" data-dismiss="modal"
                   class="btn red-soft uppercase">Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {{ Html::script('cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
    {{ Html::script('cms/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
    {{ Html::script('cms/assets/global/plugins/select2/js/select2.full.min.js') }}
    {{ Html::script('cms/assets/global/plugins/bootstrap-summernote/summernote.min.js') }}
    {{ Html::script('cms/assets/global/plugins/bootstrap-summernote/lang/summernote-vi-VN.min.js') }}
    {{ Html::script('cms/assets/global/plugins/icheck/icheck.min.js') }}
    <script>
        $(document).ready(function () {
            $('#article-form').validate({
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    'txt-title': {
                        required: true
                    },
                    'sl-category': {
                        required: true,
                        number: true
                    },
                    'file-featured': {
                        accept: "image/*"
                    }
                },
                messages: {
                    'txt-title': {
                        required: "Tiêu đề không được để trống"
                    },
                    'sl-category': {
                        required: "Chưa chọn chuyên mục tin tức",
                        number: "Chuyên mục tin tức không hợp lệ"
                    },
                    'file-featured': {
                        accept: "Ảnh tiêu biểu không hợp lệ"
                    }
                },
                invalidHandler: function (event, validator) {
                },
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                success: function (label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },
                errorPlacement: function (error, element) {
                    element.closest('.form-group').append(error);
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
            $('#article-form').find('select[name="sl-category"]').select2({
                language: {
                    noResults: function () {
                        return "Không tìm thấy chuyên mục tin tức nào";
                    }
                }
            }).on('change', function () {
                var category_id = $.trim($(this).val());
                if (category_id !== "") {
                    var parent = $(this).closest('.form-group');
                    parent.removeClass('has-error');
                    parent.find('.help-block').remove();
                }
            });
            $('#article-form').find('textarea[name="txt-content"]').summernote({
                height: 400,
                minHeight: null,
                maxHeight: null,
                focus: false,
                lang: 'vi-VN',
                toolbar: [
                    ['temp', ['style']],
                    ['style', ['bold', 'italic', 'underline']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['clear', ['codeview', 'clear']]
                ], popover: {
                    image: [
                        ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['custom', ['imageAttributes']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ]
                },
                disableDragAndDrop: true,
                callbacks: {
                    onImageUpload: function (files, editor, welEditable) {
                        uploadImage(files[0], editor, welEditable);
                    },
                    onPaste: function (e) {
                        var text = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                        if (text !== "") {
                            e.preventDefault();
                            var label = $(this).closest('.form-group').find('.control-label');
                            label.append('<i class="fa fa-spin fa-circle-o-notch m-l-5"></i>');
                            $.ajax({
                                url: "{{URL::action('Admin\ArticleController@doHandleContent')}}",
                                type: "POST",
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'txt-text': text
                                },
                                dataType: "text",
                                timeout: 30000,
                                error: function (jqXHR, textStatus, errorThrow) {
                                    label.find('i').remove();
                                    toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                                },
                                success: function (data) {
                                    e.preventDefault();
                                    var json_data = $.parseJSON(data);
                                    if (json_data.status_code === 200) {
                                        document.execCommand('insertHtml', false, json_data.data.replace('<body></p>', '<body>'));
                                    } else {
                                        toastr['error'](json_data.message);
                                    }
                                    label.find('i').remove();
                                }
                            });
                        }
                    }
                }
            });
            $('#article-form').find('input[name="file-featured"]').change(function () {
                var files = $('#featured-modal').find('input[name="file-featured"]').prop('files');
                if (files.length) {
                    var regex_type = /^(image\/jpeg|image\/png|image\/gif)$/;
                    $.each(files, function (key, file) {
                        if (regex_type.test(file.type)) {
                            var fr = new FileReader();
                            fr.readAsDataURL(file);
                            fr.onload = function (event) {
                                $('#featured-img').attr('src', event.target.result);
                                $('#article-form').find('input[name="txt-featured-type"]').val('file');
                                $('#featured-modal').find('input[name="txt-featured"]').val("");
                                $('#featured-modal').modal('hide');
                            };
                        } else {
                            $('#featured-img').attr('src', $('#featured-img').data('old'));
                            $('#article-form').find('input[name="txt-featured-type"]').val('none');
                        }
                    });
                } else {
                    $('#featured-img').attr('src', $('#featured-img').data('old'));
                    $('#article-form').find('input[name="txt-featured-type"]').val('none');
                }
            });
            $('#btn-featured').click(function () {
                var url = $('#featured-modal').find('input[name="txt-featured"]').val();
                var regex_url = /(https?:\/\/(.*)\.(png|jpg|jpeg|gif))/i;
                if (url !== "" && regex_url.test(url)) {
                    $('#featured-img').attr('src', url);
                    $('#article-form').find('input[name="txt-featured-type"]').val('url');
                    $('#article-form').find('input[name="file-featured"]').val(null);
                }
                $('#featured-modal').modal('hide');
            });
        });

        function uploadImage(file, editor, welEditable) {
            var data = new FormData();
            data.append('_token', '{{ csrf_token() }}');
            data.append("file-image", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "{{ URL::action('Admin\ArticleController@doHandleImage') }}",
                contentType: false,
                processData: false,
                error: function (jqXHR, textStatus, errorThrow) {
                    toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                },
                success: function (data) {
                    if (data.status_code === 200) {
                        $('#article-form').find('textarea[name="txt-content"]').summernote('editor.insertImage', data.data);
                    } else {
                        toastr['error'](data.message);
                    }
                }
            });
        }
    </script>
@endsection
