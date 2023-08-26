@extends('admin.index')
@section('title')
    Khách hàng MKT
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
                        <h1 class="m-0"><strong>Khách hàng MKT</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kinh doanh</li>
                            <li class="breadcrumb-item active">Khách hàng MKT</li>
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
                                    <strong>Khách hàng MKT</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('cskh') ||
                            \Illuminate\Support\Facades\Auth::user()->hasRole('boss'))
                            <button id="pressAdd" class="btn btn-success" data-toggle="modal" data-target="#addModal"><span class="fas fa-plus-circle"></span></button>
                            @endif
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Sale nhận khách</label>
                                                <select name="nhanVien" class="form-control"> 
                                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('system') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('tpkd') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('boss') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('adminsale') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('mkt') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('cskh') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('hcns') ||
                                                    \Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))
                                                    <option value="0">Tất cả</option>                                                               
                                                    @foreach($groupsale as $row)    
                                                        @if (!\Illuminate\Support\Facades\Auth::user()->hasRole('truongnhomsale'))                                                    
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @elseif ($row['group'] == $groupid)
                                                        <option value="{{$row['id']}}">{{$row['code']}} - {{$row['name']}}</option>
                                                        @else
                                                        
                                                        @endif 
                                                    @endforeach   
                                                @else   
                                                    <option value="{{$iduser}}">{{$nameuser}}</option>
                                                @endif
                                                </select> <br/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br/>
                                                <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div style="overflow: auto;">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="table-light">STT</th>
                                            <th class="table-light">Ngày tạo</th>
                                            <th class="table-light">Người tạo</th>
                                            <th class="table-light">Tên khách</th>
                                            <th class="table-light">Điện thoại</th>
                                            <th class="table-light">Nguồn</th>
                                            <th class="table-light">Yêu cầu</th>
                                            <th class="table-success">Nhóm nhận</th>
                                            <th class="table-success">Sale nhận</th>
                                            <th class="table-success">Ngày nhận</th>
                                            <th class="table-success">Đánh giá</th>
                                            <th class="table-success">Xe quan tâm</th>
                                            <th class="table-success">CS1 (24h)</th>
                                            <th class="table-success">CS2 (3-5 ngày)</th>
                                            <th class="table-success">CS3 (7-10 ngày)</th>
                                            <th class="table-success">CS4</th>
                                            <th class="table-success">Trạng thái</th>
                                            <th class="table-light">Tác vụ</th>
                                        </tr>
                                        </thead>
                                        <tbody id="all">
                                        </tbody>                                         
                                    </table>
                              </div>
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
                            <h3 class="card-title">THÊM KHÁCH HÀNG</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="addForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col">
                                    <div class="card-body">                                        
                                        <div class="form-group">
                                            <label>Tên khách hàng <span class="text-red">(*)</span></label>
                                            <input name="ten" type="text" class="form-control" placeholder="Tên khách hàng">
                                        </div>
                                        <div class="form-group">
                                            <label>Số điện thoại <span class="text-red">(*)</span></label>
                                            <input name="dienThoai" type="number" class="form-control" placeholder="10 số VD: 0989990112 hoặc 2963989922">
                                        </div>
                                        <div class="form-group">
                                            <label>Nguồn khách <span class="text-red">(*)</span></label>
                                            <select name="nguonKH" class="form-control">
                                                <option value="Hotline">Hotline</option>
                                                <option value="Zalo">Zalo</option>
                                                <option value="Facebook_ads">Facebook_ads</option>
                                                <option value="Google_ads">Google_ads</option>
                                                <option value="Website">Website</option>
                                                <option value="Email">Email</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Yêu cầu <span class="text-red">(*)</span></label>
                                            <input name="yeuCau" type="text" class="form-control" placeholder="Yêu cầu khách hàng">
                                        </div>
                                        <div class="form-group">
                                            <label>Chuyển khách cho nhóm</label>
                                            <select name="chonNhom" class="form-control">
                                               @foreach($group as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>                                                             
                            </div>                               
                            <div class="card-footer">
                                <button id="btnAdd" class="btn btn-success">Thêm</button>
                                <!-- <button id="btnAddNew" type="button" class="btn btn-info">Thêm và tạo mới</button> -->
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

    <!-- Medal Gán Nhóm -->
     <div class="modal fade" id="groupModal">
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
                            <h3 class="card-title">Chuyển khách hàng sang nhóm</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="groupForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="eid"/>                                                                        
                                    <div class="card-body"> 
                                        <div class="form-group">
                                            <h5>Khách hàng:  <span id="sten"></span></h5>
                                            <h5>Số điện thoại:  <span id="sphone"></span></h5>
                                        </div>                                       
                                        <div class="form-group">
                                            <label>Chuyển khách cho nhóm</label>
                                            <select name="chonNhoms" class="form-control">
                                               @foreach($group as $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                               @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>                                                             
                            </div>                               
                            <div class="card-footer">
                                <button id="btnSetGroup" class="btn btn-success">Xác nhận</button>
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

    <!-- Medal Gán Sale -->
    <div class="modal fade" id="saleModal">
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
                            <h3 class="card-title">Gán khách hàng cho sale</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="saleForm" autocomplete="off">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="eeid"/>                                                                        
                                    <div class="card-body"> 
                                        <div class="form-group">
                                            <h5>Khách hàng:  <span id="ssten"></span></h5>
                                            <h5>Số điện thoại:  <span id="ssphone"></span></h5>
                                        </div>                                       
                                        <div class="form-group">
                                            <label>Chuyển khách cho sale: </label>
                                            <select name="chonSale" class="form-control" id="chonSale">
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>                                                             
                            </div>                               
                            <div class="card-footer">
                                <button id="btnSetSale" class="btn btn-success">Xác nhận</button>
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
        function autoload() {            
            $.ajax({
                type: "post",
                url: "{{url('management/marketing/loadbaocao/')}}",
                dataType: "text",
                data: {
                    "_token": "{{csrf_token()}}",
                    "sale": $("select[name=nhanVien]").val(),
                    "tu": $("input[name=chonNgayOne]").val(),
                    "den": $("input[name=chonNgayTwo").val()
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'info',
                        title: " Loaded! "
                    })
                    $("#all").html(response);
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
            });
        }

        $("#xemReport").click(function(){
            autoload();
        });

        $("#btnAdd").click(function(e){
                e.preventDefault();
                let flag = true;
                if (!$("input[name=ten]").val()) 
                    alert("Bạn chưa nhập họ tên khách");
                else if (!$("input[name=dienThoai]").val()) 
                    alert("Bạn chưa nhập số điện thoại");
                else if (!$("input[name=yeuCau]").val()) 
                    alert("Bạn chưa nhập yêu cầu khách hàng");
                else if ($("input[name=dienThoai]").val().match(/\d/g).length===10) {
                    // Xử lý trùng số điện thoại
                    $.ajax({
                       url: "management/guest/check/" + $("input[name=dienThoai]").val().replace(',','').replace('.',''),
                       dataType: "text",
                       success: function(responce) {
                           let obj = JSON.parse(responce);
                           if (parseInt(obj.check) === 1) {
                               flag = false;
                               alert('Số điện thoại ' + obj.phone + ' đã được tạo bởi ' + obj.user);
                           }
                       },
                       async: false
                    });
                    //-------------------
                    if (flag) {
                        if (confirm('Xác nhận chuyển khách hàng cho nhóm này\nLưu ý: Vui lòng kiểm tra kỹ thông tin, khách hàng sẽ bị khoá sau khi chuyển cho nhóm!')) {
                            $.ajax({
                                url: "management/marketing/postdata",
                                type: "POST",
                                dataType: "json",
                                data: $("#addForm").serialize(),
                                beforeSend: function () {
                                    $("#btnAdd").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                                },
                                success: function(response) {
                                    Toast.fire({
                                        icon: response.type,
                                        title: response.message
                                    })
                                    $("#addForm")[0].reset();
                                    $("#addModal").modal('hide');
                                    $("#btnAdd").attr('disabled', false).html("Thêm");
                                    autoload();
                                },
                                error: function(e) {
                                    $("#btnAdd").attr('disabled', false).html("Thêm");
                                    Toast.fire({
                                        icon: 'error',
                                        title: e.responseJSON.message
                                    })
                                }
                            });
                        }
                        
                    }                   
                } else {
                    alert("Số điện thoại không đúng định dạng!");
                }         
            });

            $(document).on('click','#delete', function(){
                if(confirm('Bạn có chắc muốn xóa?')) {
                    $.ajax({
                        url: "management/marketing/deleteguest",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code != 500)
                                setTimeout(autoload, 1000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }
            });

            $(document).on('click','#revert', function(){
                if(confirm('Xác nhận rollback về trạng thái khách chưa gán cho nhóm (cho sale)?')) {
                    $.ajax({
                        url: "management/marketing/revertguest",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code != 500)
                                setTimeout(autoload, 3000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }
            });

            $(document).on('click','#setGroup', function(){
                $("#sten").text($(this).data('hoten'));
                $("#sphone").text($(this).data('phone'));
                $("input[name=eid]").val($(this).data('id'));
            });

            $(document).on('click','#setSale', function(){
                $("#ssten").text($(this).data('hoten'));
                $("#ssphone").text($(this).data('phone').slice(0, 4) + "xxxxxx");
                $("input[name=eeid]").val($(this).data('id'));
                $("#chonSale").empty();
                    $.ajax({
                        url: "management/marketing/getsalelist",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "idgroup": $(this).data('groupid')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })            
                            if (response.code == 200) {                                
                                let dataVal = response.data;
                                for(let i = 0; i < dataVal.length; i++) {
                                    $("#chonSale").append("<option value='"+dataVal[i].idsale+"'>"+dataVal[i].hoten+"</option>");
                                }      
                            }                
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
            });
            
            $("#btnSetGroup").click(function(e){
                e.preventDefault();
                if(confirm('Xác nhận chuyển khách hàng cho nhóm này?\nLưu ý: Sau khi chuyển sẽ khách sẽ bị khoá và không thể thu hồi lại.')) {
                    $.ajax({
                        url: "management/marketing/setgroup",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "id": $("input[name=eid]").val(),
                            "id_group": $("select[name=chonNhoms]").val()
                        },
                        beforeSend: function () {
                            $("#btnSetGroup").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("#btnSetGroup").attr('disabled', false).html("Xác nhận");
                            $("#groupModal").modal('hide');
                            setTimeout(autoload, 1000);;
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }               
            });


            $("#btnSetSale").click(function(e){
                e.preventDefault();
                if(confirm('Xác nhận chuyển khách hàng cho sale này?\nLưu ý: Sau khi chuyển sẽ không thể thu hồi lại.')) {
                    $.ajax({
                        url: "management/marketing/setsale",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "id": $("input[name=eeid]").val(),
                            "id_sale": $("select[name=chonSale]").val()
                        },
                        beforeSend: function () {
                            $("#btnSetSale").attr('disabled', true).html("Đang xử lý vui lòng đợi....");
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            $("#btnSetSale").attr('disabled', false).html("Xác nhận");
                            $("#saleModal").modal('hide');
                            setTimeout(autoload, 2000);;
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }               
            });

            $(document).on('click','#setfail', function(){
                if(confirm('Xác nhận chuyển đổi trạng thái khách từ \"Có nhu cầu\" sang trạng thái \"Không nhu cầu\"?')) {
                    $.ajax({
                        url: "management/marketing/setfail",
                        type: "POST",
                        dataType: "json",
                        data: {
                            "_token": '{{csrf_token()}}',
                            "id": $(this).data('id')
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                            if (response.code != 500)
                                setTimeout(autoload, 3000);
                        },
                        error: function() {
                            Toast.fire({
                                icon: response.type,
                                title: response.message
                            })
                        }
                    });
                }
            });
       });
    </script>
@endsection
