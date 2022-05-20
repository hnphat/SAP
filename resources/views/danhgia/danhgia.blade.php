@extends('admin.index')
@section('title')
    Góp ý khách hàng
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .kbw-signature {
            display: inline-block;
            border: 1px solid #a0a0a0;
            -ms-touch-action: none;
        }
        .kbw-signature-disabled {
            opacity: 0.35;
        }
        .kbw-signature { 
            width: 500px; 
            height: 200px;
        }
        #sig canvas{
            #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Góp ý khách hàng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
                            <li class="breadcrumb-item active">Góp ý khách hàng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Góp ý khách hàng</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <button class="btn btn-success" data-toggle="modal" data-target="#addModal"> Thêm mới </button><br/><br/>
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Ngày</th>
                                        <th>Tạo đánh giá</th>
                                        <th>Số báo giá</th>
                                        <th>Điểm vệ sinh</th>
                                        <th>Vệ sinh lại</th>
                                        <th>Chữ ký</th>
                                        <th>Tác vụ</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>   
    <!-- Medal Add -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">THÊM ĐÁNH GIÁ</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addForm" method="post" autocomplete="off">
                            {{csrf_field()}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Số báo giá:</label>                                                        
                                    <input placeholder="Số báo giá VD: VS067-220101-001" type="text" name="soBaoGia" class="form-control"/>
                                </div>     
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnAdd" class="btn btn-success">THÊM MỚI</button>
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

   <!-- Medal Edit -->
   <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
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
                            <h3 class="card-title">KHẢO SÁT CHẤT LƯỢNG VỆ SINH XE SAU KHI SỬA CHỮA</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="editForm" method="post" autocomplete="off" >
                            {{csrf_field()}}
                            <div class="card-body">
                               <input type="hidden" name="idDanhGia">
                               <h6>Nhằm nâng cao chất lượng dịch vụ, Hyundai An Giang kính mời Quý khách hàng tham gia khảo sát dưới đây</h6>
                               <div class="form-group">
                                    <label class='text-primary'>A. Quý khách đánh giá như thế nào về độ sạch sẽ của xe sau khi sửa chữa.</label>
                                    <table class="table table-bordered">
                                        <tr class="text-center">
                                            <th>1 <br/>Rất tệ</th>
                                            <th><br/>2</th>
                                            <th><br/>3</th>
                                            <th><br/>4</th>
                                            <th>5 <br/> Trung bình</th>
                                            <th><br/>6</th>
                                            <th><br/>7</th>
                                            <th><br/>8</th>
                                            <th><br/>9</th>
                                            <th>10<br/> Rất tốt</th>
                                        </tr>
                                        <tr>        
                                        <?php
                                            for ($i = 1; $i <= 10; $i++) {                           
                                                echo "<td class='text-center'><input style='transform: scale(2);' type='radio' value='".$i."' name='diemVeSinh'/></td>";
                                            }
                                        ?>
                                        </tr>
                                    </table>
                               </div>
                               <div class="form-group">
                                    <label class='text-primary'>B. Quý khách có nhu cầu được vệ sinh xe thêm 01 lần nữa?</label>
                                    <h3><input style='transform: scale(1.5);' value="1" type="radio" id="co" name="veSinhLai"> <label for="co">Có</label></h3>
                                    <h3><input style='transform: scale(1.5);' value="0" type="radio" id="khong" name="veSinhLai"> <label for="khong">Không</label></h3>
                               </div>
                               <div class="form-group">
                                    <label class='text-primary'>C. Xác nhận của khách hàng</label><br/>
                                    <div id="sig"></div>
                                    <textarea id="signature64" name="signed" style="display: none"></textarea>
                               </div>
                               <h6>Xin  chân thành cảm ơn Quý khách đã tham gia khảo sát.</h6>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button id="btnUpdate" class="btn btn-primary">GỬI</button>
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
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="script/jquery.signature.js"></script>  
    <script src="script/jquery.ui.touch-punch.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

               
        // show data
        $(document).ready(function() {   
            var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#00f'});         
            $('#widget').draggable();
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/danhgia/getlist') }}",
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
                    { "data": null,
                        render: function(data, type, row) {
                            return row.ngay + "/" + row.thang + "/" + row.nam;
                        } 
                    },
                    { "data": "surname" },
                    { "data": "soBaoGia" },
                    { "data": null,
                        render: function(data, type, row) {
                            if (row.chuKy != null) {
                                if (row.diemVeSinh < 5)
                                    return "<span class='text-bold text-danger'>"+row.diemVeSinh+"</span>";
                                else
                                    return "<span class='text-bold text-info'>"+row.diemVeSinh+"</span>";
                            }                                
                            else
                                return "<span class='text-bold text-pink'>Chưa đánh giá</span>";                          
                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            if (row.veSinhLai == true && row.chuKy != null)
                                return "<span class='text-bold text-danger'>Có</span>";
                            else if (row.veSinhLai == false && row.chuKy != null)
                                return "<span class='text-bold'>Không</span>";
                            else
                                return "<span class='text-bold text-pink'>Chưa đánh giá</span>";
                        } 
                    },
                    { "data": null,
                        render: function(data, type, row) {
                            if (row.chuKy != null)
                                return "<img src='{{asset('upload/sign/')}}/"+row.chuKy+"' style='width: 100%;'/>";
                                
                            else
                                return "<span class='text-bold text-pink'>Chưa đánh giá</span>";
                        } 
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.chuKy == null) {
                                return "<button id='danhGia' data-toggle='modal' data-target='#editModal' data-id='"+row.id+"' class='btn btn-primary btn-sm'>Mở đánh giá</button>"
                                +  "&nbsp;<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Xoá</button>";
                            }
                            else {
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                    return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'>Xoá</button>";
                                @else
                                    return "";
                                @endif
                            }                             
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

            // // delete data
            $(document).on('click','#delete', function(){
                if(confirm("Bạn có chắc muốn xoá?")) {
                    $.ajax({
                        url: "{{url('management/danhgia/delete/')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            table.ajax.reload();
                        },
                        error: function(){
                            Toast.fire({
                                icon: 'warning',
                                title: "Error 500!"
                            })
                        }
                    });
                }
            });

            // load
            $(document).on('click','#danhGia', function(){
               $("input[name=idDanhGia]").val($(this).data('id'));
            });

             //upload
         $(document).one('click','#btnUpdate',function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#editForm').submit(function(e) {
                e.preventDefault();   
                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    url: "{{ url('management/danhgia/ajax/post/')}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("#btnUpdate").attr('disabled', true).html("Đang xử lý....");
                    },
                    success: (response) => {
                        this.reset();
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        $("#btnUpdate").attr('disabled', false).html("GỬI");
                        $("#editModal").modal('hide');
                        table.ajax.reload();
                    },
                        error: function(response){
                        Toast.fire({
                            icon: 'info',
                            title: ' Có lỗi' 
                        })
                        $("#btnUpdate").attr('disabled', false).html("GỬI");
                    }
                });
            });                
        });

            $("#btnAdd").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{url('management/danhgia/them')}}",
                    type: "post",
                    dataType: "json",
                    data: $("#addForm").serialize(),
                    success: function(response) {
                        $("#addForm")[0].reset();
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                        table.ajax.reload();
                        $("#addModal").modal('hide');
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
