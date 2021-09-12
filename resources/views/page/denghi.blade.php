@extends('admin.index')
@section('title')
    Duyệt đề nghị hợp đồng
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Phê duyệt đề nghị hợp đồng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản trị</li>
                            <li class="breadcrumb-item active">Đề nghị hợp đồng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="row container">
                <div class="col-sm-12">
                    <table id="dataTableCodeWait" class="display" style="width:100%">
                        <thead>
                        <tr class="bg-gradient-lightblue">
                            <th>TT</th>
                            <th>Sale bán</th>
                            <th>Khách hàng</th>
                            <th>Xe bán</th>
                            <th>Màu sắc</th>
                            <th>Giá</th>
                            <th>Tiền cọc</th>
                            <th>Admin duyệt</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.content -->
        <!-- Medal check -->
        <div class="modal fade" id="checkIn">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">PHÊ DUYỆT ĐỀ NGHỊ</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="ajaxform" autocomplete="off">
                                {{csrf_field()}}
                                <div class="card-body">
                                    <div class="form-group">
                                        <p>Sale đề nghị: <strong id="tenSale"></strong> <br/>
                                        Xe đề nghị: <strong id="tenXe"></strong> <br/>
                                        Màu sắc: <strong id="mauXe"></strong></p>
                                    </div>
                                    <input type="hidden" name="idUserCreate">
                                    <input type="hidden" name="idGuest">
                                    <input type="hidden" name="idRequest">
                                    <div class="form-group">
                                        <label for="pass">Trạng thái xe:
                                            <strong id="status1" class="text-success" style="display: none;">Đang có xe</strong>
                                            <strong id="status2" class="text-danger" style="display: none;">Chưa có xe</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="pass">Chọn xe duyệt: </label>
                                        <select class="form-control" name="duyetXe" id="duyetXe">
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button id="pheDuyet" type="button" class="btn btn-primary">Duyệt</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
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

        // show data
        $(document).ready(function() {

            var table = $('#dataTableCodeWait').DataTable({
                // paging: false,    use to show all data
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/denghi/get/list/wait/all') }}",
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "order": [
                    [ 0, 'desc' ]
                ],
                lengthMenu:  [5, 10, 25, 50, 75, 100 ],
                columns: [
                    { "data": null },
                    { "data": "user_name" },
                    { "data": "guestname" },
                    { "data": "carname" },
                    { "data": "color" },
                    { "data": "giaXe", render: $.fn.dataTable.render.number(',','.',0,'')},
                    { "data": "tamUng", render: $.fn.dataTable.render.number(',','.',0,'')},
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.admin_check == 0)
                                return "<button id='checkBtn' data-idrequest='"+row.id+"' data-guest='"+row.guest_id+"' data-user='"+row.user_id+"' data-id='"+row.car_detail_id+"' data-sale='"+row.user_name+"' data-car='"+row.carname+"' data-color='"+row.color+"' data-toggle='modal' data-target='#checkIn' class='btn btn-warning btn-sm'><span class='fas fa-eye-slash'></span></button>";
                            else
                                return "<button class='btn btn-success btn-sm'><span class='fas fa-eye'></span></button> &nbsp;&nbsp; <button id='thuHoi' data-idrequest='"+row.id+"' class='btn btn-danger btn-sm'>Thu hồi</button>";
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

            $(document).on('click','#thuHoi',function(){
                if(confirm('Xác nhận thu hồi phê duyệt cho đề nghị này?\nXe gán cho đề nghị sẽ được thu lại vào kho.')) {
                    $.ajax({
                        url: "{{url('management/denghi/thuhoi/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('idrequest')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Lỗi máy chủ: Không thể thu hồi phê duyệt đê nghị này!"
                            })
                        }
                    });
                }
            });


            // show Data
            $(document).on('click','#checkBtn', function(){
                $("#tenSale").text($(this).data('sale'));
                $("#tenXe").text($(this).data('car'));
                $("#mauXe").text($(this).data('color'));
                $("input[name=idUserCreate]").val($(this).data('user'));
                $("input[name=idRequest]").val($(this).data('idrequest'));
                $("input[name=idGuest]").val($(this).data('guest'));
                $.ajax({
                    url: "{{url('management/denghi/pheduyet/show/')}}",
                    type: "post",
                    dataType: "text",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id'),
                        "color": $(this).data('color'),
                    },
                    success: function(response) {
                        console.log(response);
                        $("#duyetXe").html(response);
                        Toast.fire({
                            icon: 'success',
                            title: "Đã lấy thông tin kho xe"
                        })
                        if (!$('#duyetXe').val()) {
                            $("#status1").css("display","none");
                            $("#status2").css("display","block");
                            $('#pheDuyet').css("display","none")
                            $('#duyetXe').css("display","none")
                        } else {
                            $("#status1").css("display","block");
                            $("#status2").css("display","none");
                            $('#pheDuyet').css("display","block")
                            $('#duyetXe').css("display","block")
                        }
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });

            $("#pheDuyet").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/denghi/pheduyet/')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#ajaxform").serialize(),
                    success: function(response) {
                        $("#ajaxform")[0].reset();
                        Toast.fire({
                            icon: 'success',
                            title: "Đã duyệt đề nghị hợp đồng!"
                        })
                        table.ajax.reload();
                        $("#checkIn").modal('hide');
                    },
                    error: function(){
                        Toast.fire({
                            icon: 'warning',
                            title: "Error 500!"
                        })
                    }
                });
            });
        });
    </script>
@endsection
