<?php
    session_start();
    if(isset($_SESSION['uname'])) {
        $uname = $_SESSION['uname'];
        $pw = $_SESSION['pw'];
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        
        echo "Welcome back $fname.<br>
        Your full name is $lname $fname.<br><br>
        Choose either form to submit your content.<br><br>
        Remember to give input content with numbers only.";
        
    }
    if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] &&
      $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT'])) {
        different_user();  
    } 
    
    echo <<<_END
        <html>
        <head><title>Upload file</title></head>
        <body>
        
        <form action='upload.php' method='post' enctype='multipart/form-data'>
        <input type='file' name='filename' size='10'>
        <input type='submit' name='fileUpload' value='Upload'>
        <pre>
        Text box: <br>
        <textarea name='inputBox' rows='5' cols='40'></textarea>
        <input type='submit' name='textUpload' value='Submit'>
        </pre>
        </form>
        </body></html>
_END;
    
    if(isset($_POST['fileUpload'])) {
        if($_FILES) {
            if($_FILES['filename']['type'] == "text/plain") {
                $name = $_FILES['filename']['name'];
                if(!validFile($name)) {
                    echo "Please upload file with numbers only";
                } else {
                    move_uploaded_file($_FILES['filename']['tmp_name'], $name);
                    echo "Uploaded file $name <br><br>";
                }
            } else {
                echo "Please upload only file with .txt extension";
            }
        }
    }

    if(isset($_POST['textUpload'])) {
        if(!empty($_POST['inputBox'])) {
//            $content = mysql_fix_string($connection, $_POST['inputBox']);
            $content = $_POST['inputBox'];
            if(is_numeric($content)) {
                //insert to db???
            } else {
                echo "Please give input with only numbers.";
            }
        } else {
            echo "Please give some input with only numbers.";
        }
    }

    function different_user() {
        destroy_session_and_data();
        die("Sorry, time out.<br> Please log in again <a href='welcome.php'>here</a>");
    }

    function destroy_session_and_data() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
    
    function validFile($fileName) {
        $strInput = file_get_contents($fileName);
        if(is_numeric($strInput))
            return true;
        else
            return false;
    }

//    function mysql_fix_string($conn, $string) {
//        if(get_magic_quotes_gpc())
//            $string = stripslashes($string);
//        return $connection->real_escape_string($string);    
//    }

?>