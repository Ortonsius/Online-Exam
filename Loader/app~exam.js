var gets = new ReqJS("/api/exam/question/","GET");
gets.send(r => {
    if(r == ""){
        location.href = "/home/";
    }else{
        let ans = {};
        localStorage.setItem("q",r);
        for(var k in JSON.parse(r)){
            ans[k] = "";
        }

        localStorage.setItem("a",JSON.stringify(ans));
    }
})

var req = new ReqJS("/pages/exam.php","GET");
req.send(r => document.write("<style>@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap');</style>"+r));