@extends('admin.layout')
@section('style')
{{Html::style('cms/assets/global/plugins/select2/css/select2.min.css')}}
{{Html::style('cms/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}
{{Html::style('cms/assets/global/plugins/icheck/skins/all.css')}}
{{Html::style('cms/assets/global/plugins/jstree/dist/themes/default/style.min.css')}}
{{Html::style('cms/assets/global/plugins/multiselect/css/multi-select.css')}}
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
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Thêm chuyên mục</span>
                </div>
                <div class="actions">

                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::open(['action' => 'Admin\CategoryController@doAddCategory', 'method' => 'POST', 'id' => 'add-category-form']) !!}
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label">Tên <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Đường dẫn tĩnh <span class="required"> * </span></label>
                        <input type="text" class="form-control" name="txt-slug">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tiêu đề</label>
                        <input type="text" class="form-control" name="txt-title"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mô tả</label>
                        <textarea name="txt-desc" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Kiểu chuyên mục</label>
                        <div class="input-group">
                            <div class="icheck-inline">
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status-type" class="icheck" value="1"
                                           data-radio="iradio_minimal-green" checked/>
                                    <span class="text-success">Tin tức</span>
                                </label>
                                <label class="control-label" role="button">
                                    <input type="radio" name="rd-status-type" class="icheck" value="0"
                                           data-radio="iradio_minimal-green"/>
                                    <span class="text-warning">Sản phẩm</span>
                                </label>
                            </div>
                        </div>
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
                <div class="form-actions">
                    <button type="submit" class="btn blue uppercase">Thêm mới</button>
                    <button type="reset" class="btn red-soft uppercase">Hủy bỏ</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase">Danh sách chuyên mục</span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                @if(count($categories) > 0)
                <div id="categories-tree">
                    @include('admin.category.tree', ['categories' => $categories])
                </div>
                @else
                <h4 class="text-center">Không có dữ liệu</h4>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="edit-category-modal" class="modal fade" tabindex="-1" data-keyboard="false">
    <div class="modal-dialog"  style="margin-top: 5%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title text-uppercase"><i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu</h4>
            </div>
            {!! Form::open(['action' => ['Admin\CategoryController@doEditCategory'], 'method' => 'POST', 'id'=> 'edit-category-form']) !!}
            <div class="modal-body" style="display: none">
                <input type="hidden" class="form-control" name="txt-category-id"/>
                <div class="form-group">
                    <label class="control-label">Tên <span class="required"> * </span></label>
                    <input type="text" class="form-control" name="txt-name"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Đường dẫn tĩnh <span class="required"> * </span></label>
                    <input type="text" class="form-control" name="txt-slug"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Tiêu đề</label>
                    <input type="text" class="form-control" name="txt-title"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Mô tả</label>
                    <textarea name="txt-desc" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Kiểu chuyên mục</label>
                    <div class="input-group">
                        <div class="icheck-inline">
                            <label class="control-label" role="button">
                                <input type="radio" name="rd-status-type" class="icheck" value="1"
                                       data-radio="iradio_minimal-green" checked/>
                                <span class="text-success">Tin tức</span>
                            </label>
                            <label class="control-label" role="button">
                                <input type="radio" name="rd-status-type" class="icheck" value="0"
                                       data-radio="iradio_minimal-green"/>
                                <span class="text-warning">Sản phẩm</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Trạng thái</label>
                    <div class="input-group">
                        <div class="icheck-inline">
                            <label class="control-label" role="button">
                                <input type="radio" name="rd-status" class="icheck" value="1" data-radio="iradio_minimal-green"/>
                                <span class="text-primary">Hoạt động</span>
                            </label>
                            <label class="control-label" role="button">
                                <input type="radio" name="rd-status" class="icheck" value="0" data-radio="iradio_minimal-green"/>
                                <span class="text-warning">Chờ duyệt</span>
                            </label>
                            <label class="control-label" role="button">
                                <input type="radio" name="rd-status" class="icheck" value="-1" data-radio="iradio_minimal-green"/>
                                <span class="text-danger">Bị khóa</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: none">
                <button type="button" id="btn-delete" class="btn red-soft uppercase pull-left"><i class="fa fa-trash m-r-5"></i>Xóa</button>
                <button type="submit" id="btn-update" class="btn blue uppercase">Lưu chỉnh sửa</button>
                <button type="button" class="btn default uppercase" data-dismiss="modal">Hủy bỏ</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div id="delete-modal" class="modal fade" tabindex="-1" data-keyboard="false">
    <div class="modal-dialog"  style="margin-top: 5%">
        {!! Form::open(['action' => 'Admin\CategoryController@doDeleteCategory' , 'method' => 'POST', 'id' => 'delete-form']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"></button>
                <h4 class="modal-title uppercase">Xóa chuyên mục</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="txt-id" value="">
                <div class="font-red-soft">Bạn có chắc chắn muốn xóa chuyên mục này?</div>
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
{{ Html::script('cms/assets/global/plugins/jstree/dist/jstree.min.js') }}
{{ Html::script('cms/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
{{ Html::script('cms/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
{{ Html::script('cms/assets/global/plugins/icheck/icheck.min.js') }}
{{ Html::script('cms/assets/global/plugins/select2/js/select2.full.min.js') }}
{{ Html::script('cms/assets/global/plugins/multiselect/js/jquery.quicksearch.js') }}
{{ Html::script('cms/assets/global/plugins/multiselect/js/jquery.multi-select.js') }}
<script>
    $(document).ready(function () {
        $('#add-category-form').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                'txt-name': {
                    required: true
                },
                'txt-slug': {
                    required: true,
                    pattern: /^[a-z0-9\-]+$/
                },
                'sl-parent': {
                    number: true
                }
            },
            messages: {
                'txt-name': {
                    required: "Tên chuyên mục không được để trống"
                },
                'txt-slug': {
                    required: "Đường dẫn tĩnh không được để trống",
                    pattern: "Đường dẫn tĩnh không hợp lệ"
                },
                'sl-parent': {
                    number: "Chuyên mục cha không hợp lệ"
                }
            },
            invalidHandler: function (event, validator) {
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        $('#add-category-form').find('input[class!="tt-input"]').keypress(function (e) {
            if (e.which === 13) {
                if ($(this).validate().form()) {
                    $(this).submit();
                }
                return false;
            }
        });
        $('#config-category-form').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                'sl-website': {
                    required: true,
                    number: true
                }
            },
            messages: {
                'sl-website': {
                    required: "Chưa chọn trang web",
                    number: "Trang web không hợp lệ"
                }
            },
            invalidHandler: function (event, validator) {
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        $('#edit-category-form').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                'txt-name': {
                    required: true
                },
                'txt-slug': {
                    required: true,
                    pattern: /^[a-z0-9\-]+$/
                }
            },
            messages: {
                'txt-name': {
                    required: "Tên chuyên mục không được để trống"
                },
                'txt-slug': {
                    required: "Đường dẫn tĩnh không được để trống",
                    pattern: "Đường dẫn tĩnh không hợp lệ"
                }
            },
            invalidHandler: function (event, validator) {
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                form_data = new FormData(form);
                $.ajax({
                    url: "{{ URL::action('Admin\CategoryController@doEditCategory') }}",
                    type: "POST",
                    data: form_data,
                    dataType: "text",
                    timeout: 30000,
                    contentType: false,
                    processData: false,
                    error: function (jqXHR, textStatus, errorThrow) {
                        toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                    },
                    success: function (data) {
                        var json_data = $.parseJSON(data);
                        if (json_data.status_code === 200) {
                            toastr['success'](json_data.message);
                            var node_id = $('#categories-tree').find('a[data-id=' + json_data.data.category_id + ']').closest('li').attr('id');
                            var node = $('#categories-tree').jstree(true).get_node(node_id);
                            $('#categories-tree').jstree('rename_node', node, json_data.data.category_name);
                            $('#edit-category-modal').modal('hide');
                        } else {
                            toastr['error'](json_data.message);
                        }
                    }
                });
            }
        });
        $('#categories-tree').jstree({
            core: {
                themes: {
                    responsive: false,
                    variant: "large"
                },
                "check_callback": true
            },
            types: {
                default: {
                    icon: "fa fa-folder icon-state-warning icon-lg"
                },
                file: {
                    icon: "fa fa-file icon-state-warning icon-lg"
                }
            },
            plugins: ["dnd", "types"]
        });
        $('#categories-tree').on('move_node.jstree', function (e, data) {
            var category_id = data.node.a_attr['data-id'];
            var parent_id = 0;
            if (data.node.parent !== "#") {
                parent_id = $('#' + data.node.parent).find('.jstree-anchor').data('id');
            }
            $.ajax({
                url: "{{ URL::action('Admin\CategoryController@doChangeParent') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: category_id,
                    category_parent: parent_id
                },
                dataType: "text",
                timeout: 30000,
                error: function (jqXHR, textStatus, errorThrow) {
                    toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                },
                success: function (data) {
                    var json_data = $.parseJSON(data);
                    if (json_data.status_code === 200) {
                        toastr['success'](json_data.message);
                    } else {
                        toastr['error'](json_data.message);
                    }
                }
            });
        });
        $('#add-category-form').find('select[name="sl-parent"]').select2({
            language: {
                noResults: function () {
                    return "Không tìm thấy chuyên mục nào";
                }
            }
        }).on('change', function () {
            if ($.trim($(this).val()) === "") {
                var parent = $(this).closest('.form-group');
                parent.removeClass('has-error');
                parent.find('.help-block').remove();
            }
        });
        $('#config-category-form').find('select[name="sl-website"]').select2({
            language: {
                noResults: function () {
                    return "Không tìm thấy trang web nào";
                }
            }
        }).on('change', function () {
            var website_id = $.trim($(this).val());
            var form = $('#config-category-form');
            form.find('select[name="sl-categories[]"]').multiSelect('deselect_all');
            if (website_id !== "") {
                form.find('.loading').show();
                form.find('.config-form').hide();
                $.ajax({
                    url: "{{ URL::action('Admin\CategoryController@loadConfigCategory') }}",
                    type: "GET",
                    data: {
                        website_id: website_id
                    },
                    dataType: "text",
                    timeout: 30000,
                    error: function (jqXHR, textStatus, errorThrow) {
                        toastr['error']('Lỗi trong quá trình xử lý dữ liệu');
                    },
                    success: function (data) {
                        var json_data = $.parseJSON(data);
                        if (json_data.status_code === 200) {
                            var selected = [];
                            $.each(json_data.data, function (key, category) {
                                selected.push(category.category_id + '');
                            });
                            form.find('select[name="sl-categories[]"]').multiSelect('select', selected);
                            form.find('.loading').hide();
                            form.find('.config-form').show();
                        } else {
                            toastr['error'](json_data.message);
                        }
                    }
                });
                var parent = $(this).closest('.form-group');
                parent.removeClass('has-error');
                parent.find('.help-block').remove();
            }
        });
        $('#add-category-form').find('input[name="txt-name"]').focusout(function () {
            var value = $.trim($(this).val())
            if (value !== "") {
                var slug = $('#add-category-form').find('input[name="txt-slug"]');
                $.ajax({
                    url: "{{ URL::action('Admin\HomeController@slug') }}",
                    type: "GET",
                    data: {
                        slug: value
                    },
                    dataType: "text",
                    timeout: 30000,
                    error: function (jqXHR, textStatus, errorThrow) {
                        slug.val("");
                    },
                    success: function (data) {
                        var json_data = $.parseJSON(data);
                        if (json_data.status_code === 200) {
                            slug.val(json_data.data);
                        } else {
                            slug.val("");
                        }
                    }
                });
            }
        });
        $('#add-category-form').find('input[name="txt-slug"]').focusout(function () {
            var value = $.trim($(this).val());
            if (value !== "") {
                var slug = $('#add-category-form').find('input[name="txt-slug"]');
                $.ajax({
                    url: "{{ URL::action('Admin\HomeController@slug') }}",
                    type: "GET",
                    data: {
                        slug: value
                    },
                    dataType: "text",
                    timeout: 30000,
                    error: function (jqXHR, textStatus, errorThrow) {
                        slug.val("");
                    },
                    success: function (data) {
                        var json_data = $.parseJSON(data);
                        if (json_data.status_code === 200) {
                            slug.val(json_data.data);
                        } else {
                            slug.val("");
                        }
                    }

                });
            }
        });
        $('#edit-category-modal').on('hidden.bs.modal', function () {
            var modal = $('#edit-category-modal');
            modal.find('.modal-title').html('<i class="fa fa-circle-o-notch fa-spin"></i> Đang xử lý dữ liệu');
            $("#edit-category-form").trigger('reset');
            modal.find('input[name="rd-status"]').iCheck('uncheck');
            modal.find('input[name="rd-status-type"]').iCheck('uncheck');
            modal.find('.form-group').removeClass('has-error');
            modal.find('.form-group').find('.help-block').remove();
            modal.find('.modal-body').hide();
            modal.find('.modal-footer').hide();

        });
        
        $('#btn-delete').click(function () {
            console.log('sdf');
            var id = $.trim($(this).data('id'));
            if (id !== "") {
                $('#delete-modal').find('input[name="txt-id"]').val(id);
                $('#delete-modal').modal('show');
            }
        });
        $('#delete-modal').on('hidden.bs.modal', function () {
            $(this).find('#delete-form').trigger('reset');
        });
        $('#config-category-form').find('select[name="sl-categories[]"]').multiSelect({
            selectableHeader: '<input type="text" class="form-control search-input" placeholder="Tìm chuyên mục">',
            selectionHeader: '<input type="text" class="form-control search-input" placeholder="Tìm chuyên mục">',
            afterInit: function (ms) {
                var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function (e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function (e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    });
    function loadCategory(element) {
        var category_id = $(element).data('id');
        var modal = $('#edit-category-modal');
        modal.modal('show');
        $.ajax({
            url: "{{URL::action('Admin\CategoryController@loadCategory')}}",
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
                    modal.find('.modal-title').text(json_data.data.category_name);
                    modal.find('#btn-delete').data('id', json_data.data.category_id);
                    modal.find('input[name="txt-category-id"]').val(json_data.data.category_id);
                    modal.find('input[name="txt-name"]').val(json_data.data.category_name);
                    modal.find('input[name="txt-slug"]').val(json_data.data.category_slug);
                    modal.find('input[name="txt-title"]').val(json_data.data.category_meta_title);
                    modal.find('textarea[name="txt-desc"]').val(json_data.data.category_meta_desc);
                    modal.find('input[name="rd-status"][value="' + json_data.data.category_status + '"]').iCheck('check');
                    modal.find('input[name="rd-status-type"][value="' + json_data.data.category_type + '"]').iCheck('check');
                    modal.find('.modal-body').show();
                    modal.find('.modal-footer').show();
                } else {
                    modal.modal('hide');
                    toastr['error'](json_data.message);
                }
            }
        });
    }
</script>
@endsection
