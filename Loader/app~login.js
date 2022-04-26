var req = new ReqJS("/pages/login.php","GET");
req.send(r => document.write("<style>@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap');</style>"+r));