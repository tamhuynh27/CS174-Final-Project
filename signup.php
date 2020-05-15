<?php
    require_once'login.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if($connection->connect_error) die ("something went wrong");

    echo <<<_END
        <html><head><title>Sign up</title></head>
        <body>
        <h2>Let's create an account</h2>
        <form action='signup.php' method='post'>
        <div>
            <label for='fname'><i>First name</i></label>
            <input type='text' placeholder='First name' name='fname' required/><br>
            <label for='lname'><i>Last name </i></label>
            <input type='text' placeholder='Last name' name='lname' required/><br>
            <label for='uname'><i>User name</i></label>
            <input type='text' placeholder='User name' name='uname' required/><br>
            <label for='pw'><i>Password</i></label>
            <input type='password' placeholder='Password' name='pw' required/><br><br>
            <input type='submit' name='signup' value='Sign up'><br><br>
            <a href='welcome.php'>Already have an account?</a><br>
        </div>
        </form>
        </body></html>
_END;
    
    if(isset($_POST['signup'])) {
        $fname = mysql_entities_fix_string($connection, $_POST['fname']);
        $lname = mysql_entities_fix_string($connection, $_POST['lname']);
        $uname = mysql_entities_fix_string($connection, $_POST['uname']);
        $pw = mysql_entities_fix_string($connection, $_POST['pw']);
           
        $salt1 = rand();
        $salt2 = rand();
        $token = hash('ripemd128', "$salt1$pw$salt2");
        
        $query = "INSERT INTO users VALUES('$fname', '$lname', '$uname', '$token', '$salt1', '$salt2')";
        $result = $connection->query($query);
        
        if($result) {
            die("Account created! <br><a href=welcome.php>Click here to continue</a>");
        } else {
            die ("Something went wrong");
        }
    }


    $connection->close();

    function mysql_entities_fix_string($connection, $string) {
        return htmlentities(mysql_fix_string($connection, $string));
    }

    function mysql_fix_string($connection, $string) {
        if(get_magic_quotes_gpc())
            $string = stripslashes($string);
        return $connection->real_escape_string($string);
    }

?>