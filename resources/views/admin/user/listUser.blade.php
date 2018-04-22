@extends('admin.layout')
@section('style')
    {{Html::style('cms/assets/global/plugins/select2/css/select2.min.css')}}
    {{Html::style('cms/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}
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
                <span>{!! $title !!}</span>
            </li>
        </ul>
        <div class="page-toolbar">
            <a href="{{URL::previous()}}" class="btn default btn-sm uppercase"><i class="fa fa-arrow-left m-r-5"></i>Quay
                lại</a>
        </div>
    </div>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">Danh sách thành viên</span>
            </div>
            <div class="actions">
                <button type="button" class="btn blue btn-lg uppercase" data-toggle="modal" data-target="#add-user-modal">Thêm thành viên</button>
            </div>
        </div>
        <div class="portlet-body">
            @if(count($users) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover td-middle">
                        <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ và tên</th>
                            <th>Nhóm</th>
                            <th style="width: 100px;">Trạng thái</th>
                            <th style="width: 70px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{!! $user->user_name !!}</td>
                                <td>{!! $user->user_fullname !!}</td>
                                <td>{!! $user->group_name !!}</td>
                                <td class="font-13">
                                    @if($user->user_status == 1)
                                        <span class="text-success">Hoạt động</span>
                                    @elseif($user->user_status == 0)
                                        <span class="text-warning">Chờ duyệt</span>
                                    @else
                                        <span class="text-danger">Bị khóa</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" data-id="{{ $user->user_id }}" class="btn green btn-xs btn-loaduser">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" data-id="{{ $user->user_id }}" class="btn btn-xs red-soft m-r-0 btn-delete">
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
    <div class="modal fade" id="add-user-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title uppercase">Thêm thành viên</h4>
                </div>
                {!! Form::open(['action' => ['Admin\UserController@doAddUser'], 'method' => 'POST', 'id'=> 'add-user-form']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Họ và tên <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-fullname" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tên đăng nhập <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-name" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mật khẩu <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-password" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nhóm<span class="required"> * </span></label>
                        <select class="form-control" name="sl-group">
                            <option value="">Chọn nhóm thành viên</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->group_id }}">{!! $group->group_name !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Trạng thái</label>
                        <div class="input-group">
                            <div class="icheck-inline">
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="1"
                                           data-radio="iradio_minimal-green" checked/>
                                    <span class="text-success">Hoạt động</span>
                                </label>
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="0"
                                           data-radio="iradio_minimal-green"/>
                                    <span class="text-warning">Chờ duyệt</span>
                                </label>
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="-1"
                                           data-radio="iradio_minimal-green"/>
                                    <span class="text-danger">Bị khóa</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn blue uppercase">Thêm mới</button>
                    <button type="button" class="btn red-soft uppercase" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal fade" id="edit-user-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu</h4>
                </div>
                {!! Form::open(['action' => ['Admin\UserController@doEditUser'], 'method' => 'POST', 'id'=> 'edit-user-form']) !!}
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="txt-user-id" />
                    <div class="form-group">
                        <label class="control-label">Họ và tên <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-fullname" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tên đăng nhập <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-name" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mật khẩu</label>
                        <input type="text" class="form-control" name="txt-password" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nhóm <span class="required"> * </span></label>
                        <select class="form-control" name="sl-group">
                            <option value="">Chọn nhóm thành viên</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->group_id }}">{!! $group->group_name !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Trạng thái</label>
                        <div class="input-group">
                            <div class="icheck-inline">
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="1" data-radio="iradio_minimal-green" />
                                    <span class="text-primary">Hoạt động</span>
                                </label>
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="0" data-radio="iradio_minimal-green" />
                                    <span class="text-warning">Chờ duyệt</span>
                                </label>
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status" class="icheck" value="-1" data-radio="iradio_minimal-green" />
                                    <span class="text-danger">Bị khóa</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-update" class="btn blue uppercase">Lưu chỉnh sửa</button>
                    <button type="button" class="btn red-soft uppercase" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div id="delete-modal" class="modal fade" tabindex="-1" data-keyboard="false">
        <div class="modal-dialog"  style="margin-top: 5%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title uppercase">Xóa thành viên</h4>
                </div>
                {!! Form::open(['action' => 'Admin\UserController@doDeleteUser', 'method' => 'POST', 'id' => 'delete-form']) !!}
                <div class="modal-body">
                    <input type="hidden" name="txt-id" value="" />
                    <div class="font-red-soft">Bạn có chắc chắn muốn xóa thành viên này?</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn blue text-uppercase">Xác nhận</button>
                    <button type="button" data-dismiss="modal" class="btn red-soft uppercase">Hủy bỏ</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{ Html::script('cms/assets/global/plugins/icheck/icheck.min.js') }}
    {{ Html::script('cms/assets/global/plugins/select2/js/select2.full.min.js') }}
    {{ Html::script('cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
    <script>
        $(document).ready(function () {
            $('#add-user-modal, #edit-user-modal').on('shown.bs.modal', function () {
                var modal = $(this);
                modal.find('select[name="sl-group"]').select2({
                    language: {
                        noResults: function () {
                            return "Không tìm thấy nhóm nào";
                        }
                    }
                }).on('change', function () {
                    if ($.trim($(this).val()) === "") {
                        var parent = $(this).closest('.form-group');
                        parent.removeClass('has-error');
                        parent.find('.help-block').remove();
                    }
                });
            });
            $('#edit-user-modal').on('hidden.bs.modal', function () {
                var modal = $(this);
                modal.find('.modal-title').html('<i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu');
                $("#edit-user-form").trigger('reset');
                modal.find('input[name="rd-status"]').iCheck('uncheck');
                modal.find('.form-group').removeClass('has-error');
                modal.find('.form-group').find('.help-block').remove();
                modal.find('.modal-body').hide();
                modal.find('.modal-footer').hide();
            });
            $('#add-user-modal').on('hidden.bs.modal', function () {
                var modal = $(this);
                $("#add-user-form").trigger('reset');
                modal.find('.form-group').removeClass('has-error');
                modal.find('.form-group').find('.help-block').remove();
            });
            $('.btn-loaduser').click(function () {
                var user_id = $(this).data('id');
                var modal = $('#edit-user-modal');
                modal.modal('show');
                $.ajax({
                    url: "{{URL::action('Admin\UserController@loadUser')}}",
                    type: "GET",
                    data: {
                        user_id: user_id
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
                            modal.find('.modal-title').text(json_data.data.user_name);
                            modal.find('input[name="txt-user-id"]').val(json_data.data.user_id);
                            modal.find('input[name="txt-name"]').val(json_data.data.user_name);
                            modal.find('input[name="txt-fullname"]').val(json_data.data.user_fullname);
                            modal.find('select[name="sl-group"]').val(json_data.data.user_group_id).trigger('change');
                            modal.find('input[name="rd-status"][value="' + json_data.data.user_status + '"]').iCheck('check');
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
            $('#add-user-form').validate({
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    'txt-name': {
                        required: true,
                        pattern: /^[a-z0-9_]+$/,
                        minlength: 3
                    },
                    'txt-password': {
                        required: true,
                        minlength: 3
                    },
                    'txt-fullname': {
                        required: true
                    },
                    'sl-group': {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    'txt-name': {
                        required: "Tên đăng nhập không được để trống",
                        pattern: "Tên đăng nhập không hợp lệ",
                        minlength: "Tên đăng nhập phải lớn hơn 3 ký tự"
                    },
                    'txt-password': {
                        required: "Mật khẩu không được để trống",
                        minlength: "Mật khẩu phải lớn hơn 3 ký tự"
                    },
                    'txt-fullname': {
                        required: "Họ và tên không được để trống"
                    },
                    'sl-group': {
                        required: "Chưa chọn nhóm thành viên",
                        number: "Nhóm thành viên không hợp lệ"
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
            $('#edit-user-form').validate({
                errorElement: 'span',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    'txt-name': {
                        required: true,
                        pattern: /^[a-z0-9_]+$/,
                        minlength: 3
                    },
                    'txt-fullname': {
                        required: true
                    },
                    'sl-group': {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    'txt-name': {
                        required: "Tên đăng nhập không được để trống",
                        pattern: "Tên đăng nhập không hợp lệ",
                        minlength: "Tên đăng nhập phải lớn hơn 3 ký tự"
                    },
                    'txt-fullname': {
                        required: "Họ và tên không được để trống"
                    },
                    'sl-group': {
                        required: "Chưa chọn nhóm thành viên",
                        number: "Nhóm thành viên không hợp lệ"
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