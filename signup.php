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
            <label for='email'><i>Email</i></label>
            <input type='text' placeholder='Email' name='email' required/><br>
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
        $email = mysql_entities_fix_string($connection, $_POST['email']);
        $uname = mysql_entities_fix_string($connection, $_POST['uname']);
        $pw = mysql_entities_fix_string($connection, $_POST['pw']);
        
        //user's information validation and insertion
        if(preg_match('/^[a-zA-Z]*$/', $fname)) {
            if(preg_match('/^[a-zA-Z]*$/', $lname)) {
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if(preg_match('/^[a-zA-Z0-9_-]*$/', $uname)) {
                        if(strlen($_POST['pw']) >= 8) {
                            $salt1 = rand();
                            $salt2 = rand();
                            $token = hash('ripemd128', "$salt1$pw$salt2");
                            
                            $query = "INSERT INTO users VALUES('$fname', '$lname', '$email', '$uname', '$token', '$salt1', '$salt2')";
                            $result = $connection->query($query);
                            
                            if($result) {
                                die("Account created! <br><a href=welcome.php>Click here to continue</a>");
                            } else {
                                die ("Something went wrong");
                            }
                        } else {
                            echo "Password must contain at least 8 characters.";
                        }
                    } else {
                        echo "Username can only contain: <br>
                            <p><i>English characters<br>digits<br>underscore(_) and dash(-)<br></i></p>";
                    }
                } else {
                    echo "Please enter a valid email address.";
                }
            } else {
                echo "Please enter a legit last name.";
            }
        } else {
            echo "Please enter a legit first name.  ";
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