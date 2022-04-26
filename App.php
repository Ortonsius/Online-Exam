<?php

include_once 'Lib/php/router.php';
include_once 'Lib/php/DB.php';

session_start();

// PAGE ROUTING
Route::get("/","loader/app~login.js");
Route::get("/home/","loader/app~home.js");
Route::get("/exam/","loader/app~exam.js");





// API ROUTING
ApiRoute::post("/api/login/",function($res){
    $un = isset($_POST["usr"]) ? htmlspecialchars($_POST["usr"]) : false;
    $pw = isset($_POST["pwd"]) ? htmlspecialchars($_POST["pwd"]) : false;

    $r = DB::query("SELECT * FROM student WHERE name = :n",[":n" => $un]);
    if($r->rowCount() === 1){
        $data = $r->fetch();
        if($pw === $data["password"]){
            session_regenerate_id();
            $_SESSION["authenticate"] = true;
            $_SESSION["name"] = $data["name"];
            $_SESSION["grade"] = $data["grade"];
            $_SESSION["sid"] = $data["sid"];
            $res["status"] = "true";
        }else{
            $res["status"] = "false";
        }
    }else{
        $res["status"] = "false";
    }
    echo json_encode($res);
});

ApiRoute::get("/api/logout/",function($res){
    if(isset($_SESSION["authenticate"])) unset($_SESSION["authenticate"]);
    if(isset($_SESSION["name"])) unset($_SESSION["name"]);
    if(isset($_SESSION["grade"])) unset($_SESSION["grade"]);
    if(isset($_SESSION["sid"])) unset($_SESSION["sid"]);
    if(isset($_SESSION["exam_now"])) unset($_SESSION["exam_now"]);
    if(isset($_SESSION["exam_sub"])) unset($_SESSION["exam_sub"]);
    header("Location: /");
});

ApiRoute::get("/api/exam/schedule/",function($res){
    if(isset($_SESSION["grade"])){
        $cd = date("d M Y");
        $r = DB::query("SELECT * FROM exam WHERE grade = :gr AND date = :d",[":gr" => $_SESSION["grade"],":d" => $cd]);
        $data = $r->fetchAll($mode=PDO::FETCH_ASSOC);
        $nd = [];
        
        $done = [];
        $s = DB::query("SELECT * FROM score WHERE sid = :si",[":si" => $_SESSION["sid"]]);
        $sd = $s->fetchAll($mode=PDO::FETCH_ASSOC);
        foreach($sd as $i){
            array_push($done,$i["eid"]);
        }

        foreach($data as $i){
            if(!in_array($i["eid"],$done)){
                $nd[$i["eid"]] = [$i["duration"],$i["time"],$i["date"],$i["subject"]];
            }
        }
        echo json_encode($nd);
    }else{
        http_response_code(404);
    }
});

ApiRoute::get("/api/exam/score/",function($res){
    if(isset($_SESSION["sid"])){
        $r = DB::query("SELECT * FROM score WHERE sid = :si",[":si" => $_SESSION["sid"]]);
        $data = $r->fetchAll($mode=PDO::FETCH_ASSOC);
        $nd = [];
        foreach($data as $i){
            $s = DB::query("SELECT subject FROM exam WHERE eid = :e",[":e" => $i["eid"]]);
            if($s->rowCount() == 1){
                $sj = $s->fetch();
                $nd[$sj["subject"]] = $i["score"];
            }
        }
        echo json_encode($nd);
    }else{
        http_response_code(404);
    }
});

ApiRoute::post("/api/exam/do/",function($res){
    $subject = isset($_POST["subject"]) ? htmlspecialchars($_POST["subject"]) : false;
    if($subject != false){
        if(!isset($_SESSION["exam_now"])){
            $s = DB::query("SELECT * FROM exam WHERE eid = :e",[":e" => $subject]);
            if($s->rowCount() == 1){
                $data = $s->fetch();
                $_SESSION["exam_sub"] = $data["subject"];
                $_SESSION["exam_now"] = $data["eid"];
                echo "true";
            }else{
                echo "false";
            }
        }else{
            echo "false";
        }
    }else{
        echo "false";
    }
});

ApiRoute::get("/api/exam/question/",function($res){
    if(isset($_SESSION["exam_now"])){
        $s = DB::query("SELECT * FROM question WHERE eid = :e",[":e" => $_SESSION["exam_now"]]);
        if($s->rowCount() === 1){
            $data = $s->fetchAll($mode=PDO::FETCH_ASSOC);
            echo $data[0]["quest"];
        }
    }
});

ApiRoute::post("/api/exam/submit/",function($res){
    if(isset($_SESSION["exam_now"]) && isset($_SESSION["sid"])){
        $ans = isset($_POST["ans"]) ? $_POST["ans"] : false;
        if($ans != false){
            $s = DB::query("SELECT * FROM question WHERE eid = :e",[":e" => $_SESSION["exam_now"]]);
            if($s->rowCount() === 1){
                $rans = json_decode($s->fetch()["answer"],true);
                $ans = json_decode($ans,true);

                $qlen = count($rans);
                $correct = 0;
                
                foreach($rans as $k => $_){
                    if(isset($ans[$k])){
                        if($ans[$k] === $rans[$k]){
                            $correct++;
                        }
                    }
                }

                $score = ($correct / $qlen) * 100;
                DB::query("INSERT INTO score VALUES (:si,:ei,:scr,:sa)",[":si" => $_SESSION["sid"],":ei" => $_SESSION["exam_now"],":scr" => $score,":sa" => json_encode($ans)]);

                unset($_SESSION["exam_now"]);
                unset($_SESSION["exam_sub"]);

                echo "true";
            }
        }
    }
});

http_response_code(404);

?>