<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/templateStyling.css">
        <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script type="text/javascript"> window.jQuery || document.write('<script src="js/jquery-3.1.1.min.js"><\/script>'); </script>
        <script src="js/passwordRecovery.js"></script>
        <meta name="viewport" content="width=device-width; initial-scale=1.0">
    </head>
    <body>
    <?php
	include 'db_connection.php';
	include 'header.php';
    ?>
    <?php
    $conn = OpenCon();
    //if the username is set on button click
    if(isset($_POST['username'])){
       $username = $_POST['username'];
       $password = $_POST['password'];
       
        $query = "SELECT * FROM users WHERE username='".$username."'AND password='".$password."' limit 1";
      
        $result = $conn->query($query);
        CloseCon($conn);
       //if there is an account with the specified username and password
        if (!empty($result) && $result->num_rows > 0) {
           $row = $result->fetch_assoc() ;
           $admin = $row['admin'];
            //check if user is admin or not and then set the session variables to their username, password and admin status
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['login'] = TRUE;
            if ($admin==1){
                $_SESSION['admin'] = TRUE;
            }
            echo "<script> window.location.assign('feed.php'); </script>";
            exit();
        }
        else {
            $message = "Username or password does not match";
            echo "<script type='text/javascript'>alert('$message');</script>";
            
            echo "<script> window.location.assign('login.php'); </script>";
            exit();
        }
    }
    ?>
        <form method="POST" action="#" class="holder">
            <h1>Login</h1>
            <input required type="text" name="username" placeholder="Username" >
            <input required type="password" name="password" placeholder="Enter Password">
            <input type="submit" value="Login">

            <div id="links">
                <a href="#" id ="forgotPassword" ><p class="hover">Forgot Password?</p></a>
                <a href="signup.php"><p class="hover">Don't have an account? Sign up now</p></a>
            </div>

        </form>

    <?php
    //if the user clicks on recover passoword
    if(isset($_POST['recoverButton'])){
    $username = $_POST['uname'];
        //the javascipt function will then be called and the HTML is modified with a username form
        //onclick of recovery button, using PHP mailer perform a query to see if the username exists
    $conn = OpenCon();
    $query = "SELECT email,password FROM users WHERE username='".$username."'";
    $result = $conn->query($query);
    CloseCon($conn);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $email = $row["email"];
            $password = $row["password"];
            require_once("PHPMailer/PHPMailerAutoload.php");
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '465';
            $mail->isHTML();
            $mail->Username = 'catsdogs262@gmail.com';//our fake email accout
            $mail->Password = '123456Cats!';//password
            $mail->SetFrom('catsdogs262@gmail.com');
            $mail->Subject= 'Your Chats and Dogs password';
            $mail->Body = 'Hello, your chats and dogs password is the following: '.$password;
            $mail->AddAddress($email);
            $mail->Send();
        
        }
        
    }
    else{
        exit();
    }
    }
    ?>


    </body>
</html>