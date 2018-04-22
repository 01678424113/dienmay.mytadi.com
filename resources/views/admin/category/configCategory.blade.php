@extends('admin.layout')
@section('style')
{{Html::style('cms/assets/global/plugins/select2/css/select2.min.css')}}
{{Html::style('cms/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}
{{Html::style('cms/assets/global/plugins/icheck/skins/all.css')}}
{{Html::style('cms/assets/global/plugins/jstree/dist/themes/default/style.min.css')}}
{{Html::style('cms/assets/global/plugins/multiselect/css/style.css')}}
@endsection
@section('pagecontent')
<div class="page-bar m-b-20">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{URL::action('Admin\HomeController@index')}}">Bảng điều khiển</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{URL::action('Admin\CategoryController@listCategory')}}">Chuyên mục</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>{!! $title !!}</span>
        </li>
    </ul>
    <div class="page-toolbar">
        <a href="{{URL::action('Admin\CategoryController@listCategory')}}" class="btn default btn-sm uppercase"><i class="fa fa-arrow-left m-r-5"></i>Quay lại</a>
    </div>
</div>
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject bold uppercase">{{ $title }}</span>
        </div>
        <div class="actions">

        </div>
    </div>
    <div class="portlet-body">
        @if(count($websites) > 0)
        <div class="row">
            <div class="col-md-3">
                <ul class="nav nav-tabs tabs-left">
                    @php($first = true)
                    @foreach($websites as $website)
                    @if($first)
                    <li class="active">
                        @php($first = false)
                        @else
                    <li>
                        @endif
                        <a href="#tab-config" data-toggle="tab" aria-expanded="false" data-id="{{ $website->website_id }}">{!! $website->website_domain !!}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab-config">
                        <div class="form">
                            <div class="form-body p-0">
                                <input type="hidden" name="txt-website-id" value="" />
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">Danh sách chuyên mục</label>
                                            <select name="sl-categories-from[]" id="category" class="form-control" size="20" multiple="multiple">
                                                @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}">{!! $category->category_name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">Chuyên mục trang web</label>
                                            <select name="sl-categories-to[]" id="category_to" class="form-control" size="20" multiple="multiple"></select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue uppercase">Lưu cài đặt</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <h4 class="text-center">Không có dữ liệu</h4>
        @endif
    </div>
</div>
@endsection
@section('script')
{{ Html::script('cms/assets/global/plugins/jstree/dist/jstree.min.js') }}
{{ Html::script('cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
{{ Html::script('cms/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
{{ Html::script('cms/assets/global/plugins/icheck/icheck.min.js') }}
{{ Html::script('cms/assets/global/plugins/select2/js/select2.full.min.js') }}
{{ Html::script('cms/assets/global/plugins/multiselect/dist/js/multiselect.min.js') }}
<script>
    $(document).ready(function ($) {
        $('#category').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control m-b-15" placeholder="Nhập tên chuyên mục" />',
                right: '<input type="text" name="q" class="form-control m-b-15" placeholder="Nhập tên chuyên mục" />',
            },
            fireSearch: function (value) {
                return value.length > 0;
            }
        });
    });
</script>
@endsection
