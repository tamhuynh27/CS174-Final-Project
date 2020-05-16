<?php
    session_start();
    if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] &&
      $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT'])) {
        different_user();  
    } 
    
    require_once'login.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if($connection->connect_error) die ("something went wrong");

    if(isset($_SESSION['uname'])) {
        $uname = $_SESSION['uname'];
        $pw = $_SESSION['pw'];
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        
        echo "Welcome back $fname.<br>
        Your full name is $lname $fname.<br><br>";        
    }
    
    echo <<<_END
        <html>
        <head><title>Upload file</title></head>
        <body>
        Choose either form and your choice of models to train your data.<br>
        Remember to give input content with numbers only.<br><br>
        
        <form action='upload.php' method='post' enctype='multipart/form-data'>
        <input type='radio' name='model' id='createmodel'/>
        <label for='createmodel'>Create model</label>
        <input type='text' name='newModel' placeholder='Model name' required><br>
        <input type='radio' name='model' id='usemodel'/>
        <label for='usemodel'>Use existing model</label>
        <input type='text' name='oldModel' placeholder='Model name' required><br>
        <br>
        <input type='file' name='filename' size='10'>
        <br><br>Input box: <br>
        <textarea name='inputBox' rows='5' cols='40'></textarea>
        <br><br>
        <input type='submit' name='submit' value='Submit'>
        </form>
        </body></html>
_END;
    
    if(isset($_POST['submit'])) {
        if(isset($_POST['model'])) {
            if(validFileUpload() || validInputBox()) {
                
            }
        } else {
            echo "Please create a model or use an existing one.";
        }
    }
    
    
    $connection->close();
    
    function validFileUpload() {
        if(!empty($_POST['inputBox'])){
            return false;
        } else{
            if($_FILES) {
                if($_FILES['filename']['type'] == "text/plain") {
                    $name = $_FILES['filename']['name'];
                    if(!validFile($name)) {
                        echo "Please upload file with numbers only.";
                        return false;
                    } else {
                        move_uploaded_file($_FILES['filename']['tmp_name'], $name);
                        echo "Uploaded file $name <br><br>";
                        return true;
                    }
                } else {
                    echo "Please upload only file with .txt extension.";
                    return false;
                }
            }
        }
    }

    function validInputBox() {
        if($_FILES) {
            return false;
        } else {
            if(!empty($_POST['inputBox'])) {
                $content = $_POST['inputBox'];
                $content = preg_replace('/[ ,]+/', '', $content);
                if(is_numeric($content)) {
                    return true;
                } else {
                    echo "Please give input with only numbers.";
                    return false;
                } 
            } else {
                echo "Please give some input with only numbers.";
                return false;
            }
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

    function mysql_fix_string($conn, $string) {
        if(get_magic_quotes_gpc())
            $string = stripslashes($string);
        return $connection->real_escape_string($string);    
    }

?>