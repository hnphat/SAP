@extends('admin.index')
@section('title')
    Quản lý lương
@endsection
@section('script_head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><strong>Quản lý lương</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Nhân sự</li>
                            <li class="breadcrumb-item active">Lương -> Quản lý</li>
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
                                    <strong>Quản lý lương</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                            <form id="importForm" autocomplete="off" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="card-body">    
                                    <div class="form-group">
                                        <label>File mẫu</label>
                                        <a href="{{asset('store/LUONG.xlsx')}}" download>TẢI VỀ</a>
                                    </div>                                                                
                                    <div class="form-group">
                                        <label>Tháng</label>
                                        <select name="thang" class="form-control">
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{$i}}" <?php if(Date('m') == $i) echo "selected";?>>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Năm</label>
                                        <select name="nam" class="form-control">
                                            @for($i = 2021; $i < 2100; $i++)
                                                <option value="{{$i}}" <?php if(Date('Y') == $i) echo "selected";?>>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tệp</label>
                                        <input type="file" class="form-control" name="fileBase" id="fileBase">
                                        <span>Tối đa 10MB (xls, xlsx)</span>
                                    </div>                                                                   
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button id="btnImport" class="btn btn-primary">Import</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('script')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

       $(document).ready(function(){
            //upload
            $(document).one('click','#btnImport',function(e){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#importForm').submit(function(e) {
                    e.preventDefault();   
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: "{{ url('management/nhansu/ajax/importfileluong/')}}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $("#btnImport").attr('disabled', true).html("Đang xử lý....");
                        },
                        success: (response) => {
                            this.reset();
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            });
                            $("#btnImport").attr('disabled', false).html("IMPORT");                      
                        },
                            error: function(response){
                            Toast.fire({
                                icon: 'info',
                                title: ' Có lỗi: ' + response.responseJSON.errors.fileBase
                            })
                            $("#btnImport").attr('disabled', false).html("IMPORT");  
                            console.log(response);
                        }
                    });
                });                
            });
       });
    </script>
@endsection
