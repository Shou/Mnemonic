
<?php

// TODO
// - Logged-in upload
// - Cookies
//      - Change expiry date (7 days?) on every page load
// - Logout
//      - POST with cookie key as argument?
//          - This prevents others from causing logouts by linking the URL.
//      - GET presents confirmation button sending POST request.
// - File control
// - Page numbers.


// {{{ Consts

$fileTable = "CREATE TABLE IF NOT EXISTS Files
              ( fname TEXT, fuser TEXT, fpath TEXT, fhash TEXT, ftype TEXT
              , fsize INTEGER, fdate INTEGER
              , UNIQUE (fname, fpath, fuser)
              );
              CREATE TABLE IF NOT EXISTS Paths
              ( fname TEXT, fpath TEXT, fuser TEXT, fsize INTEGER, fdate INTEGER
              , UNIQUE (fname, fpath, fuser)
              );";

$authTables = "CREATE TABLE IF NOT EXISTS Logins
               (fuser TEXT, fkey TEXT UNIQUE, fdate INTEGER);
               CREATE TABLE IF NOT EXISTS Users
               (fuser TEXT UNIQUE, fhash TEXT);";

// }}}

// {{{ Utils

// opendb :: IO PDO
function opendb($path, $table) {
    // Create or open "files.db" SQLite3 database.
    $db = new PDO("sqlite:" . $path);

    // Prepare statements in the SQL database.
    //$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Raise hell, and exceptions.
    //$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db -> exec($table);

    return $db;
}

// authArgs :: Obj String String -> Maybe (Obj String String)
function authArgs($post) {
    return safeArgs($post, ["user", "pass"]);
}

// safeArgs :: Obj String String -> [String] -> Maybe (Obj String String)
function safeArgs($obj, $arr) {
    for ($i = 0; $i < count($arr); $i++)
        if (! $obj[$arr[$i]]) return null;

    return $obj;
}

