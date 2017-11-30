<?php
    $sql = "SELECT * FROM `".MYSQL_TABLE_ASSETS."` WHERE `id`='".$_GET['id']."'";
    $req = @mysql_query($sql) or sqldie($sql);  ;
    $row = @mysql_fetch_assoc($req);
    if (! $row) {
        echo("error");
        exit();
    }
    $url = $GLOBALS['CONFIG']['base_url']."/?id=".$row['id'];
    $json = json_decode($row['data']);

    $getTest = "";
    $getTest .= "fetch('$url',\n";
    $getTest .= "  { method: 'GET' })\n";
    $getTest .= "  .then(function(res) {\n";
    $getTest .= "     if (!res.ok) throw Error(res.statusText);\n";
    $getTest .= "     return res.json();\n";
    $getTest .= "  })\n";
    $getTest .= "  .then(function(data){\n";
    $getTest .= "     data = JSON.parse(data);\n";
    $getTest .= "     alert('Success'); console.log(data)\n";
    $getTest .= "  })\n";
    $getTest .= "  .catch(function(e){ alert(e); console.error(e) })\n";

    $postTest = "";
    $postTest .= "fetch('$url',\n";
    $postTest .= "  { method: 'POST', body: data })\n";
    $postTest .= "  .then(function(res) {\n";
    $postTest .= "        if (!res.ok) throw Error(res.statusText);\n";
    $postTest .= "        return res.json();\n";
    $postTest .= "  })\n";
    $postTest .= "  .then(function(data){\n";
    $postTest .= "     data = JSON.parse(data);\n";
    $postTest .= "     alert('Success'); console.log(data)\n";
    $postTest .= "  })\n";
    $postTest .= "  .catch(function(e){ alert(e); console.error(e) })\n";

    function code2html($code) {
        $str = htmlentities($code);
        $str = str_replace(" ","&nbsp", $str);
        $str = nl2br($str);
        $str = preg_replace("/(\'.*\')/","<spam class='string'>$1</spam>", $str);
        $str = preg_replace("/(fetch)/","<spam class='method'>$1</spam>", $str);
        $str = preg_replace("/(then)/","<spam class='method'>$1</spam>", $str);
        $str = preg_replace("/(catch)/","<spam class='method'>$1</spam>", $str);
        $str = preg_replace("/(function)/","<spam class='keyword'>$1</spam>", $str);
        $str = preg_replace("/(alert)/","<spam class='dbgmethod'>$1</spam>", $str);
        $str = preg_replace("/(console)/","<spam class='dbgmethod'>$1</spam>", $str);
        $str = preg_replace("/(if)/","<spam class='keyword'>$1</spam>", $str);
        $str = preg_replace("/(return)/","<spam class='return'>$1</spam>", $str);
        $str = preg_replace("/(throw)/","<spam class='return'>$1</spam>", $str);
        return $str;
    }
?>



<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            background-color: #2b2929;
        }
        form {
            margin: 0;
        }
        .time {
            color: #7d7b7b;
            text-align: right;
        }
        #jsonError {
            position: absolute;
            top: 0;
            left: 0;
            width: calc(100% - 40px);
            overflow: hidden;
            line-height: 3em;
            padding-left: 20px;
            padding-right: 20px;
            color: #ffe500;
            background-color: #a93d3d;
        }
        textarea {
            height: 200px;
            display: block;
            width: calc(100% - 20px);
            padding: 20px;
            background-color: #212020;
            color: #bdbbbb;
            font-size: 1.3em;
            border-radius: 5px;
            margin: 10px;
            border: 0;
        }
        code {
            display: block;
            width: calc(100% - 60px);
            padding: 20px;
            background-color: #212020;
            color: #bdbbbb;
            font-size: 1.3em;
            border-radius: 5px;
            margin: 10px;
        }
        button {
            position: relative;
            right: -20px;
            top: -40px;
        }
        .method {
            color: #d7ae35;
        }
        .string {
            color: #00ba08;
        }
        .keyword {
            color: #9d7809;
        }
        .return {
            color: #ca4040
        }
        .dbgmethod {
            color: #3bb6c6;
        }
    </style>
</head>
<body>
    <div class='time'>creation: <?php echo $row['creation'] ?>, lastChange: <?php echo $row['lastChange'] ?></div>
    <br/>
    
    <form id="myForm" action='index.php?action=<?php echo ACTION_UPDATE.'&id='.$row['id'] ?>' method='post'>
        <div id='jsonError'></div>
        <textarea name='data'></textarea>
    </form>

    <script>
        var str = '<?php echo json_encode($json)?>';
        var json = JSON.parse(str);
        var form = document.getElementById("myForm");
        var textarea = form.elements["data"];
        var jsonError = document.getElementById("jsonError");

        function update() {
            form.submit()
        }

        function getTest() { <?php echo $getTest ?> }
        function postTest() { 
            let data = textarea.value;
            <?php echo $postTest ?>
        }
        
        function validateJSON() {
            let err = 'undefined error';
            try {
                JSON.parse(textarea.value);
                err=null;
            } catch(e) { err = e.message };
            jsonError.innerHTML = err;
            textarea.style.color = err ? 'red' : '';
        }
        textarea.value = JSON.stringify(json, null, 2);
        textarea.onkeyup = validateJSON;
        validateJSON();
    </script>
    
    <button onClick="update()">UPDATE</button>
    <code>
        <?php print code2html($getTest) ?>
        <br/>
    </code>
    <button class="test" onClick="getTest()">TEST</button>

    <code>
        <?php print code2html($postTest) ?>
        <br/>
    </code>
    <button class="test" onClick="postTest()">TEST</button>

</body>
</html>