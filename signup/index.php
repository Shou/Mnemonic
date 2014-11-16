
<?php

// opendb :: IO PDO
function opendb($path) {
    $db = new PDO("sqlite:" . $path);

    $itable = "CREATE TABLE IF NOT EXISTS Logins
               (fuser TEXT, fkey TEXT UNIQUE, fdate INT);
               CREATE TABLE IF NOT EXISTS Users
               (fuser TEXT UNIQUE, fhash TEXT);";

    $db -> exec($itable);

    return $db;
}

// parseArgs :: Obj String String -> Obj String String
function parseArgs($post) {
    if ($post) {
        if ($post["user"] && $post["pass"]) {
            return $post;

        } else return null;
    }
}

// register :: PDO -> String -> String -> IO ()
function register($db, $user, $pass) {
    // Well that was a lot simpler than I thought I BET THE NSA CAN CRACK THIS
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $query = $db->prepare("INSERT INTO Users (fuser, fhash)
                           VALUES (:fuser, :fhash)");

    $sqlargs = array( "fuser" => $user
                    , "fhash" => $hash
                    );

    $res = $query -> execute($sqlargs);

    if (! $res) echo "That user already exists, please try again.\n";

    $stmt = $db -> prepare("SELECT * FROM Users");
    $res = $stmt -> execute();
    var_dump($res);
    $dat = $stmt -> fetchAll();
    var_dump($dat);
}


function main() {
    $args = parseArgs($_POST);

    if ($args) {
        $db = opendb("../users.db");

        if (strlen($args["pass"]) < 8)
            echo "Please use 8 characters or more in your password.\n";

        else register($db, $args["user"], $args["pass"]);

    } else echo "1";
}

main();

?>

