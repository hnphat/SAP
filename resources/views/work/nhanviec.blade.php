
@extends('admin.index')
@section('title')
  Nhận việc
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
                        <h1 class="m-0"><strong>NHẬN VIỆC</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Công việc</li>
                            <li class="breadcrumb-item active">Nhận việc</li>
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
                            <h5>NHẬN VIỆC</h5>
                        </div>
                        <div class="card-body">
                            <table id="dataTable" class="display" style="width:100%">
                                <thead>
                                <tr class="bg-gray">
                                    <th>STT</th>
                                    <th>Ngày</th>
                                    <th>Người giao</th>
                                    <th>Tên công việc</th>
                                    <th>Yêu cầu</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                     <!-- Medal Reponse Work-->
                    <div class="modal fade" id="approveWork">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">TỪ CHỐI CÔNG VIỆC</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card">
                                        <form id="responseForm" autocomplete="off">
                                            {{csrf_field()}}
                                            <div class="card-body">
                                                <input type="hidden" name="_id">
                                                <div class="form-group">
                                                    <label>Lý do từ chối: </label>
                                                    <input name="ketQua" placeholder="Lý do (nếu có)" type="text" class="form-control">
                                                </div>   
                                                <div class="form-group">
                                                    <label>Ghi chú: </label>
                                                    <input name="ghiChu" placeholder="Ghi chú (nếu có)" type="text" class="form-control">
                                                </div>   
                                                <button id="noapprove" class="btn btn-warning">XÁC NHẬN</button>    
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
                ajax: "{{ url('management/work/getworkdetail') }}",
                "columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    },{
                        "targets": 1,
                        "className": "text-center",
                    }
                ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "ngayTao" },
                    { "data": "surname" },
                    { "data": "tenCongViec"},
                    { "data": "requestWork"},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return ""+revertDate(row.ngayStart)+"";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return ""+revertDate(row.ngayEnd)+"";
                        }
                    },
                    {
                       "data": null,
                        render: function(data, type, row) {
                                return "<button id='cancel' data-id="+row.id+" class='btn btn-warning btn-sm' data-toggle='modal' data-target='#approveWork'>Từ chối</button>&nbsp;<button id='ok' data-id="+row.id+" class='btn btn-info btn-sm'>Nhận việc</button>";
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


           $(document).on("click","#cancel", function(){
                $('input[name=_id]').val($(this).data('id'));
            });

           $(document).on("click","#ok", function(){
                 if(confirm('Đồng ý nhận việc được giao?')) {
                    $.ajax({
                        url: "{{url('management/work/getapprove/')}}",
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

             // Từ chối
            $('#noapprove').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/work/getnoapprove')}}",
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
        });
    </script>
@endsection
