
<?php

require("../server.php");

function poster() {
    $adb = opendb("../users.db", $authTables);

    logout($adb, $_COOKIE["pass"]);

    redirect("/");

    exit();
}

function geter() {
    $adb = opendb("../users.db", $authTables);

    $auth = cookieAuth($adb);

    if (! $auth) redirect("/");
}

function main() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") poster();

    else geter();
}

main();

?>

<!DOCTYPE html>

<head>

<title>Sign up &#x25AA; Mnemonic</title>

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

<form name="auth" method="post" action="/logout/">

<h1>Sign out
<span>are you sure?</span>
</h1>
<input type="submit" value="Sign out">

</form>

</div>

</main>

<footer>Design by <span>Benedict Aas</span></footer>

<script type="text/javascript" src="/script.js"></script>

</body>

</html>

