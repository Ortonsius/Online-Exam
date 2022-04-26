var scr = new ReqJS("/api/exam/score/","GET");
scr.send(r => {
    localStorage.setItem("score",r);
});

var sch = new ReqJS("/api/exam/schedule/","GET");
sch.send(r => {
    localStorage.setItem("schedule",r);
});

var req = new ReqJS("/pages/home.php","GET");
req.send(r => document.write("<style>@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap');</style>"+r));