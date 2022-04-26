<?php

session_start();
if(isset($_SESSION["authenticate"]) && isset($_SESSION["name"]) && isset($_SESSION["sid"]) && isset($_SESSION["grade"]) && isset($_SESSION["exam_now"]) && isset($_SESSION["exam_sub"])){
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
    <link rel="stylesheet" href="/lib/css/exam.css">
    <title>Exam Now !!</title>
</head>
<body>
    <section class="float-bar">
        <div class="prev-btn abtn">Back</div>
        <div class="stu-info">
            <div class="stu-name"<?= $_SESSION["name"]; ?>></div>
            <div class="stu-exam"><?= $_SESSION["exam_sub"]; ?></div>
        </div>
        <div class="next-btn abtn">Next</div>
    </section>
    <section class="exam">
        <div class="quest">
            <div class="qcon">

            </div>
            <div class="submit">SUBMIT</div>
        </div>
        <div class="conq">
            <div class="q-num">
            </div>
        </div>
    </section>
</body>
</html>
<script>

const qnum = document.querySelector(".q-num");

var data = JSON.parse(localStorage.getItem("q"));
var pdata = [];
let index = 0;
var counter = 0;

for(var i in data){
    pdata.push(i);
    qnum.innerHTML += `<div class="qn" value="`+i+`">`+(++counter)+`</div>`;
}

function setAnswered(){
    var d = JSON.parse(localStorage.getItem("a"));
    document.querySelectorAll(".qn").forEach(i => {
        if(d[i.getAttribute("value")] != ""){
            i.className += " qn-active";
        }
    })
}

function setQuest(e,answer=""){
    const quest = document.querySelector(".qcon");
    const cho = quest.querySelectorAll("#choice");
    var ld = data[e];
    var ans = JSON.parse(localStorage.getItem("a"));

    if(document.querySelector("#qid") != null){
        cho.forEach(i => {
            if(i.checked){
                ans[document.querySelector("#qid").value] = i.value;
            }
        })
        localStorage.setItem("a",JSON.stringify(ans));
    }

    quest.innerHTML = `<input type="hidden" id="qid" value="`+e+`">
            <div class="part">
                <div class="num">`+ld.num+`.</div>
                <div class="q">`+ld.q+`</div>
            </div>
            <div class="options">
                <div class="opt">
                    <input type="radio" name="ans"  `+ (answer == "a" ? "checked=\"checked\"" : "") +` id="choice" value="a">
                    <p>`+ld.a+`</p>
                </div>
                <div class="opt">
                    <input type="radio" name="ans" `+ (answer == "b" ? "checked=\"checked\"" : "") +` id="choice" value="b">
                    <p>`+ld.b+`</p>
                </div>
                <div class="opt">
                    <input type="radio" name="ans" `+ (answer == "c" ? "checked=\"checked\"" : "") +` id="choice" value="c">
                    <p>`+ld.c+`</p>
                </div>
                <div class="opt">
                    <input type="radio" name="ans" `+ (answer == "d" ? "checked=\"checked\"" : "") +` id="choice" value="d">
                    <p>`+ld.d+`</p>
                </div>
                <div class="opt">
                    <input type="radio" name="ans" `+ (answer == "e" ? "checked=\"checked\"" : "") +` id="choice" value="e">
                    <p>`+ld.e+`</p>
                </div>
            </div>`;

    setAnswered();
}

setQuest(pdata[index]);

document.querySelector(".prev-btn").addEventListener("click",() => {
    if(index > 0){
        index--;
    }
    var obj = JSON.parse(localStorage.getItem("a"));
    setQuest(pdata[index],obj[pdata[index]]);
})

document.querySelector(".next-btn").addEventListener("click",() => {
    if(index < pdata.length - 1){
        index++;
    }
    var obj = JSON.parse(localStorage.getItem("a"));
    setQuest(pdata[index],obj[pdata[index]]);
})


document.querySelectorAll(".qn").forEach((i,node) => {
    i.addEventListener("click",() => {
        index = node;
        var obj = JSON.parse(localStorage.getItem("a"));
        setQuest(pdata[index],obj[pdata[index]]);
    })
})

document.querySelector(".submit").addEventListener("click",() => {
    var req = new ReqJS("/api/exam/submit/","POST");
    req.setData([
        ["ans",localStorage.getItem("a")]
    ])
    req.send(r => {
        if(r == "true"){
            location.href = "/home/";
        }else{
            alert("Pls try submit again")
        }
    })
})

</script>