// XXX prepared statements here too??
// cookieMatch :: PDO -> String -> String -> String -> IO Bool
function cookieMatch($db, $val, $key, $tab) {
    $query = $db -> prepare("SELECT * FROM $tab
                             WHERE $val=:fkey;");

    $sqlargs = array( "fkey" => $key );

    $exe = $query -> execute($sqlargs);

    $fet = $query -> fetch();

    return $fet;
}

// makeAuthCookie :: PDO -> String -> IO Bool
function makeAuthCookie($db, $user) {
    $expire = time() + (365 * 24 * 60 * 60);

    // oh no there's a n / 374144419156711147060143317175368453031918731001856
    // chance of a collision :(
    $rkey = base64_encode(openssl_random_pseudo_bytes(21));

    $query = $db -> prepare("INSERT INTO Logins (fuser, fkey, fdate)
                             VALUES (:fuser, :fkey, :fdate);");


    $sqlargs = array( "fuser" => $user
                    , "fkey" => $rkey
                    , "fdate" => $expire
                    );

    $res = $query -> execute($sqlargs);

    if ($res) {
        // please don't intercept my cookies
        setcookie("pass", $rkey, $expire, "/", "", false, true);

        return 0;

    } else return 3;
}

// redirect :: String -> IO ()
function redirect($path) {
    header("Location: " . $path, true, 302);
    exit();
}

// substrEq :: String -> String -> Bool
function substrEq($str, $sub) {
    return substr($str, 0, strlen($sub)) == $sub;
}

// init :: [a] -> [a]
function init($xs) {
    return array_slice($xs, 0, -1);
}

// pathEq :: String -> String -> Bool
function pathEq($p0, $p1) {
    $ps0 = array_filter(explode("/", $p0));
    $ps1 = array_filter(explode("/", $p1));

    $b = true;

    if (count($ps0) != count($ps1)) $b = false;

    else for ($i = 0; $i < count($ps0); $i++) $b = $b && $ps0[$i] == $ps1[$i];

    return $b;
}

// }}}

// storeFiles :: PDO -> IO ()
function storeFiles($fdb, $adb) {
    $tmps = $_FILES["files"]["tmp_name"];
    $sizes = $_FILES["files"]["size"];
    $names = $_FILES["files"]["name"];

    $total_filesize = 0;

    $len = count($tmps);

    $user = cookieAuth($adb)["fuser"];

    $path = $_POST["path"];
    if (! $path) $path = "/";

    // Check the total filesize, get file extension, generate SHA1 for file,
    // move file to "up" folder.
    for ($i = 0; $i < $len; $i++) {
        $total_filesize += $sizes[$i];

        if ($total_filesize > pow(1024, 2) * 10) {
            echo "File(s) too large.\n";

            break;
        }

        $name = $names[$i];

        $exts = explode(".", $name);
        $extn = "";

        $hash = sha1_file($tmps[$i]);

        if (count($exts) > 1) {
            $extn = implode(array_slice($exts, 1));
            $filen = $hash;

        } else {
            $filen = $hash;
        }

        $time = time();

        $size = $sizes[$i];

        if (! file_exists("../up/" . $path . $filen))
            $moved = move_uploaded_file($tmps[$i], "../up/" . $filen) . "\n";

        if ($moved) echo $path . $filen . "\n";

        $iquery = "INSERT INTO Files (fname, fuser, fpath, fhash, ftype, fsize, fdate)
                   VALUES (:name, :user, :path, :hash, :type, :size, :date)";
        $stmt = $fdb -> prepare($iquery);
        var_dump($stmt);
        $sqlargs = array( "name" => $name
                        , "user" => $user
                        , "path" => $path
                        , "hash" => $hash
                        , "type" => $extn
                        , "size" => $size
                        , "date" => $time
                        );
        $res = $stmt -> execute($sqlargs);
        var_dump($res);

        $stmt = $fdb -> prepare("SELECT * FROM Files");
        var_dump($stmt);
        $res = $stmt -> execute();
        var_dump($res);
        $dat = $stmt -> fetchAll();
        var_dump($dat);
    }
}

// login :: PDO -> String -> String -> IO Int
function login($db, $user, $key) {
    $cook = $_COOKIE["pass"];
    var_dump($cook);

    if (cookieMatch($db, "fkey", $cook, "Logins")) return 2;

    $query = $db -> prepare("SELECT * FROM Users
                             WHERE fuser=:fuser;");

    $sqlargs = array( "fuser" => $user );

    $res = $query -> execute($sqlargs);

    $fuser = $query -> fetchAll();

    if (count($fuser) === 1) {
        $hash = $fuser[0]["fhash"];

        if (password_verify($key, $hash))
            return makeAuthCookie($db, $user);

        else return 4;

    } else return 5;
}

// function cookieAuth :: PDO -> IO (Maybe (Obj String String))
function cookieAuth($db) {
    $cook = $_COOKIE["pass"];

    if ($cook)
        return cookieMatch($db, "fkey", $cook, "Logins");

    else return null;
}

// register :: PDO -> String -> String -> IO ()
function register($db, $user, $pass) {
    // Well that was a lot simpler than I thought I BET THE NSA CAN CRACK THIS
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $query = $db -> prepare("INSERT INTO Users (fuser, fhash)
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

    login($db, $user, $pass);
}

// selectFile :: String -> PDO -> PDO -> IO [Object String String]
function selectFile($ft, $fdb, $user) {
    $isql = "SELECT * FROM $ft
             WHERE fuser=:fuser";
    $sel = $fdb -> prepare($isql);

    $sqlargs = array( "fuser" => $user );
    $exe = $sel -> execute($sqlargs);

    return $sel -> fetchAll();
}

// logout :: PDO -> String -> IO ()
function logout($adb, $hash) {
    $query = $adb -> prepare("DELETE FROM Logins
                              WHERE fkey=:fkey");

    $sqlargs = array( "fkey" => $_COOKIE["pass"] );

    $res = $query -> execute($sqlargs);

    setcookie("pass", null, time() - 3600, "/", "", false, true);
    unset($_COOKIE["pass"]);

    if (! $res) echo "Key doesn't exist.\n";
}

?>

