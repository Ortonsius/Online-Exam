<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Exam</title>
    <link rel="stylesheet" href="lib/css/login.css">
</head>
<body>
    <section class="login">
        <input type="text" id="user" class="un" placeholder="Name" required autocomplete="off">
        <input type="password" id="pwd" class="pw" placeholder="Password" required autocomplete="off">
        <button id="login-submit" class="btn">LOGIN</button>
        <p style="color: red; font-size: 14px;" id="err-msg"></p>
    </section>
</body>
</html>
<script>

document.querySelector("#login-submit").addEventListener("click",() => {
    var req = new ReqJS("/api/login/","POST");
    req.setData([
        ["usr",document.querySelector("#user").value],
        ["pwd",document.querySelector("#pwd").value]
    ])

    req.send(r => {
        var data = JSON.parse(r);
        if(data.status == "false"){
            document.querySelector("#err-msg").innerHTML = "Invalid username/password";
        }else{
            location.href = "/home/";
        }
    })
})

</script>
<?php
?>