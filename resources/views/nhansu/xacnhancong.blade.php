@extends('admin.index')
@section('title')
    Quản lý chốt công
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #tuyChonXuat {
            width: 500px;
            border: 1px solid black;
        }
        
        #tuyChonXuat tr, th, td {
            border: 1px solid black;
        }

        #tuyChonXuat th, td {
            padding: 5px;
        }

        #boxChotCong {
            height: 450px;
            overflow: auto;
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
                        <h1 class="m-0"><strong>Quản lý chốt công</strong> 
                    </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý chốt công</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
        <div class="container-fluid">
        <form id="formCommit">
        </form>
                <!-- <details>
                    <summary><strong>TÙY CHỌN XUẤT</strong></summary>
                    <div id="boxChotCong">
                        <form id="formCommit">
                            @csrf
                            <table id="tuyChonXuat">
                                <tr>
                                    <th>Nhân viên</th>
                                    <th>
                                        <input type="checkbox" name="selectAllCheckBox" checked="checked" class="form-control">
                                    </th>
                                </tr>
                                @foreach($user as $row)
                                    @if($row->hasRole('chamcong'))
                                    <tr>
                                        <td>{{$row->userDetail->surname}}</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="checkboxvar[]" value="{{$row->id}}" checked="checked" class="form-control checkvar">
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>
                        </form>
                    </div>
                </details> -->
                <div class="row">  
                    <div class="col-md-1">
                        <label>Tháng</label>
                        <select name="thang" class="form-control" form="formCommit">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}" <?php if(Date('m') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Năm</label>
                        <select name="nam" class="form-control" form="formCommit">
                            @for($i = 2021; $i < 2100; $i++)
                                <option value="{{$i}}" <?php if(Date('Y') == $i) echo "selected";?>>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="chon" type="button "class="btn btn-xs btn-info" form="formCommit">Xem</button>
                    </div>
                    <!-- <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="exportExcel" type="button "class="btn btn-xs btn-primary">Xuất excel</button>
                    </div> -->
                    <div class="col-md-2">
                        <label>&nbsp;</label><br/>
                        <button id="xacNhanAll" type="button "class="btn btn-xs btn-success">Xác nhận tất cả</button>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label><br/>
                        <button id="huyAll" type="button "class="btn btn-xs btn-warning">Hủy tất cả</button>
                    </div>
                </div>  
                <br/>               
                <p id="loading" style="text-align:center;display:none;">
                    <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width: 100px; height:auto;"/>
                </p>
                <table id="dataTable" class="table table-striped text-center" style="width:100%">
                    <thead> 
                        <tr class="table-primary">
                            <th>Nhân viên</th>
                            <th>Phòng ban</th>
                            <th>Ngày công</th>
                            <th>Phép năm</th>
                            <th>Tăng ca x1</th>
                            <th>Tăng ca x1.5</th>
                            <th>Tăng ca x2</th>
                            <th>Tăng ca x3</th>
                            <th>Tổng trể/sớm</th>
                            <th>Không phép (Trể/sớm/QCC/Nữa ngày)</th>
                            <th>Không phép (Cả ngày)</th>
                            <th>Trạng thái</th>
                            <th>Tác vụ</th>
                        </tr>    
                    </thead>              
                </table>                  
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
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

        // Exe
        $(document).ready(function() {
            // $('input[name=selectAllCheckBox]').change(function() {
            //     $("#formCommit input[type=checkbox]").prop('checked', this.checked);
            // });
            let month = $("select[name=thang]").val();
            let year = $("select[name=nam]").val();
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ route('getchotcong') }}" + '?thang=' + month + "&nam=" + year,
                "order": [
                    [ 1, 'desc' ]
                ],
                columns: [
                    { "data": "name" },
                    { "data": "phongBan" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.ngayCong)
                                return "<span class='text-success'>"+row.ngayCong+" (ngày)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.phepNam)
                                return "<span class='text-success'>"+row.phepNam+" (ngày)</span>";
                            else
                                return "";                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tangCa)
                                return "<span class='text-primary'>"+row.tangCa100+" (giờ)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tangCa)
                                return "<span class='text-primary'>"+row.tangCa150+" (giờ)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tangCa)
                                return "<span class='text-primary'>"+row.tangCa200+" (giờ)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tangCa)
                                return "<span class='text-primary'>"+row.tangCa300+" (giờ)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.tongTre)
                                return "<span class='text-pink'>"+row.tongTre+" (phút)</span>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.khongPhep)
                                return "<span class='text-pink'>"+row.khongPhep+"</span>";
                            else 
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.khongPhepNgay)
                                return "<strong class='text-danger'>"+row.khongPhepNgay+"</strong>";
                            else
                                return "";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.ngayCong)
                                return "<strong class='text-success'>Chốt công</strong>";
                            else
                                return "<strong class='text-danger'>Chưa xác nhận công</strong>";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.ngayCong)
                                return `<button id='huy' data-id='${row.id_user}' data-thang='${row.thang}' data-nam='${row.nam}' class='btn btn-danger btn-sm'>Hủy</button>`;
                            else
                                return ``;
                        }
                    },
                ]
            });

           $("#chon").click(function(e){
            e.preventDefault();
            let month = $("select[name=thang]").val();
            let year = $("select[name=nam]").val();
            let urlpathcurrent = "{{ route('getchotcong') }}";
            table.ajax.url( urlpathcurrent + '?thang=' + month + "&nam=" + year).load();
           });

            //Delete data
            $(document).on('click','#huy', function(){
                if(confirm('Bạn có chắc muốn hủy xác nhận của nhân viên này?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/huy')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "id": $(this).data('id'),
                            "thang": $(this).data('thang'),
                            "nam": $(this).data('nam')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            let month = $("select[name=thang]").val();
                            let year = $("select[name=nam]").val();
                            let urlpathcurrent = "{{ route('getchotcong') }}";
                            table.ajax.url( urlpathcurrent + '?thang=' + month + "&nam=" + year).load();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể hủy lúc này!"
                            })
                        }
                    });
                }
            });

            //Xác nhận tất cả
            $("#xacNhanAll").click(function(){
                if(confirm('Xác nhận chốt công cho TẤT CẢ nhân viên?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/xacnhanall')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "thang": $("select[name=thang]").val(),
                            "nam": $("select[name=nam]").val()
                        },
                        beforeSend: function() {
                            $("#loading").show();
                        },
                        success: function(response){    
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })                    
                            let month = $("select[name=thang]").val();
                            let year = $("select[name=nam]").val();
                            let urlpathcurrent = "{{ route('getchotcong') }}";
                            table.ajax.url( urlpathcurrent + '?thang=' + month + "&nam=" + year).load();            
                        },
                        error: function(){
                            Toast.fire({
                                icon: "error",
                                title: "Lỗi! Không thể tải chi tiết chốt công"
                            })
                        },
                        complete: function() {
                            $("#loading").hide();
                        }
                    });
                }
            });


            // Hủy tất cả
            $("#huyAll").click(function(){
                if(confirm('Xác nhận hủy chốt công cho TẤT CẢ nhân viên?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/chotcong/ajax/huyall')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            "_token": "{{csrf_token()}}",
                            "thang": $("select[name=thang]").val(),
                            "nam": $("select[name=nam]").val()
                        },
                        success: function(response){    
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })                    
                            let month = $("select[name=thang]").val();
                            let year = $("select[name=nam]").val();
                            let urlpathcurrent = "{{ route('getchotcong') }}";
                            table.ajax.url( urlpathcurrent + '?thang=' + month + "&nam=" + year).load();                  
                        },
                        error: function(){
                            Toast.fire({
                                icon: "error",
                                title: "Lỗi! Không thể tải chi tiết chốt công"
                            })
                        }
                    });
                }
            });
        });
    </script>
@endsection
