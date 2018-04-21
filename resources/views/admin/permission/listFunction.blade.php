@extends('admin.layout')
@section('style')
@endsection
@section('pagecontent')
    <div class="page-bar m-b-20">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{URL::action('Admin\HomeController@index')}}">Bảng điều khiển</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <span>{!! $title !!}</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <a href="{{URL::previous()}}" class="btn default btn-sm uppercase"><i class="fa fa-arrow-left m-r-5"></i>Quay lại</a>
        </div>
    </div>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">Danh sách chức năng hệ thống</span>
            </div>
            <div class="actions">
                <button type="button" class="btn blue uppercase" data-toggle="modal" data-target="#add-function-modal">Thêm chức năng</button>
            </div>
        </div>
        <div class="portlet-body">
            @if(count($functions) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover td-middle">
                        <thead>
                        <tr>
                            <th>Tên chức năng</th>
                            <th style="width: 70px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($functions as $func)
                            <tr>
                                <td>{!! $func->function_name!!}</td>
                                <td>
                                    <button type="button" data-id="{{ $func->function_id }}" class="btn green btn-xs btn-loadfunction">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" data-id="{{ $func->function_id }}" class="btn btn-xs red-soft m-r-0 btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                </div>
            @else
                <h4 class="text-center">Không có dữ liệu</h4>
            @endif
        </div>
    </div>
    <div class="modal fade" id="add-function-modal" role="dialog">
        <div class="modal-dialog" style="margin-top:5%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title uppercase">Thêm chức năng hệ thống</h4>
                </div>
                {!! Form::open(['action' => ['Admin\PermissionController@doAddFunction'], 'method' => 'POST', 'id'=> 'add-function-form']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Mã chức năng <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-function-key"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tên chức năng <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-function-name"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn blue uppercase">Thêm mới</button>
                    <button type="button" class="btn red-soft uppercase" data-dismiss="modal">Hủy bỏ</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-function-modal" role="dialog">
        <div class="modal-dialog" style="margin-top:5%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title uppercase"><i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu</h4>
                </div>
                {!! Form::open(['action' => ['Admin\PermissionController@doEditFunction'], 'method' => 'POST', 'id'=> 'edit-function-form']) !!}
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="txt-function-id" />
                    <div class="form-group">
                        <label class="control-label">Mã chức năng <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-function-key" readonly=""/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tên chức năng <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-function-name" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn blue uppercase">Lưu chỉnh sửa</button>
                    <button type="button" class="btn red-soft uppercase" data-dismiss="modal">Hủy bỏ</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="delete-modal" class="modal fade" tabindex="-1" data-keyboard="false">
        <div class="modal-dialog" style="margin-top:5%">

            {!! Form::open(['action' => 'Admin\PermissionController@doDeleteFunction', 'method' => 'POST', 'id' => 'delete-form']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title uppercase">Xóa chức năng hệ thống</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="txt-id" value="">
                    <div class="font-red-soft">Bạn có chắc chắn muốn xóa chức năng hệ thống này?</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn blue text-uppercase">Xác nhận</button>
                    <button type="button" data-dismiss="modal" class="btn red-soft uppercase">Hủy bỏ</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {{ Html::script('assets/global/plugins/select2/js/select2.full.min.js') }}
    {{ Html::script('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
    <script>
        $(document).ready(function () {
            $('#edit-function-modal').on('hidden.bs.modal', function () {
                var modal = $(this);
                modal.find('.modal-title').html('<i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu');
                $("#edit-function-form").trigger('reset');
                modal.find('.form-group').removeClass('has-error');
                modal.find('.form-group').find('.help-block').remove();
                modal.find('.modal-body').hide();
                modal.find('.modal-footer').hide();
            });
            $('.btn-loadfunction').click(function () {
                var function_id = $(this).data('id');
                var modal = $('#edit-function-modal');
                modal.modal('show');
                $.ajax({
                    url: "{{URL::action('Admin\PermissionController@loadFunction')}}",
                    type: "GET",
                    data: {
                        function_id: function_id
                    },
                    dataType: "text",
                    timeout: 30000,
                    error: function (jqXHR, textStatus, errorThrow) {
                        modal.modal('hide');
                        toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                    },
                    success: function (data) {
                        var json_data = $.parseJSON(data);
                        if (json_data.status_code === 200) {
                            modal.find('.modal-title').text(json_data.data.function_name);
                            modal.find('input[name="txt-function-id"]').val(json_data.data.function_id);
                            modal.find('input[name="txt-function-key"]').val(json_data.data.function_key);
                            modal.find('input[name="txt-function-name"]').val(json_data.data.function_name);
                            modal.find('.modal-body').show();
                            modal.find('.modal-footer').show();
                        } else {
                            modal.modal('hide');
                            toastr['error'](json_data.message);
                        }
                    }
                });
            });
            $('.btn-delete').click(function () {
                var id = $.trim($(this).data('id'));
                if (id !== "") {
                    $('#delete-modal').find('input[name="txt-id"]').val(id);
                    $('#delete-modal').modal('show');
                }
            });
            $('#delete-modal').on('hidden.bs.modal', function () {
                $(this).find('#delete-form').trigger('reset');
            });
            var validate_options = {
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    'txt-function-key': {
                        required: true,
                        pattern: /^[a-z0-9_]+$/,
                        minlength: 3
                    },
                    'txt-function-name': {
                        required: true
                    }
                },
                messages: {
                    'txt-function-key': {
                        required: "Mã chức năng không được để trống",
                        pattern: "Mã chức năng không hợp lệ",
                        minlength: "Mã chức năng phải lớn hơn 3 ký tự"
                    },
                    'txt-function-name': {
                        required: "Tên chức năng không được để trống"
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
            };
            $('#add-function-form').validate(validate_options);
            $('#edit-function-form').validate(validate_options);
        });
    </script>
@endsection
