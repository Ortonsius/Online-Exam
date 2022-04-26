<?php
session_start();
if(isset($_SESSION["authenticate"]) && isset($_SESSION["name"]) && isset($_SESSION["sid"]) && isset($_SESSION["grade"])){
    if(!$_SESSION["authenticate"]){
        echo "<script>location.href = '/'</script>";
        exit();
    }
}else{
    echo "<script>location.href = '/'</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/lib/css/home.css">
    <title>Home</title>
</head>
<body>
    <input type="hidden" id="sid" value="<?= $_SESSION["sid"]; ?>">
    <section class="infobar">
        <div class="student-info"><?= htmlspecialchars($_SESSION["name"]); ?> - <?= htmlspecialchars($_SESSION["grade"]); ?></div>
        <div class="logout-btn" onclick="location.href = '/api/logout/';">LOGOUT</div>
    </section>
    <section class="exam">
        <p class="title">EXAM:</p>
        <div class="exam-box">

        </div>
    </section>
    
    <section class="score">
    <div class="title">SCORE</div>
        <div class="box">
        </div>
    </section>
</body>
</html>
<script>

const eb = document.querySelector(".exam-box");
var data = JSON.parse(localStorage.getItem("schedule"));
for(var k in data){
    var payload = ``;
    var current = new Date();
    var t = data[k][1].split(":");
    if(parseInt(t[0]) < parseInt(current.getHours())){
        payload = `<div class="start-exam" id="start-subject" onclick="
        var req = new ReqJS('/api/exam/do/','POST');
        req.setData([
            ['subject','`+k+`']
        ])
        req.send(r => {
            if(r == 'true'){
                location.href = '/exam/';
            }
        })
    ">START</div>`;
    }else if(parseInt(t[0]) == parseInt(current.getHours())){
        if(parseInt(t[1]) <= parseInt(current.getMinutes())){
            payload = `<div class="start-exam" id="start-subject" onclick="
        var req = new ReqJS('/api/exam/do/','POST');
        req.setData([
            ['subject','`+k+`']
        ])
        req.send(r => {
            if(r == 'true'){
                location.href = '/exam/';
            }
        })
    ">START</div>`;
        }
    }
    eb.innerHTML += `<div class="subject">
                <input type="hidden" id="" value="`+k+`">
                <div class="subname">`+data[k][3]+`</div>
                <div class="prop-name"><div>Time</div>: `+data[k][1]+`</div>
                <div class="prop-name"><div>Date</div>: `+data[k][2]+`</div>
                <div class="prop-name"><div>Duration</div>: `+data[k][0]+` Minutes</div>
                `+payload+`
            </div>`;
}


const box = document.querySelector(".box");
var dsc = JSON.parse(localStorage.getItem("score"));
for(var k in dsc){
    box.innerHTML += `<div class="bar"><div class="prop-names"><div>`+k+`</div>: `+dsc[k]+`</div></div>`;
}

</script>