@extends('admin.index')
@section('title')
   Quản lý khen thưởng
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý khen thưởng</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Quản lý khen thưởng</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button><br/><br/>
                <div class="card card-info card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-1-tab" data-toggle="pill" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="false">
                                    <strong>Quản lý khen thưởng</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                                <table id="dataTable" class="display" style="width:100%">
                                    <thead>
                                    <tr class="bg-gradient-lightblue">
                                        <th>TT</th>
                                        <th>Ngày khen thưởng</th>
                                        <th>Phòng ban</th>
                                        <th>Nhân viên</th>
                                        <th>Nội dung</th>
                                        <th>Hình thức</th>
                                        <th>Bản cứng</th>                                        
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
    <!--  MEDAL -->
    <div>
        <!-- Medal Add -->
        <div class="modal fade" id="addModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Quản lý khen thưởng</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"> 
                        <form method="POST" enctype="multipart/form-data" id="addForm" autocomplete="off">
                            <div class="row class="form-group""> 
                                <div class="col-md-2">
                                    <label>Ngày</label>
                                    <select name="ngay" class="form-control">
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{$i}}" <?php if(Date('d') == $i) echo "selected";?>>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>                   
                                <div class="col-md-2">
                                    <label>Tháng</label>
                                    <select name="thang" class="form-control">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}" <?php if(Date('m') == $i) echo "selected";?>>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Năm</label>
                                    <select name="nam" class="form-control">
                                        @for($i = 2021; $i < 2100; $i++)
                                            <option value="{{$i}}" <?php if(Date('Y') == $i) echo "selected";?>>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>  <br/>
                            <div class="form-group">
                                <label>Phòng ban</label>
                                <select id="phongBan" name="phongBan" class="form-control">
                                    @foreach($phongban as $row)
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nhân viên</label>
                                <select id="nhanVien" name="nhanVien" class="form-control">

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <input type="text" name="noiDung" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Hình thức khen thưởng</label>
                                <input type="text" name="hinhThuc" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>File (Tệp) khen thưởng</label>
                                <input type="file" class="form-control" name="fileTaiLieu" placeholder="Choose File" id="file">
                                <span class="text-danger">{{ $errors->first('file') }}</span>
                                <span>Tối đa 20MB (png,jpg,PNG,JPG,doc,docx,pdf)</span>
                            </div>                           
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button id="btnAdd" class="btn btn-primary" form="addForm">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!----------------------->
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

        // Exe
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                // paging: false,    use to show all data
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: "{{ url('management/nhansu/ajax/loadkhenthuong/') }}",
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
                    { "data": "name" },
                    { "data": "surname" },
                    { "data": "noiDung" },
                    { "data": "hinhThuc" },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (row.url !== null)
                                return "<a href='upload/bienbankhenthuong/"+row.url+"' target='_blank'>Tải về</a>";
                            else 
                                return "Không có file";
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button id='delete' data-id='"+row.id+"' class='btn btn-danger btn-sm'><span class='fas fa-times-circle'></span></button>";
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

            //upload
            $(document).one('click','#btnAdd',function(e){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#addForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/nhansu/ajax/postkhenthuong/')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnAdd").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: 'info',
                                title: response.message
                            })
                            table.ajax.reload();
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            $("#addModal").modal('hide');
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Có lỗi tài liệu: ' + response.responseJSON.errors.fileTaiLieu
                            })
                            $("#btnAdd").attr('disabled', false).html("LƯU");
                            console.log(response);
                        }
                    });
                });                
            });
            //Delete data
            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "{{url('management/nhansu/ajax/delete/bienbankhenthuong')}}",
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
                        error: function() {
                            Toast.fire({
                                icon: 'warning',
                                title: "Không thể xóa lúc này!"
                            })
                        }
                    });
                }
            });

            $('#phongBan').on('change', function (e) {
                var optionSelected = $("option:selected", this);
                var valueSelected = this.value;
                $.ajax({
                    type: "post",
                    url: "{{route('loadnhanvienbbkt')}}",
                    dataType: "json",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "eid": valueSelected                           
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        }) 
                        $("#nhanVien").empty();
                        let dataVal = response.data;
                        for(let i = 0; i < dataVal.length; i++) {
                            $("#nhanVien").append("<option value='"+dataVal[i].id+"'>"+dataVal[i].surname+"</option>");
                        }  
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'warning',
                            title: " Không tìm thấy hạng mục nào!"
                        })                       
                    }
                });  
            });
        });        
    </script>
@endsection
