
@extends('admin.index')
@section('title')
    Đề nghị cấp xăng
@endsection
@section('script_head')
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Đề nghị nhiên liệu</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Đề nghị nhiên liệu</li>
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
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đề nghị nhiên liệu</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="so-00" role="tabpanel" aria-labelledby="so-00-tab">
                        <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
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
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">ĐỀ NGHỊ CẤP NHIÊN LIỆU</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="addForm" action="{{route('capxang.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Loại xe:</label>                                                        
                                                                <input placeholder="Accent, Santafe,..." type="text" name="loaiXe" class="form-control" required="required"/>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Biển số/số khung: </label>                                                        
                                                                <input placeholder="Biển số/Số khung" type="text" name="bienSo" class="form-control" required="required"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Loại nhiên liệu:</label>                                                        
                                                            <select name="loaiNhienLieu" class="form-control">
                                                                <option value="X">Xăng</option>
                                                                <option value="D">Dầu</option>
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <label>Số lít:</label>                                                        
                                                                <input placeholder="Số lít" type="number" min="0" name="soLit" required="required" class="form-control"/>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>Khách hàng (nếu có): </label>                                                        
                                                                <input placeholder="Thông tin khách hàng" type="text" name="khachHang" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <label>Lý do cấp:</label>                                                        
                                                            <input type="text" name="lyDoCap" required="required" class="form-control">
                                                        </div> -->
                                                        <div class="form-group">
                                                            <label>Lý do cấp:</label>                                                        
                                                            <select name="lyDoCap" class="form-control">
                                                                <option value="Xe showroom">Xe showroom</option>
                                                                <option value="Công tác + sếp">Công tác + sếp</option>
                                                                <option value="Lái thử + thị trường">Lái thử + thị trường</option>
                                                                <option value="Giao xe mới">Giao xe mới</option>
                                                                <option value="Xe lưu kho">Xe lưu kho</option>
                                                                <option value="Dịch vụ">Dịch vụ</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Ghi chú:</label>                                                        
                                                            <input placeholder="Ghi chú (nếu có)" type="text" name="ghiChu" class="form-control">
                                                        </div>
                                                        <!-- <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <label>Số km đi (dự kiến):</label>                                                        
                                                                <input placeholder="Số km" type="number" min="0" name="km" class="form-control"/>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label>Từ: </label>                                                        
                                                                <input placeholder="Điểm đi" type="text" name="from" class="form-control"/>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <label>Đến: </label>                                                        
                                                                <input placeholder="Điểm đến" type="text" name="to" class="form-control"/>
                                                            </div>
                                                        </div> -->
                                                        <div class="form-group">
                                                            <select name="leadCheck" class="form-control">
                                                                <option value="">Chọn người duyệt</option>
                                                                @foreach($lead as $row)
                                                                    @if($row->hasRole('lead'))
                                                                        <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                    <div class="card-footer">
                                                        <button id="btnAdd" class="btn btn-primary">GỬI</button>
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
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày</th>
                                    <th>Đề nghị</th>
                                    <th>Xe - Biển số</th>
                                    <th>Nhiên liệu</th>
                                    <th>Số lít</th>
                                    <th>Khách hàng</th>
                                    <th>Lý do cấp</th>
                                    <th>Ghi chú</th>
                                    <th>Trưởng bộ phận</th>
                                    <th>Hành chính</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deNghi as $row)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{\HelpFunction::revertCreatedAt($row->created_at)}}</td>
                                        <td> @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không
                                                @endif</td>
                                        <td>{{$row->fuel_car}}; {{$row->fuel_frame}}</td>
                                        <td>
                                            {{$row->fuel_type == 'X' ? "Xăng" : "Dầu"}}
                                        </td>
                                        <td>{{$row->fuel_num}}</td>
                                        <td>{{$row->fuel_guest}}</td>
                                        <td>{{$row->fuel_lyDo}}</td>
                                        <td>{{$row->ghiChu}}</td>
                                        <td>@if($row->lead_id !== null)
                                                {{$row->userLead->userDetail->surname}}
                                                @if($row->lead_check == true)
                                                    <span class="badge badge-success">Đã duyệt</span>
                                                    @else
                                                    <span class="badge badge-secondary">Chưa duyệt</span>
                                                    @endif  
                                            @endif           
                                        </td>
                                        <td>
                                            @if($row->fuel_allow == true)
                                            <span class="badge badge-success">Đã duyệt</span>
                                            @else
                                            <span class="badge badge-secondary">Chưa duyệt</span>
                                            @endif
                                        </td>
                                        <td>
                                                @if($row->fuel_allow == false)
                                                <button id="del" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Xóa</button>
                                                @else
                                                <a href="{{route('xang.in', ['id' => $row->id])}}" target="_blank" class="btn btn-success btn-xs">IN PHIẾU</a>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- Page specific script -->
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');
        });

      //Delete data
      $(document).on('click','#del', function(){
            if(confirm('Bạn có chắc muốn xóa?')) {
                $.ajax({
                    url: "{{url('management/capxang/del/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'info',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('capxang.denghi')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không thể xóa lúc này!"
                        })
                    }
                });
            }
        });
        // -- event realtime
        let es = new EventSource("{{route('action.reg')}}");
        es.onmessage = function(e) {
            console.log(e.data);
            let fullData = JSON.parse(e.data);
            if (fullData.flag == true) {
               open('{{route('capxang.denghi')}}','_self');
            }
        }
        // -- event realtime
    </script>
@endsection
