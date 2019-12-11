<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Forgot Password</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="index.html">Home</a>
    </li>
    <li class="breadcrumb-item active">Forgot Password</li>
    </ol> 

    <?php 
        if($_POST){
            if(isset($_POST["email"]) && (!empty($_POST["email"]))){
                $email = $_POST["email"];
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            }
             //Array for storing validation errors
             $errors = array(); 

            if(!$email){
                //Invalid email format - prepare error message
                $errors['email'] = "Invalid email address please type a valid email address!";
            }else{
                //Good - check database for 

                //==============  Check for existing email ==============
                $stmt = $dbc->prepare("SELECT COUNT(*)
                                       FROM users
                                       WHERE email=:email");
                
                $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                $stmt->execute();
                $num_rows = $stmt->fetchColumn();
                
                if($num_rows==0){
                    //no user found - prepare error message
                    $errors['user'] = "No user is registered with this email address!";
                }
                //var_dump($num_rows);
                //var_dump($errors);
                //exit();
            }

            //var_dump($errors);
            if(empty($errors)){
                //Proceed - create a temporary password key
                $expFormat = mktime(
                date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
                $expDate = date("Y-m-d H:i:s",$expFormat);
                $token = md5($email);
                $addtoken = substr(md5(uniqid(rand(),1)),3,10);
                $token = $token . $addtoken;

                //Prepare query
                $stmt = $dbc->prepare("INSERT INTO `password_reset_temp` (`email`, `token`, `expDate`)
                                       VALUES (:email, :token, :expDate)");
                //bind the params
                $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                $stmt->bindValue(':token',$token,PDO::PARAM_STR);
                $stmt->bindValue(':expDate',$expDate,PDO::PARAM_STR);

                 //execute the statement
                 $result = $stmt->execute();
                 
                 if($result){
                    //record inserted in database - SEND EMAIL
                    $siteURL = "http://localhost:8888/InClassPDODemos/reset-password.php?e=". 
                    urlencode($email) .  "&t=$token";
                    $replyToEmail ="knowledge@programming.oultoncollege.com";
                    $replyToName = "Knowledge Is Power";
                    $mailSubject = "Knowledge Is Power - Password Reset";
                    $messageTEXT = "<Password Recovery - Knowledge Is Power</strong>\n\n
                                    To reset your account password please click on this link: $siteURL\n\n
                                    Note:  The link will expire after 1 day for security reason.";
                    $messageHTML = "<p><strong>Password Recovery - Knowledge Is Power</strong></p>
                                    <p>To reset your account password please click on this link:</p>
                                    <a href='$siteURL'>Reset your account password</a>
                                    <p>Note:  The link will expire after 1 day for security reason.</p>";
                                    
                    $fromEmail = "knowledge@programming.oultoncollege.com";
                    $fromName =  "Knowledge Is Power";
                    $toEmail = $email;
                    $toName = "KnowledgePower User";


                    //Load the PHPMailer classes
                    require './vendor/autoload.php';
                    require './mail/sendMail.php';

                    //Instantiate the sendMail class
                    $mail = new sendMail($replyToEmail,$replyToName,$mailSubject,
                                        $messageHTML,$messageTEXT,$fromEmail,
                                        $fromName,$toEmail,$toName);
                    //Send the email
                    $mailResult = $mail->SendMail();

                    //Check email result 
                    if($mailResult){
                    //=================== mail success ===================
                    echo '<div class="alert alert-success" role="alert">
                            <p><strong>Password Reset</strong></p>
                            <p>Please check your email to reset your account password</p>
                        </div>';

                        //terminate page
                        echo "</div>";
                        include 'includes/footer.php';
                        exit();

                    }else{
                    //=================== mail failure ===================
                    echo '<div class="alert alert-warning" role="alert">
                            <p><strong>Password Reset</strong></p>
                            <p>Error sending email:  Please contact customer support!</p>
                    </div>';
                    }
                 }else{
                    //error inserting record in database - SHOW ERROR MESSAGE
                    echo '<div class="alert alert-danger"><strong>Password Reset</strong>
                            <p>A server error has occured, cannot reset your password</p>
                        </div>';
                 }
            }else{
                //NOT GOOD - SOME VALIDATION ERRORS ARE PRESENT->DISPLAY THE ERRORS
                //debug
                //var_dump($errors);
                echo '<div class="alert alert-danger" role="alert">';
                    echo "<ul>";
                        foreach ($errors as $error){
                            echo "<li>$error</li>";
                        }
                    echo "</ul>";
                echo '</div>';

            }
        }

    ?>


    <form method="post" action="forgot-password.php">
        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" name="email"
                   type="email" oninvalid="this.setCustomValidity('Please enter your email')" 
                   oninput="setCustomValidity('')"                   
                   placeholder="Enter email" required
                   value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
            <!-- <input class="form-control" id="email" name="email" required
                    type="email" aria-describedby="emailHelp" placeholder="Enter email"> -->
        </div>        
        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
    </form>

</div>    