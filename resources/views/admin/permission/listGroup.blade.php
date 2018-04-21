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
                <span class="caption-subject uppercase">Danh sách nhóm thành viên</span>
            </div>
            <div class="actions">
                <a href = "{{URL::action('Admin\PermissionController@addGroup')}}" class="btn blue uppercase">Thêm nhóm</a>
            </div>
        </div>
        <div class="portlet-body">
            @if(count($groups) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover td-middle">
                        <thead>
                        <tr>
                            <th>Tên nhóm</th>
                            <th style="width: 70px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $group)
                            <tr>
                                <td>{!!$group->group_name!!}</td>
                                <td>
                                    <a href="{{URL::action('Admin\PermissionController@editGroup', ['group_id' => $group->group_id])}}" class="btn green btn-xs"><i class="fa fa-pencil"></i></a>
                                    <button type="button" data-id="{{ $group->group_id }}" class="btn btn-xs red-soft m-r-0 btn-delete" >
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
    <div id="delete-modal" class="modal fade" tabindex="-1" data-keyboard="false">
        <div class="modal-dialog"  style="margin-top: 5%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title uppercase">Xóa nhóm thành viên</h4>
                </div>
                {!! Form::open(['action' => 'Admin\PermissionController@doDeleteGroup', 'method' => 'POST', 'id' => 'delete-form']) !!}
                <div class="modal-body">
                    <input type="hidden" name="txt-id" value="" />
                    <div class="font-red-soft">Bạn có chắc chắn muốn xóa nhóm thành viên này?</div>
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
    <script>
        $(document).ready(function () {
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
        });

    </script>
@endsection
