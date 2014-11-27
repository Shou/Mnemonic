
<?php

require("../server.php");


function poster() {
    $args = authArgs($_POST);

    if ($args) {
        $db = opendb("../users.db", $authTables);

        if (strlen($args["pass"]) < 8)
            echo "Please use 8 or more characters in your password.\n";

        else if (strlen($args["pass"]) > 255)
            echo "Please use less than 256 characters in your password.\n";

        else if (strlen($args["user"]) > 32)
            echo "Please use 32 or less characters in your username.\n";

        else register($db, $args["user"], $args["pass"]);

    } else echo "1";

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

<form name="auth" method="post" action="/signup/">

<h1>Sign up</h1>
<input type="text" name="user" placeholder="Username" required>
<input type="password" name="pass" placeholder="Password" required>
<input type="submit" value="Sign up">

</form>

<article>

<h3>Anonymous <span>&</span> Convenient</h3>
<p>
Logging in to use the service isn't required, but registering will give
access to more features.
</p>

<h3>Beautiful <span>&</span> Organic</h3>
<p>
More web design buzzwords. Just sign up already!!!!!
</p>

<h3>Harnessing space</h3>
<p>
please click the button
</p>

</article>

</div>

</main>

<footer>Design by <span>Benedict Aas</span></footer>

<script type="text/javascript" src="/script.js"></script>

</body>

</html>

