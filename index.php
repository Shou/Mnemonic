
<?php

require("server.php");


function main() {
    $adb = opendb("users.db", $authTables);

    $auth = cookieAuth($adb);

    if ($auth) redirect("/files/");
}

main();

?>

<!DOCTYPE html>

<head>

<title>Mnemonic</title>

<meta charset="UTF-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<link rel="stylesheet" type="text/css" href="/style.css">

</head>

<body id=front>

<header>

<a href="/">Mnemonic</a>

<label for="upload">Upload</label>
<input type="file" name="files[]" id="upload" multiple>

</header>


<main>

<form name="auth" method="post" action="/signup/">

<h1>Sign up
<span>or log in</span>
</h1>
<input type="text" name="user" placeholder="Username" required>
<input type="password" name="pass" placeholder="Password" required>
<div>
    <input type="submit" value="Sign up">
    <input type="button" value="Log in">
</div>

</form>

</main>

<?php require("bottom.html"); ?>

