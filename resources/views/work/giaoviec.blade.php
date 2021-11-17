
@extends('admin.index')
@section('title')
   Giao việc
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>GIAO VIỆC</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Công việc</li>
                            <li class="breadcrumb-item active">Giao việc</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            @if(session('succ'))
                <div class="alert alert-success">
                    {{session('succ')}}
                </div>
            @endif
            @if(session('err'))
                <div class="alert alert-warning">
                    {{session('err')}}
                </div>
            @endif
                 <div class="card card-info">
                        <div class="card-header">
                            <h5>GIAO VIỆC</h5>
                        </div>
                        <div class="card-body">
                            <button id="addWorkBtn" type="button" data-toggle="modal" data-target="#addWork" class="btn btn-success">Thêm</button><br><br>
                            <table id="dataTable" class="display" style="width:100%">
                                <thead>
                                <tr class="bg-gray">
                                    <th>STT</th>
                                    <th>Báo cáo</th>
                                    <th>Ngày</th>
                                    <th>Giao cho</th>
                                    <th>Tên công việc</th>
                                    <th>Yêu cầu</th>
                                    <th>Tiến độ</th>
                                    <th>Deadline</th>
                                    <th>Phản hồi</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- Medal Add Work-->
                    <div class="modal fade" id="addWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">THÊM CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="addWorkForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Giao cho</label>
                                                    <select name="giaoCho" class="form-control">
                                                        @foreach($user as $row)
                                                            @if($row->hasRole('work'))
                                                                <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tên công việc</label>
                                                    <input name="tenCongViec" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày bắt đầu</label>
                                                    <input name="ngayStart" min="<?php echo Date('Y-m-d');?>" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày kết thúc</label>
                                                    <input name="ngayEnd" min="<?php echo Date('Y-m-d');?>" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Yêu cầu công việc</label>
                                                    <input name="yeuCau" placeholder="Yêu cầu công việc" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnAddWork" class="btn btn-primary" form="addWorkForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                     <!-- Medal Edit Work-->
                    <div class="modal fade" id="editWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">SỬA CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="editWorkForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <div class="card-body">
                                                <input type="hidden" name="_id">
                                                 <div class="form-group">
                                                    <label>Người nhận việc</label>
                                                    <input name="_nguoiNhan" readonly="readonly" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tên công việc</label>
                                                    <input name="_tenCongViec" type="text" class="form-control">
                                                </div>
                                                 <div class="form-group">
                                                    <label>Ngày bắt đầu</label>
                                                    <input name="_ngayStart" min="<?php echo Date('Y-m-d');?>" type="date" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Ngày kết thúc</label>
                                                    <input name="_ngayEnd" min="<?php echo Date('Y-m-d');?>" type="date" class="form-control">
                                                </div>
                                               <div class="form-group">
                                                    <label>Yêu cầu công việc</label>
                                                    <input name="_yeuCau" placeholder="Yêu cầu công việc" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    <button id="btnEditWork" class="btn btn-primary" form="addWorkForm">Lưu</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Medal SHOW Work-->
                    <div class="modal fade" id="dataShowResult">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">PHẢN HỒI CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="p-4">
                                           <h5>Phản hồi</h5>
                                            <p id="lyDo"></p>
                                            <h5>GHI CHÚ</h5>
                                            <p id="lyDoGhiChu"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Medal Reponse Work-->
                    <div class="modal fade" id="approveWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">XÁC NHẬN KẾT QUẢ</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="responseForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <div class="card-body">
                                                <input type="hidden" name="_idPhanHoi">
                                                <div class="form-group">
                                                    <label>Phản hồi (nếu có)</label>
                                                    <input name="phanHoi" placeholder="Phản hồi từ người giao việc (nếu có)" type="text" class="form-control">
                                                </div>
                                                <button id="approve" class="btn btn-primary">Đạt yêu cầu</button>
                                             <button id="noapprove" class="btn btn-warning">Chưa đạt yêu cầu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
            </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
     <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Below is plugin for datatables -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        function revertDate(str) {
            let myArr = str.split("-");
            return myArr[2] + "-" + myArr[1] + "-" + myArr[0];
        }

        $(document).ready(function() {
            //Display data onload
            var table = $('#dataTable').DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/work/pushwork/list') }}",
                "columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    },{
                        "targets": 1,
                        "className": "text-center",
                    },{
                        "targets": 6,
                        "className": "text-center",
                    }
                ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.isReportPush == true)
                                return "<input id='_check' type='checkbox' checked='checked' data-id="+row.id+">";
                            else
                                return "<input id='_check' type='checkbox' data-id="+row.id+">";
                        }
                    },
                    { "data": "ngayTao"},
                    { "data": "surname"},
                    { "data": "tenCongViec"},
                    { "data": "requestWork"},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tienDo < 100)
                                return "<span class='text-danger'><strong>"+row.tienDo+"%</strong></span>";
                            else
                                return "<span class='text-info'><strong>"+row.tienDo+"%</strong></span>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            let _stt = "";
                            if((new Date() > new Date(row.ngayEnd)) && row.tienDo < 100 && row.acceptApply == false)
                            {
                                _stt = "(Trể)"
                            }
                            return revertDate(row.ngayStart)+"<br/>"+revertDate(row.ngayEnd)+"<br/> <strong>"+_stt+"</strong>";
                        }
                    },
                    {
                      "data": null,
                        render: function(data, type, row) {
                                return "<button id='viewMore' data-id='"+row.id+"' data-toggle='modal' data-target='#dataShowResult' id='showResult' class='btn btn-info btn-sm'>Xem</button>";
                       }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.apply == null)
                               return "<span class='text-warning'><strong>Chưa nhận</strong></span>";
                            else if (row.apply == false)
                               return "<span class='text-secondary'><strong>Từ chối</strong></span>";
                            else if (row.apply == true && row.tienDo == 100 && row.acceptApply == false)
                               return "<span class='text-success'><strong>Hoàn tất</strong></span>";
                            else if (row.apply == true && row.tienDo == 100 && row.acceptApply == true)
                               return "<span class='text-pink'><strong>Kết thúc</strong></span>";
                            else if (row.apply == true && row.tienDo < 100)
                              return "<span class='text-info'><strong>Đang thực hiện</strong></span>";
                       }
                    },
                    {
                       "data": null,
                        render: function(data, type, row) {
                            if (row.apply != true)
                                return "<button id='delWork' data-id="+row.id+" class='btn btn-danger btn-sm'>Xóa</button>&nbsp;<button id='showEdit' data-id='"+row.id+"' data-surname='"+row.surname+"' class='btn btn-success btn-sm' data-toggle='modal' data-target='#editWork'>Sửa</button>";
                            else if (row.apply == true && row.tienDo == 100 && row.acceptApply == false)
                                return "<button id='showXemXet' data-id='"+row.id+"' class='btn btn-success btn-sm' data-toggle='modal' data-target='#approveWork'>Xác nhận</button>";
                            else if (row.apply == true && row.tienDo == 100 && row.acceptApply == true)
                                return "";
                            else if (row.apply == true && row.tienDo < 100)
                                return "<button id='huy' data-id='"+row.id+"' class='btn btn-warning btn-sm'>Bỏ giao việc</button>";
                            else return "";
                        }
                    }
                ]
            });

            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                    table.cell(cell).invalidate('dom');
                } );
            } ).draw();


             // Lưu công việc
            $('#btnAddWork').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/addpushwork')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#addWorkForm").serialize(),
                    success: function(response) {
                        $("#addWorkForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#addWork").modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: " Lỗi! Không thể giao công việc!"
                        })
                    }
                });
            });

            // Sửa công việc
            $('#btnEditWork').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/editpushwork')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#editWorkForm").serialize(),
                    success: function(response) {
                        $("#editWorkForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#editWork").modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: " Lỗi! Không thể chỉnh sửa công việc!"
                        })
                    }
                });
            });

            $(document).on("click","#delWork", function(){
                 if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/work/delpushwork/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: " " + response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: "Lỗi: Không thể xóa!"
                            })
                        }
                    });
                }
            });

            $(document).on("change","#_check", function(){
                $.ajax({
                    url: "{{url('management/work/checkpush/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi: Không thể xóa!"
                        })
                    }
                });
            });

            $(document).on("click","#showEdit", function(){
                let _name = $(this).data('surname');
                $.ajax({
                    url: "{{url('management/work/getworkedit/')}}" + "/" + $(this).data('id'),
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $('input[name=_tenCongViec]').val(response.data.tenCongViec);
                        $('input[name=_id]').val(response.data.id);
                        $('input[name=_ngayStart]').val(response.data.ngayStart);
                        $('input[name=_ngayEnd]').val(response.data.ngayEnd);
                        $('input[name=_yeuCau]').val(response.data.requestWork);
                        $('input[name=_nguoiNhan]').val(_name);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi: Không tải được công việc!"
                        })
                    }
                });
            });

            $(document).on("click","#showResult", function(){

            });

            $(document).on("click","#showXemXet", function(){
                $('input[name=_idPhanHoi]').val($(this).data('id'));
            });

             // Chấp nhận
            $('#approve').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/approve')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#responseForm").serialize(),
                    success: function(response) {
                        $("#responseForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#approveWork").modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: " Lỗi! Không thể cập nhận xem xét kết quả!"
                        })
                    }
                });
            });

            // Từ chối
            $('#noapprove').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/noapprove')}}",
                    type: "post",
                    dataType: 'json',
                    data: $("#responseForm").serialize(),
                    success: function(response) {
                        $("#responseForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $("#approveWork").modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: " Lỗi! Không thể cập nhận xem xét kết quả!"
                        })
                    }
                });
            });

            $(document).on("click","#huy", function(){
                 if(confirm('Xác nhận hủy bỏ công việc đã giao\nCông việc sau khi hủy sẽ bị xóa khỏi hệ thống?')) {
                    $.ajax({
                        url: "{{url('management/work/delpushwork/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: " " + response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: "Lỗi: Không thể xóa!"
                            })
                        }
                    });
                }
            });

             $(document).on("click","#viewMore", function(){
                $.ajax({
                    url: "{{url('management/work/viewmore/')}}" + "/" + $(this).data('id'),
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: " " + response.message
                        })
                        $('#lyDo').text(response.data.ketQua);
                        $('#lyDoGhiChu').text(response.data.ghiChu);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: "Lỗi: Không tải được!"
                        })
                    }
                });
            });

            // -- event realtime
            let es = new EventSource("{{route('action.reg')}}");
            es.onmessage = function(e) {
                console.log(e.data);
                let fullData = JSON.parse(e.data);
                if (fullData.flag == true) {
                   table.ajax.reload();
                }
            }
            // -- event realtime
        });
    </script>
@endsection
