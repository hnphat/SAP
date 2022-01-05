@extends('admin.index')
@section('title')
    Báo cáo kho
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
                        <h1 class="m-0"><strong>Báo cáo</strong></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Kho xe</li>
                            <li class="breadcrumb-item active">Báo cáo</li>
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
                                    <strong>Báo cáo</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="tab-1-tab">
                              <form>
                                <div class="card-body row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Chọn loại báo cáo</label>
                                                <select name="chonBaoCao" class="form-control">
                                                    <option value="ALL">Tất cả (số liệu)</option>
                                                    <option value="PO">P/O</option>
                                                    <option value="MAP">MAP</option>
                                                    <option value="ORDER">Đặt hàng</option>
                                                    <option value="STORE">Tồn kho</option>
                                                    <option value="COMPLETE">Xuất xe</option>
                                                    <option value="HD">Hợp đồng ký</option>
                                                    <option value="HDWAIT">Hợp đồng ký chờ</option>
                                                    <option value="HDCANCEL">Hợp đồng hủy</option>
                                                </select> <br/>
                                                <button id="xemReport" type="button" class="btn btn-info btn-xs">XEM</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Từ</label>
                                                <input type="date" name="chonNgayOne" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Đến</label>
                                                <input type="date" name="chonNgayTwo" value="<?php echo Date('Y-m-d');?>" class="form-control">
                                            </div>
                                        </div>
                                </div>
                              </form>
                              <hr/>
                              <div id="all">
                                  
                              </div>
                              <div id="po">
                                  
                              </div>
                              <div id="map">
                                  
                              </div>
                              <div id="order">
                                  
                              </div>
                              <div id="store">
                                  
                              </div>
                              <div id="complete">
                                  
                              </div>
                              <div id="hdky">
                                  
                              </div>
                              <div id="hdcho">
                                  
                              </div>
                              <div id="hdhuy">
                                  
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
         $("#xemReport").click(function(){
            $.ajax({
                type: "get",
                url: "{{url('management/kho/getreportkho/')}}" + "/" + $("select[name=chonBaoCao]").val() +
                "/ngayfrom/" + $("input[name=chonNgayOne]").val() + "/ngayto/" + $("input[name=chonNgayTwo").val(),
                dataType: "text",
                success: function(response) {
                    Toast.fire({
                        icon: 'info',
                        title: " Đã gửi yêu cầu! "
                    })
                    if ($("select[name=chonBaoCao]").val() == "ALL") {
                        $("#all").html(response);
                        $("#po").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }
                        
                    if ($("select[name=chonBaoCao]").val() == "PO") {
                        $("#all").html(response);
                        $("#po").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "MAP") {
                        $("#map").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "ORDER") {
                        $("#order").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "STORE") {
                        $("#store").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "COMPLETE") {
                        $("#complete").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "HD") {
                        $("#hdky").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "HDWAIT") {
                        $("#hdcho").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#hdky").html("<span></span>");
                        $("#hdhuy").html("<span></span>");
                    }

                    if ($("select[name=chonBaoCao]").val() == "HDCANCEL") {
                        $("#hdhuy").html(response);
                        $("#po").html("<span></span>");
                        $("#all").html("<span></span>");
                        $("#map").html("<span></span>");
                        $("#store").html("<span></span>");
                        $("#complete").html("<span></span>");
                        $("#order").html("<span></span>");
                        $("#hdcho").html("<span></span>");
                        $("#hdky").html("<span></span>");
                    }
                        
                },
                error: function() {
                    Toast.fire({
                        icon: 'warning',
                        title: " Lỗi!"
                    })
                }
            });
         });  
       });
    </script>
@endsection
