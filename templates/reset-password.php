<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Reset Password</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="index.html">Home</a>
    </li>
    <li class="breadcrumb-item active">Reset Password</li>
    </ol> 

    <?php 
        
        ///////////////////////////////////
        //1.  Check if the id parameter exists, and is numeric
        if(isset($_GET['e']) && isset($_GET['t']) ){
            //FOR GET ONLY - GOOD (retrieve it)
            //Good to go - Retrieve them and store in variables
            $e = $_GET['e']; //email
            $t = $_GET['t']; //token

        }elseif(isset($_POST['e']) && $_POST['t'] ){
            //FOR POST ONLY - GOOD (retrieve it)
            $e = $_POST['e']; //email
            $t = $_POST['t']; //token

        }else{
            //Invalid parameter or doesn't exit - kill the script
            echo '<div class="alert alert-danger" role="alert">
                    <strong>Reset Password Failed</strong>
                    <p>Your password has not been reset!</p>                        
                </div>';

            include './includes/footer.php';
            exit();
        }

        //===================== CHECK FOR POST ======================
        if($_POST){
            //var_dump($_POST);
            //exit();

            //Validate Passwords
            $reg_errors = array();
            // 3.Check for a password and match against the confirmed password:
            /* rules:  
            * - start of the line ^
            * - Password must be 6-40 characters - {6,40} 
            * - Must have no spaces, at least 1 digit (?=.*[\d])
            * - at least 1 uppercase letter (?=.*[A-Z]) 
            * - and at least one lowercase letter (?=.*[a-z]) 
            * - Allows specifying special characters - !@#$%_ 
            * - end of line $        
            */
            if (preg_match('/^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])[\w\d!@#$%_]{6,40}$/', $_POST['password1'])) {
                if ($_POST['password1'] == $_POST['password2']) {
                    $password2 = strip_tags($_POST['password2']);
                } else {
                    $reg_errors['password2'] = 'Your password did not match the confirmed password!';
                }
            } else {
                $reg_errors['password1'] = 'Password must be between 6 and 20 characters long, with 
                        at least one lowercase letter, one uppercase letter, 
                        and one number.!';
            }
            if(empty($reg_errors)){
                //GOOD - NO VALIDATION ERRORS ->PROCEED TO UPDATE USER PASSWORD
                //echo $password2;
                //echo '<br>';

                //create a password hash
                $salted_password = password_hash($password2,PASSWORD_BCRYPT);
               // echo $salted_password;

                $stmt = $dbc->prepare("UPDATE users SET pass=:pass 
                                       WHERE email=:email");

                //bind the params
                $stmt->bindValue(':email',$e,PDO::PARAM_STR);
                $stmt->bindValue(':pass',$salted_password,PDO::PARAM_STR);


                $result = $stmt->execute();
                $count = $stmt->rowCount();

                //Check for successfull update
                if ($count > 0) {
                    //User successfully activated
                    echo '<div class="alert alert-success"><strong>Password Reset</strong>
                            <p>Your password has been successfully reset!  Proceed to the login page!</p>
                            <a class="btn btn-success" href="login.php">Login</a>
                        </div>';

                        //Now delete records from password reset temp table
                        $stmt = $dbc->prepare("DELETE FROM password_reset_temp
                                               WHERE email=:email");
                        //bind the params
                        $stmt->bindValue(':email',$e,PDO::PARAM_STR); 

                        $result = $stmt->execute();

                } else {
                    //Failed to activate user
                    echo '<div class="alert alert-danger"><strong>Password Reset Failed</strong>
                            <p>Your password has not been reset!</p>
                        </div>'; 
                }


                //terminate script
                echo "</div>";
                include 'includes/footer.php';
                exit();
            }else{
                //NOT GOOD - SOME VALIDATION ERRORS ARE PRESENT->DISPLAY THE ERRORS
                //debug
                //var_dump($reg_errors);
                echo '<div class="alert alert-danger" role="alert">';
                echo "<ul>";

                foreach ($reg_errors as $error){
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo '</div>';
            }            
        }//END IF POST

        $curDate = date("Y-m-d H:i:s");  //Get current date ->2019-12-11 13:37:31

        //CHECK parms against the database
        $stmt = $dbc->prepare("SELECT COUNT(*)
                                FROM password_reset_temp
                                WHERE email=:email AND token=:token");

        //PDO bind parameters
        $stmt->bindValue(':email',$e,PDO::PARAM_STR);
        $stmt->bindValue(':token',$t,PDO::PARAM_STR);

        //Execute the statement
        $result = $stmt->execute();

        //fetch the column
        $num_row = $stmt->fetchColumn();

        if($num_row>0){
            //VALID LINK - show form to change password
            $stmt = $dbc->prepare("SELECT id,email, token, expDate
                                    FROM password_reset_temp
                                    WHERE email=:email AND token=:token");

            //PDO bind parameters
            $stmt->bindValue(':email',$e,PDO::PARAM_STR);
            $stmt->bindValue(':token',$t,PDO::PARAM_STR);

            //Execute the statement
            $result = $stmt->execute();

            //Fetch the record 
            $user= $stmt->fetch(PDO::FETCH_ASSOC);

            //Get user info
            $user_email = $user['email']; 
            $user_token = $user['token'] ;
        ?>

            <form method="post" action="reset-password.php">
            <input type="hidden" name="e" value="<?php echo $user_email?>">
            <input type="hidden" name="t" value="<?php echo $user_token?>">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="password1">New Password</label>
                        <input class="form-control" id="password1" name="password1"
                            type="password" 
                            oninvalid="this.setCustomValidity('Please enter password')" 
                            oninput="setCustomValidity('')" autocomplete="off"                  
                            placeholder="Enter password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password2">Confirm New Password</label>
                        <input class="form-control" id="password2" name="password2"
                            type="password" 
                            oninvalid="this.setCustomValidity('Please confirm password')" 
                            oninput="setCustomValidity('')" autocomplete="off"                 
                            placeholder="Confirm password" required>
                    </div>
                </div>
            </div> 
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>

        <?php   
            }else{
                //INVALID LINK - show message
                echo '<div class="alert alert-danger"><strong>Invalid Link</strong>
                        <p>The link is invalid or expired. Either you did not copy the correct link
                        from the email, or you have already used the key in which case it is 
                        deactivated.</p>
                        <p><a class="btn btn-danger" href="http://localhost:8888/InClassPDODemos/forgot-password.php">Reset Password</a></p>
                      </div>';           
            }

        
    ?>

</div>