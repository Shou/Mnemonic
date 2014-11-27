
<?php

require("server.php");


function main() {
    $adb = opendb("users.db", $authTables);

    $auth = cookieAuth($adb);

    if ($auth) redirect("/files/");
}

main();

?>

<?php require("top.html"); ?>

<main>

<form name="auth" method="post" action="/signup/">

<h1>Sign up
<span>or log in</span>
</h1>
<input type="text" name="user" placeholder="Username">
<input type="password" name="pass" placeholder="Password">
<div>
    <input type="submit" value="Sign up">
    <input type="button" value="Log in">
</div>

</form>

</main>

<?php require("bottom.html"); ?>

