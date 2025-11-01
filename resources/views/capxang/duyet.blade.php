
@extends('admin.index')
@section('title')
    Duyệt cấp xăng
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
                        <h1 class="m-0"><strong>Duyệt nhiên liệu</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Hành chính</li>
                            <li class="breadcrumb-item active">Duyệt nhiên liệu</li>
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
                        <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr class="bg-gradient-lightblue">
                                    <th>TT</th>
                                    <th>Ngày</th>
                                    <th>CODE</th>
                                    <th>Đề nghị</th>
                                    <th>Xe</th>
                                    <th>Biển số/Số khung</th>
                                    <th>Nhiên liệu</th>
                                    <th>Số lít</th>
                                    <th>Khách hàng</th>
                                    <th>Hạng mục</th>
                                    <th>Lý do cấp</th>
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
                                        <td>HAGI-CX-0{{$row->id}}</td>
                                        <td> @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không
                                                @endif</td>
                                        <td>{{$row->fuel_car}}</td>
                                        <td>{{$row->fuel_frame}}</td>
                                        <td>
                                            {{$row->fuel_type == 'X' ? "Xăng" : "Dầu"}}
                                        </td>
                                        <td>{{$row->fuel_num}}</td>
                                        <td>{{$row->fuel_guest}}</td>
                                        <td>{{$row->fuel_lyDo}}</td>
                                        <td>{{$row->fuel_ghiChu}}</td>
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
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                                            \Illuminate\Support\Facades\Auth::user()->hasRole('system'))
                                            <td>
                                                @if($row->fuel_allow == true)
                                                @else
                                                    <button id="allow" data-id="{{$row->id}}" class="btn btn-success btn-xs">Duyệt</button>
                                                    <button id="kiemTra" data-id="{{$row->id}}" data-toggle="modal" data-target="#kiemTraModal" class="btn btn-info btn-xs">Kiểm tra</button>
                                                    <button id="cancel" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Hủy</button>
                                                @endif
                                            </td>
                                        @endif
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('lead'))
                                            <td>
                                                @if($row->lead_check == true)
                                                @else
                                                    <button id="leadAllow" data-id="{{$row->id}}" class="btn btn-success btn-xs">Duyệt</button>
                                                @endif
                                            </td>
                                        @endif
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
    <!-- Medal Add -->
    <div class="modal fade" id="kiemTraModal">
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
                                <h3 class="card-title">KIỂM TRA TRƯỚC DUYỆT</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h5><strong>LẦN ĐỀ NGHỊ GẦN NHẤT</strong></h5>
                                <p id="hasFound" style="display: none;">
                                    Thời gian đề nghị: <strong id="ngayDeNghi"></strong><br/>
                                    Loại: <strong id="loai"></strong><br/>
                                    Tên xe: <strong id="tenXe"></strong><br/>
                                    Biển số/Số khung: <strong id="bienSo"></strong><br/>
                                    Khách hàng (nếu có): <strong id="khachHang"></strong><br/>
                                    Số lít yêu cầu: <strong id="soLit"></strong> (lít); Loại: <strong id="loaiNhienLieu"></strong><br/>
                                    Lý do đề nghị: <strong id="lyDo"></strong><br/>
                                    Trưởng bộ phận phê duyệt: <strong id="truongBP"></strong><br/>
                                </p>                               
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
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

        $(document).ready(function() {
            $("#hasFound").hide();
        });

        $(document).on('click','#allow', function(){
            if (confirm('Xác nhận duyệt phiếu cấp xăng này!')) {
                $.ajax({
                    url: "{{url('management/capxang/allow/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('capxang.duyet')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không phê duyệt lúc này!"
                        })
                    }
                });
            }
        });

        $(document).on('click','#cancel', function(){
            if (confirm('Xác nhận không duyệt và hủy phiếu cấp xăng này!')) {
                $.ajax({
                    url: "{{url('management/capxang/cancel/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('capxang.duyet')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không phê duyệt lúc này!"
                        })
                    }
                });
            }
        });

        $(document).on('click','#leadAllow', function(){
            if (confirm('Xác nhận duyệt phiếu cấp xăng này!')) {
                $.ajax({
                    url: "{{url('management/capxang/leadallow/')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "id": $(this).data('id')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(function(){
                            open('{{route('capxang.duyet')}}','_self');
                        }, 1000);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: "Không phê duyệt lúc này!"
                        })
                    }
                });
            }
        });

        $(document).on('click','#kiemTra', function(){
            $.ajax({
                url: "{{url('management/capxang/kiemtra/')}}",
                type: "post",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    "id": $(this).data('id')
                },
                success: function(response) {
                    if (response.code == 200) {
                        if (response.data) {                                
                            
                            $("#ngayDeNghi").text(response.data.ngayNew);
                            $("#loai").text(response.data.fuel_lyDo);
                            $("#tenXe").text(response.data.fuel_car);
                            $("#bienSo").text(response.data.fuel_frame);
                            $("#khachHang").text(response.data.fuel_guest);
                            $("#soLit").text(response.data.fuel_num);
                            $("#loaiNhienLieu").text(response.data.fuel_type == "X" ? "Xăng" : "Dầu");
                            $("#lyDo").text(response.data.fuel_ghiChu);
                            $("#truongBP").text(response.data.truongBP);
                            $("#hasFound").show();
                        } else {
                           
                            $("#hasFound").hide();
                            $("#ngayDeNghi").text("");
                            $("#loai").text("");
                            $("#tenXe").text("");
                            $("#bienSo").text("");
                            $("#khachHang").text("");
                            $("#soLit").text("");
                            $("#loaiNhienLieu").text("");
                            $("#lyDo").text("");
                            $("#truongBP").text("");
                        }
                    } else {
                        
                        $("#hasFound").hide();
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: "Không thể kiểm tra!"
                    })
                   
                    $("#hasFound").hide();
                }
            });
        });
         
        //-- event realtime
        let es = new EventSource("{{route('action.reg')}}");
        es.onmessage = function(e) {
            console.log(e.data);
            let fullData = JSON.parse(e.data);
            if (fullData.flag == true) {
               open('{{route('capxang.duyet')}}','_self');
            }
        }
        //-- event realtime
    </script>
@endsection
