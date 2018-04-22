@extends('admin.layout') 
@section('style')
{{Html::style('assets/global/plugins/icheck/skins/all.css')}} 
@endsection 
@section('pagecontent')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption uppercase">
            <span class="caption-subject uppercase">Tất cả chuyên mục tin tức</span>
        </div>
        <div class="actions">
            <button type="button" class="btn blue btn-lg uppercase" data-toggle="modal" data-target="#add-category-modal">Thêm chuyên mục</button>
        </div>
    </div>
    <div class="portlet-body">
        @if(count($categories) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover td-middle">
                <thead>
                    <tr>
                        <th>Tên chuyên mục</th>
                        <th>Tiêu đề SEO</th>
                       
                        <th style="width: 70px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{!! $category->category_name !!}</td>
                        <td>{!! $category->category_meta_title !!}</td>
                        
                        <td>
                            <button type="button" data-id="{{ $category->category_id }}" class="btn green btn-xs btn-loadcategory" disabled>
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" data-id="{{ $category->category_id }}" class="btn btn-xs red-soft m-r-0 btn-delete" disabled>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right">
            {!!  $categories->appends(Request::all())->links() !!}
        </div>
        @else
        <h4 class="text-center">Không có dữ liệu</h4> 
        @endif
    </div>
</div>
<div class="modal fade" id="add-category-modal" role="dialog">
    <div class="modal-dialog" style="margin-top: 5%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title uppercase">Thêm chuyên mục tin tức</h4>
            </div>
            {!! Form::open(['action' => 'Admin\ArticleController@doAddCategory', 'method' => 'POST', 'id'=> 'add-category-form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Tên chuyên mục <span class="required"> * </span></label>
                    <input type="text" class="form-control" name="txt-name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Tiêu đề SEO</label>
                    <input type="text" class="form-control" name="txt-meta-title" />
                </div>
                <div class="form-group">
                    <label class="control-label">Mô tả SEO</label>
                    <textarea class="form-control" name="txt-meta-desc" rows="3"></textarea>
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
<div class="modal fade" id="edit-category-modal" role="dialog">
    <div class="modal-dialog" style="margin-top: 5%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title uppercase"><i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu</h4>
            </div>
            {!! Form::open(['action' => 'Admin\ArticleController@doEditCategory', 'method' => 'POST', 'id'=> 'edit-category-form']) !!}
            <div class="modal-body">
                <input type="hidden" class="form-control" name="txt-id" />
                <div class="form-group">
                    <label class="control-label">Tên chuyên mục <span class="required"> * </span></label>
                    <input type="text" class="form-control" name="txt-name" id="txt-name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Tiêu đề SEO</label>
                    <input type="text" class="form-control" name="txt-meta-title" />
                </div>
                <div class="form-group">
                    <label class="control-label">Mô tả SEO</label>
                    <textarea class="form-control" name="txt-meta-desc" rows="3"></textarea>
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
                <h4 class="modal-title uppercase">Xóa chuyên mục tin tức</h4>
            </div>
            {!! Form::open(['action' => 'Admin\ArticleController@doDeleteCategory', 'method' => 'POST', 'id' => 'delete-form']) !!}
            <div class="modal-body">
                <input type="hidden" name="txt-id" value="" />
                <div class="font-red-soft">Bạn có chắc chắn muốn xóa chuyên mục tin tức này?</div>
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
{{ Html::script('assets/global/plugins/icheck/icheck.min.js') }} 
{{Html::script('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}
{{Html::script('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}
<script>
    $(document).ready(function () {
        $('#edit-category-modal').on('hidden.bs.modal', function () {
            var modal = $(this);
            modal.find('.modal-title').html('<i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu');
            $("#edit-category-form").trigger('reset');
            modal.find('.form-group').removeClass('has-error');
            modal.find('.form-group').find('.help-block').remove();
            modal.find('.modal-body').hide();
            modal.find('.modal-footer').hide();
        });
        $('#add-category-modal').on('hidden.bs.modal', function () {
            var modal = $(this);
            $("#add-category-form").trigger('reset');
            modal.find('.form-group').removeClass('has-error');
            modal.find('.form-group').find('.help-block').remove();
        });
        $('.btn-loadcategory').click(function () {
            var category_id = $(this).data('id');
            var modal = $('#edit-category-modal');
            modal.modal('show');
            $.ajax({
                url: "{{URL::action('Admin\ArticleController@loadCategory')}}",
                type: "GET",
                data: {
                    category_id: category_id
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
                        modal.find('.modal-title').text("Chuyên mục: " + json_data.data.category_name);
                        modal.find('input[name="txt-id"]').val(json_data.data.category_id);
                        modal.find('input[name="txt-name"]').val(json_data.data.category_name);
                        modal.find('input[name="txt-meta-title"]').val(json_data.data.category_meta_title);
                        modal.find('textarea[name="txt-meta-desc"]').val(json_data.data.category_meta_desc);
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
        $('#add-category-form').validate({
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
                    required: "Tên chuyên mục không được để trống"
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
        $('#edit-category-form').validate({
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
                    required: "Tên chuyên mục không được để trống"
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