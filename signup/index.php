
<?php

// opendb :: IO PDO
function opendb() {
    $db = new PDO("sqlite:../users.db");

    // Prepare statements in the SQL database.
    //$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Raise hell, and exceptions.
    //$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // This table feels so naked
    $itable = "CREATE TABLE IF NOT EXISTS Users
               (fuser TEXT UNIQUE, fhash TEXT);";

    $db -> exec($itable);

    return $db;
}

function parseArgs($post) {
    if ($post) {
        if ($post["nick"] && $post["pass"]) {
            return $post;

        } else return false;
    }
}

function register($db, $nick, $pass) {
    // Well that was a lot simpler than I thought, I BET THE NSA CAN CRACK THIS
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $query = $db->prepare("INSERT INTO Users (fuser, fhash)
                           VALUES (:fuser, :fhash)");

    $sqlargs = array( "fuser" => $nick
                    , "fhash" => $hash
                    );

    $res = $query->execute($sqlargs);
    var_dump($res);

    $stmt = $db -> prepare("SELECT * FROM Users");
    var_dump($stmt);
    $res = $stmt -> execute();
    var_dump($res);
    $dat = $stmt -> fetchAll();
    var_dump($dat);
}

function main() {
    $args = parseArgs($_POST);

    if ($args) {
        $nick = $args["nick"];
        $pass = $args["pass"];

        $db = opendb();

        register($db, $nick, $pass);

    } else echo "ERROR LOL\n";
}

main();

?>

