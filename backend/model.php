<?php
    header("Access-Control-Allow-Origin: http://localhost:5500");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Control-Allow-Methods: POST, PATCH, GET"); 
    header('Allow: <http-methods>');
    header('Content-Control-Allow-Headers: Content-Control-Allow-Headers, Content-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

    
    include_once './db.php';
    include_once './methods.php';
    session_start();

    if($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['crud_req']))
        logout($con);

    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "signup")
        subscribe($con);
                
    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "login")
        login($con);

    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update")
        update($con);

    if($_SERVER['REQUEST_METHOD'] == "GET")
        unsubscribe($con);
    
        //signing up
    function subscribe($x){
        $fname = $_POST['fName'];
        $lname = $_POST['lName'];
        $uname = $_POST['uName'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $rpwd = $_POST['rPwd'];

        //check if any field is empty
        if(empty($fname) || empty($lname) || empty($uname) || empty($email) || empty($pwd) || empty($rpwd))
            Reply(400, "All fields are madatory !");
                 

        if(! filter_var($email, FILTER_VALIDATE_EMAIL))
           Reply(400, "Invalid Email Adress !");
        
        if( $pwd != $rpwd)
            Reply(400, "Inconsistent Password Match !");
        
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $rpwd = $pwd;

        $sql = "INSERT INTO users (FirstName, LastName, Email, UserName, Pwd, R_pwd) VALUES(?,?,?,?,?,?);";
        $stmt = $x->stmt_init();

        if(!$stmt->prepare($sql))
            Reply(400, "Something went wrong. Please try again !");

        $stmt->bind_param("ssssss", $fname, $lname, $email, $uname, $pwd, $rpwd);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            Reply(200, "Sign Up Successful!");
        }
        else{
            http_response_code(400);
            echo "Something went wrong !";
        }
   
    }  
    function login($con){
        $userName = $_POST['uName'];
        $pwd = $_POST['pwd'];

        if(empty($userName) || empty($pwd))
            Reply(400, "All fields are manadatory !");
        
        $sql ="SELECT `Pwd` FROM `users` WHERE `UserName` = ?;";
        $stmt = $con->stmt_init();
        if(!$stmt->prepare($sql))
            Reply(400, "Something went wrong, try again !");

        $stmt->bind_param('s', $userName);
        $stmt->execute();

        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            $data = $result->fetch_assoc();
            $isValid = password_verify($pwd, $data['Pwd']);
            if(!$isValid)
                Reply(400, "Invalid Password !");

            $_SESSION['User'] = $userName;
            Reply(200, "Welcome " . $_SESSION['User']);
        } else {
            Reply(400, "Invalid Password or Username !");
        }
    }

    function logout($con){
        //check if the user is logged in
        if(!isset($_SESSION['User']))
            Reply(400, "You are not logged in!");

        unset($_SESSION['User']);
        session_destroy();
        Reply(200, "You have logged out successfully !");

    }
    function update($con){
        //first we check if the user is signed in
        if(!isset($_SESSION['User']))
        Reply(400, "You are not logged in!");

        //parse_str(file_get_contents("php://input"), $_PATCH);

        $fname = $_POST['fName'];
        $lname = $_POST['lName'];
        $uname = $_POST['uName'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $rpwd = $_POST['rPwd'];

        //check if any field is empty
        if(empty($fname) || empty($lname) || empty($uname) || empty($email) || empty($pwd) || empty($rpwd))
            Reply(400, "All fields are madatory !");
                 

        if(! filter_var($email, FILTER_VALIDATE_EMAIL))
           Reply(400, "Invalid Email Adress !");
        
        if( $pwd != $rpwd)
            Reply(400, "Inconsistent Password Match !");
        
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $rpwd = $pwd;

        $sql = "UPDATE `users` SET FirstName=?, LastName=?, Email=?, UserName=?, Pwd=?, R_pwd=? WHERE UserName=?;";
        $stmt = $con->stmt_init();

        if(!$stmt->prepare($sql))
            Reply(400, "Something went wrong. Please try again !");

        $stmt->bind_param("sssssss", $fname, $lname, $email, $uname, $pwd, $rpwd, $_SESSION['User']);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $_SESSION['User'] = $uname;
            Reply(200, "Details updated Successfully!");
        }
        else{
            http_response_code(400);
            echo "Something went wrong, try again later";
        }
    }

    function unsubscribe($con){

        if(!isset($_SESSION['User']))
            Reply(403, "You are not allowed to perform this operation!");

        $sql = "DELETE FROM `users` WHERE `UserName` = '" . $_SESSION['User'] . "';";
        if($con->query($sql)){
            unset($_SESSION['User']);
            session_destroy();
            Reply(200, "You have successfully unsubscribed from our services !");
        } else{
            Reply(400, "Something went wrong while unsubing");
        }
    }
    
?>