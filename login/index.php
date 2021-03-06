
<?php

require("../server.php");


function poster() {
    $db = opendb("../users.db", $authTables);

    $args = authArgs($_POST);

    if ($args) {
        $code = login($db, $args["user"], $args["pass"]);

        echo $code;

    } else echo 1;

    exit();
}

function geter() {
    $adb = opendb("../users.db", $authTables);

    $auth = cookieAuth($adb);

    if ($auth) redirect("/files/");
}

function main() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") poster();

    else geter();
}

main();

?>

<!DOCTYPE html>

<head>

<title>Log in &#x25AA; Mnemonic</title>

<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<link rel="stylesheet" type="text/css" href="/style.css">

</head>

<body id="auth">

<header>

<a href="/">Mnemonic</a>

<label for="upload">Upload</label>
<input type="file" name="files[]" id="upload" multiple>

</header>

<main>

<div>

<form name="auth" method="post" action="/login/">

<h1>Sign in</h1>
<input type="text" name="user" placeholder="Username" required>
<input type="password" name="pass" placeholder="Password" required>
<input type="submit" value="Log in">

</form>


</div>

</main>

<footer>Design by <span>Benedict Aas</span></footer>

<script type="text/javascript" src="/script.js"></script>

</body>

</html>

