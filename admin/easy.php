<?php
if(!@include("../conf.php")) { echo("Setup missing"); die(); }
$url = $GLOBALS['CONFIG']['base_url']."/?id=".$_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
  body {
    text-align: center;    
    font-family: sans-serif;
   }
  input {
    font-size: 4em;
    padding: 0.2em;    
    font-family: sans-serif;
  }
  button {
    margin: 0.2em;
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 0.2em 0.5em;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 4em;
  }
  button:hover{
    background-color: #ccc;
    cursor: pointer;
  }
  #result {
    font-size: 2em;
    color: #4CAF50; /* Green */
    margin: 1em;
  }
</style>
</head>

<body>
    <br />
    <input id='value' type='text'/>
    <br />
    <button id="send">Send</button>
    <br />
    <br />
    <span id="result"></span>
</body>

<script>
    const inputElt = document.getElementById('value');
    const sendElt = document.getElementById('send');
    const resultElt = document.getElementById('result');
    const url = '<?php echo $url ?>';

   // read previous value
   fetch(url,{ method: 'GET' })
      .then(function(res) {
          if (!res.ok) throw Error(res.statusText);
          return res.json();
      })
      .then(function(data){
          data = JSON.parse(data);
          inputElt.value = data.value;
      })
      .catch(function(e){ alert(e); console.error(e) })

    sendElt.onclick = () => {
      const data = JSON.stringify({value: inputElt.value})

      // write new value
      fetch(url, { method: 'POST', body: data })
        .then(function(res) {
              if (!res.ok) throw Error(res.statusText);
              return res.json();
        })
        .then(function(data){
          data = JSON.parse(data);
          resultElt.innerHTML = 'Value successfully changed!';
          console.log(data)
        })
        .catch(function(e){ alert(e); console.error(e) })
      event.preventDefault();
    };
</script>

</html>