<?php
    echo $demo;
?>
<form action="{{route("login")}}" method="post" enctype="multipart/form-data">
    @csrf
    Name: <input type="text" name="_name" /> <br>
    Pass: <input type="password" name="_password" />
    <input type="submit" value="send" />
</form>
