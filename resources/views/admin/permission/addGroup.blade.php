@extends('admin.layout')
@section('style')
    {{Html::style('cms/assets/global/plugins/icheck/skins/all.css')}}
@endsection
@section('pagecontent')
    <div class="page-bar m-b-20">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{URL::action('Admin\HomeController@index')}}">Bảng điều khiển</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{URL::action('Admin\PermissionController@listGroup')}}">Nhóm thành viên</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <span>{!! $title !!}</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <a href="{{URL::action('Admin\PermissionController@listGroup')}}" class="btn default btn-sm uppercase"><i class="fa fa-arrow-left m-r-5"></i>Quay lại</a>
        </div>
    </div>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">{!! $title !!}</span>
            </div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['action' => 'Admin\PermissionController@doAddGroup', 'method' => 'POST', 'id' => 'group-form']) !!}
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label">Tên nhóm<span class="required"> * </span></label>
                    <input type="text" class="form-control" name="txt-name">
                </div>
                <div class="form-group">
                    <label class="control-label">Phân quyền</label>
                    <div class="row m-t-15">
                        @foreach($functions as $func)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group group-permission">
                                        <div class="icheck-list">
                                            <label role="button">
                                                <input type="checkbox" value="{{ $func->function_key }}" name="cb-functions[]" class="icheck cb-function" data-checkbox="icheckbox_square-blue"> <span class="font-bold font-13">{!! $func->function_name !!}</span>
                                            </label>
                                        </div>
                                        <div class="form-group m-l-25 m-t-15">
                                            <div class="input-group">
                                                <div class="icheck-list">
                                                    <label role="button">
                                                        <input type="checkbox" value="view" name="cb-{{ $func->function_key }}-actions[]" class="icheck cb-action" data-checkbox="icheckbox_minimal-blue"> Xem
                                                    </label>
                                                    <label role="button">
                                                        <input type="checkbox" value="add" name="cb-{{ $func->function_key }}-actions[]"
                                                               class="icheck cb-action" data-checkbox="icheckbox_minimal-blue"> Thêm
                                                    </label>
                                                    <label role="button">
                                                        <input type="checkbox" value="edit" name="cb-{{ $func->function_key }}-actions[]" class="icheck cb-action" data-checkbox="icheckbox_minimal-blue"> Sửa
                                                    </label>
                                                    <label role="button">
                                                        <input type="checkbox" value="delete" name="cb-{{ $func->function_key }}-actions[]" class="icheck cb-action" data-checkbox="icheckbox_minimal-blue"> Xóa
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-actions" style="clear: both;">
                <button type="submit" class="btn blue uppercase">Thêm mới</button>
                <a href="{{URL::previous()}}" data-dismiss="modal" class="btn red-soft uppercase">Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {{ Html::script('cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
    {{ Html::script('cms/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
    {{ Html::script('cms/assets/global/plugins/icheck/icheck.min.js') }}
    <script>
        $(document).ready(function(){
            $('#group-form').find('.group-permission').find('.cb-function').on('ifClicked', function () {
                if (!$(this).prop('checked')) {
                    $(this).closest('.group-permission').find('.cb-action').iCheck('check');
                } else {
                    $(this).closest('.group-permission').find('.cb-action').iCheck('uncheck');
                }
            });
            $('#group-form').find('.group-permission').find('.cb-action').on('ifClicked', function () {
                if (!$(this).prop('checked')) {
                    $(this).closest('.group-permission').find('.cb-function').iCheck('check');
                } else {
                    if ($(this).closest('.group-permission').find('.cb-action:checked').length === 1) {
                        $(this).closest('.group-permission').find('.cb-function').iCheck('uncheck');
                    }
                }
            });
            $('#group-form').validate({
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    'txt-name': {
                        required: true
                    }
                },
                messages: {
                    'txt-name': {
                        required: "Tên nhóm không được để trống"
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
        });
    </script>
@endsection