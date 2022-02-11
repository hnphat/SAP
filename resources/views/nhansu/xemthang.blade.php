<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem chấm công tháng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">  
        <div class="col-md-2">
            <label>Tháng</label>
            <select name="thang" class="form-control" disabled>
                @for($i = 1; $i <= 12; $i++)
                    @if($thang == $i)
                        <option value="{{$i}}" selected>{{$i}}</option>
                    @else
                        <option value="{{$i}}">{{$i}}</option>
                    @endif
                    
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label>Năm</label>
            <select name="nam" class="form-control" disabled>
                @for($i = 2021; $i < 2100; $i++)
                    @if($nam == $i)
                        <option value="{{$i}}" selected>{{$i}}</option>
                    @else
                        <option value="{{$i}}">{{$i}}</option>
                    @endif
                @endfor
            </select>
        </div>
        <div class="col-md-1">
            <label>&nbsp;</label><br/>
            <button id="xemThang" type="button "class="btn btn-xs btn-warning">Xem</button>
        </div>
    </div>  
    <p id="loading" style="text-align:center;display:none;">
        <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width: 100px; height:auto;"/>
    </p>
    <div>
        <br/>
        <div id="noiDung">

        </div>
    </div>
</div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
    $(document).ready(function(){
        $("#xemThang").click(function(){
                $.ajax({
                    url: "{{url('management/nhansu/tonghop/ajax/getthang')}}",
                    type: "get",
                    dataType: "text",
                    data: {
                        "thang": $("select[name=thang]").val(),
                        "nam": $("select[name=nam]").val()
                    },
                    beforeSend: function() {
                        $("#loading").show();
                    },
                    success: function(response){                        
                        $("#noiDung").html(response);                                   
                    },
                    error: function(){
                        alert('Lỗi');
                    },
                    complete: function() {
                        $("#loading").hide();
                    }
                });
           }); 
    });
</script>
</body>
</html>