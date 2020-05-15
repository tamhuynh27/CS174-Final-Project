<?php
    session_start();
    if(isset($_SESSION['uname'])) {
        $uname = $_SESSION['uname'];
        $pw = $_SESSION['pw'];
        $fname = $_SESSION['fname'];
        $lname = $_SESSION['lname'];
        
        destroy_session_and_data();
        
        echo "Welcome back $fname.<br>
        Your full name is $lname $fname.<br><br>
        Choose either form to submit your content.<br><br>";
        
    }
    
    echo <<<_END
        <html>
        <head><title>Upload file</title></head>
        <body>
        
        <form action='upload.php' method='post'>
        <input type='file' name='filename' size='10'>
        <input type='submit' name='fileUpload' value='Upload'>
        </form>
        <pre>
        Text box: <br>
        <textarea name='comment' rows='5' cols='40'></textarea>
        <input type='submit' name='textUpload' value='Submit'>
        </pre>
_END;

    function destroy_session_and_data() {
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
?>