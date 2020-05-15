<?php   
    require_once 'login.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if($connection->connect_error) die ("Something went wrong");

echo <<<_END
    <html>
        <head><title>Final Project</title></head>
        <body>
        <h2>Welcome to our project</h2>
        New user?
        <br><br>
        <a href='signup.php'><button>Sign up</button></a>
        <br><br>Already have an account?<br><br>
        <form action="welcome.php" method="post">
        <div>
            <label for="uname"><b>User name</b></label>
            <input type="text" placeholder="Enter username" name="uname" required/>
            <br>
            <label for="pwd"><b>Password</b></label>
            <input type="password" placeholder="Enter password" name="pwd" required/>
            <br><br>
            <input type='submit' name='login' value='Login'/>
        </div>
        </form>
_END;
echo "</html></body>";


    if (isset($_POST['login'])) {
        $un_temp = mysql_entities_fix_string($connection, $_POST['uname']);
        $pw_temp = mysql_entities_fix_string($connection, $_POST['pwd']);
        $query = "SELECT * FROM users where username = '$un_temp'";
        $result = $connection->query($query);
     
        if(!$result) die ("Something went wrong");
        elseif ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            
            $salt1 = $row[5];
            $salt2 = $row[6];
            
            $token = hash('ripemd128', "$salt1$pw_temp$salt2");
            
            if($token==$row[4]) {
                session_start();
                $_SESSION['uname'] = $un_temp;
                $_SESSION['pw'] = $pw_temp;
                $_SESSION['fname'] = $row[0];
                $_SESSION['lname'] = $row[1];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                
                //redirect page after user authorization
                //to upload page
                header("Location: upload.php");
            } else 
                die("Invalid password or username");
        } 
        else 
            die("Invalid password or username");    
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
