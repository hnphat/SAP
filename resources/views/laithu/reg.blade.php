
@extends('admin.index')
@section('title')
    Đăng ký xe
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
                        <h1 class="m-0"><strong>Đăng ký xe</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý xe demo</li>
                            <li class="breadcrumb-item active">Đăng ký xe</li>
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
                            <a class="nav-link active" id="so-00-tab" data-toggle="pill" href="#so-00" role="tab" aria-controls="so-00" aria-selected="true">Đăng ký sử dụng</a>
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
                                                    <h3 class="card-title">ĐĂNG KÝ</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <!-- form start -->
                                                <form id="addForm" action="{{route('reg.post')}}" method="post" autocomplete="off">
                                                    {{csrf_field()}}
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Chọn xe</label>
                                                            <select name="xe" class="form-control">
                                                                @foreach($car as $row)
                                                                    <option value="{{$row->id}}">{{$row->name}}; Biển số: {{$row->number_car}}; Màu: {{$row->mau}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Lý do sử dụng</label>
                                                            <input required="required" type="text" name="lyDo" placeholder="Lý do sử dụng" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số km hiện tại (km)</label>
                                                            <input required="required" type="number" name="km" placeholder="Số km hiện tại" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Số xăng hiện tại (km xăng)</label>
                                                            <input required="required" type="number" name="xang" placeholder="Số xăng hiện tại" class="form-control">
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <label>
                                                                <input type="checkbox" name="fuelRequest" id="fuelRequest" />
                                                                Đề nghị cấp nhiên liệu
                                                            </label>
                                                            <input name="fuelLyDo" type="text" class="form-control pass" placeholder="Lý do cấp"
                                                                   required="required" disabled="disabled"/>
                                                            <input name="fuelNum" type="number" class="form-control pass" placeholder="Số lít"
                                                                   required="required" disabled="disabled"/>
                                                            <select name="fuelType" class="form-control pass" required="required" disabled="disabled">
                                                                <option value="">Chọn loại nhiên liệu</option>
                                                                <option value="X">Xăng</option>
                                                                <option value="D">Dầu</option>
                                                            </select>
                                                            <select name="leadCheck" class="form-control pass" required="required" disabled="disabled">
                                                                <option value="">Chọn người duyệt</option>
                                                                @foreach($lead as $row)
                                                                    @if($row->hasRole('lead'))
                                                                        <option value="{{$row->id}}">{{$row->userDetail->surname}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>

                                                        </div> -->
                                                        <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <label>Tình trạng xe</label> <br/>
                                                                Vệ sinh: &nbsp;&nbsp;<input type="radio" name="veSinh" value="1" checked="checked"> Sạch &nbsp;&nbsp;
                                                                <input type="radio" name="veSinh" value="0"> Dơ
                                                                <input type="text" name="ghiChuVeSinh" placeholder="Ghi chú" class="form-control">
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label>&nbsp;</label> <br/>
                                                                Bên ngoài: &nbsp;&nbsp;<input type="radio" name="benNgoai" value="1"checked="checked"> Bình thường &nbsp;&nbsp;
                                                                <input type="radio" name="benNgoai" value="0"> Trầy
                                                                <input type="text" name="ghiChuBenNgoai" placeholder="Ghi chú" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col">
                                                                <label>Thời gian đi</label>
                                                                <input required="required" type="time" name="timeHourGo" class="form-control">
                                                            </div>
                                                            <div class="form-group col">
                                                                <label>Ngày đi</label>
                                                                <input required="required" min="<?php echo Date('Y-m-d');?>" type="date" name="timeGo" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                             <div class="form-group col">
                                                                <label>Thời gian về (dự kiến)</label>
                                                                <input required="required" type="time" name="timeReturn" class="form-control">
                                                            </div>
                                                            <div class="form-group col">
                                                                <label>Ngày về (dự kiến)</label>
                                                                <input required="required" min="<?php echo Date('Y-m-d');?>" type="date" name="dateDuKien" class="form-control">
                                                            </div>
                                                            <select name="tbpCheck" class="form-control">
                                                                <option value="">Chọn trưởng bộ phận duyệt đăng ký xe</option>
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
                                                        <button id="btnAdd" type="button" class="btn btn-primary">Lưu</button>
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
                                    <th>Ngày đk</th>
                                    <th>Sử dụng</th>
                                    <th>Xe</th>
                                    <th>Lý do</th>
                                    <th>Km</th>
                                    <th>Xăng</th>
                                    <th>Tình trạng xe</th>
                                    <th>TG Đi</th>
                                    <th>Trạng thái</th>
                                    <th>Hồ sơ (nhận)</th>
                                    <th>Tác vụ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reg as $row)
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                       $row->id_user_reg == \Illuminate\Support\Facades\Auth::user()->id)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{\HelpFunction::revertCreatedAt($row->created_at)}}</td>
                                            <td>
                                                @if($row->user !== null)
                                                    {{$row->user->userDetail->surname}}
                                                @else
                                                    Không
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->xeLaiThu !== null)
                                                    {{$row->xeLaiThu->name}}
                                                @else
                                                    Không
                                                @endif
                                            </td>
                                            <td>{{$row->lyDo}}</td>
                                            <td>{{$row->km_current}}</td>
                                            <td>{{$row->fuel_current}}</td>
                                            <td>{{$row->car_status}}</td>
                                            <td class="text-fuchsia"><strong>{{$row->time_go}} {{\HelpFunction::revertDate($row->date_go)}}</strong></td>
                                            <td>
                                                @if($row->id_lead_check == null)
                                                    <span class="btn btn-secondary btn-xs">TBP: Không có</span>
                                                @elseif($row->id_lead_check_status == 1)
                                                    <span class="btn btn-info btn-xs">TBP: Đã duyệt</span>
                                                @else
                                                    <span class="btn btn-danger btn-xs">TBP: Chưa</span>
                                                @endif
                                            
                                                @if($row->allow == 1)
                                                    <span class="btn btn-info btn-xs">Xe: Đã duyệt</span>
                                                @else
                                                    <span class="btn btn-warning btn-xs">Xe: Đợi duyệt</span>
                                                @endif
                                                    @if($row->fuel_request == true && $row->fuel_allow == false)
                                                        <span class="btn btn-secondary btn-xs">Nhiên liệu: Đợi duyệt</span>
                                                    @elseif($row->fuel_allow == true)
                                                        <a href="{{route('xang.in', ['id' => $row->id])}}" target="_blank" class="btn btn-success btn-xs">Xăng: Đã duyệt</a>
                                                    @endif
                                            </td>
                                            <td>{{$row->hoSoDi}}</td>
                                            <td>
                                                @if($row->allow == 1)
                                                    <!-- <a target="_blank" href="{{route('qrcode', ['content' => url("/show/{$row->id}")])}}" class="btn btn-dark btn-xs">QR Code</a> -->
                                                    <a target="_blank" href="{{route('showbv', ['id' => $row->id])}}" class="btn btn-dark btn-xs">Kiểm tra</a>
                                                @else
                                                    <button id="del" data-id="{{$row->id}}" class="btn btn-danger btn-xs">Xóa</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
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

        // -- event realtime
        let es = new EventSource("{{route('action.reg')}}");
        es.onmessage = function(e) {
            console.log(e.data);
            let fullData = JSON.parse(e.data);
            if (fullData.flag == true) {
               open('{{route('laithu.reg')}}','_self');
            }
        }
        // -- event realtime
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        $(document).ready(function() {
            $("#dataTable").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        //Delete data
        $(document).on('click','#del', function(){
            if(confirm('Bạn có chắc muốn xóa?')) {
                $.ajax({
                    url: "{{url('management/reg/del/')}}",
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
                            open('{{route('laithu.reg')}}','_self');
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

        $(document).on('change','#fuelRequest', function(){
            if($(this).is(':checked'))
                $('.pass').removeAttr('disabled');
            else
                $('.pass').attr('disabled','disabled');
        });

        $(document).ready(function(){
            $('#btnAdd').click(function(event) {               
                let form = $("#addForm");   
                $("#btnAdd").attr('disabled', true).html("Đang xử lý vui lòng đợi....");            
                event.preventDefault();
                form.submit();
            }); 
        });
        
    </script>
@endsection
