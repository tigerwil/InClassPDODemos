<div class="container">
    <h1 class="mt-4 mb-3">Register</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Register</li>            
    </ol>
    <?php
        if ($_POST) {
            //var_dump($_POST);

            /* validation start */
            //Array for storing validation errors
            $reg_errors = array(); 

            //1.Check for firstname (characters, apos, period, space and dash b/w 2 and 45
            /* rules:  between 2, 45 characters
              letters A-Z, case-insensitive (i)
              space, apostrophe, period, hyphen */
            if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['firstname'])) {
                $firstname = trim($_POST['firstname']);
            } else {
                $reg_errors['firstname'] = 'Please enter your first name!';
            }
            //2. Check for a last name:
            //  rules:  same as first name           
            if (preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['lastname'])) {
                $lastname = trim($_POST['lastname']);
            } else {
                $reg_errors['lastname'] = 'Please enter your last name!';
            }
            //3. Check for email (valid email address format)
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email = trim($_POST['email']);
            } else {
                $reg_errors['email'] = 'Please enter a valid email!';
            }

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
            
            /*  end validation    */
            if (empty($reg_errors)) {
                //Validation OK: Create User 
                
                //var_dump($_POST);
          
                //Reading post inputs
                $email = $_POST['email'];
                $password = $_POST['password2'];
                $first_name = $_POST['firstname'];
                $last_name = $_POST['lastname'];

                
                //1.==============  Check for duplicate email ==============
                $stmt = $dbc->prepare("SELECT COUNT(*)
                                       FROM users
                                       WHERE email=:email");
                
                $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                $stmt->execute();
                $num_rows = $stmt->fetchColumn();
                
                if($num_rows==0){
                    //no duplicate email was found - good to go
                    //echo "good to go";                

                    //============== add user to database ===============
                    
                    //create a password hash
                    $salted_password = password_hash($password,PASSWORD_BCRYPT);
                    
                    
                    //generate a random activation code and hash it
                    $active =  md5(uniqid(rand(),true));
                    
                    //echo $salted_password;
                    //echo "<hr>";
                    //echo $active;
                    //exit();
                    
                    //prepare the statement
                    $stmt = $dbc->prepare("INSERT INTO users(email,pass,first_name,last_name,date_expires,active)
                                           VALUES(:email,:pass,:fname,:lname,SUBDATE(NOW(), INTERVAL 1 DAY),:active)");
                
                    //bind the params
                     $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                     $stmt->bindValue(':pass',$salted_password,PDO::PARAM_STR);
                     $stmt->bindValue(':fname',$first_name,PDO::PARAM_STR);
                     $stmt->bindValue(':lname',$last_name,PDO::PARAM_STR);
                     $stmt->bindValue(':active',$active,PDO::PARAM_STR);
                     
                     
                     //execute the statement
                     $result = $stmt->execute();
                     
                     if($result){
                        // User successfully inserted in database
                
                        //3.============== send activation email ===============
                        //3.1.  Prepare to send email
                        $siteURL = "http://localhost:8888/InClassPDODemos/activate.php?x=" . urlencode($email) . "&y=$active";
                        $mailSubject = 'Knowledge Is Power Registration';
                        $messageHTML="<p><strong>Thank you for registering at Knowledge is Power.</strong></p> 
                                        <p>To activate your account, please click on this link:</p>
                                        <a href='$siteURL'>Activate our Account</a>";
                        $messageText = "Thank you for registering at Knowledge Is Power.\n\n
                                            To activate your account please click on this link:  ". $siteURL;
                        $fromEmail =  "mwilliams@oultoncollege.com" ;                          
                        $fromName = "Marc Williams";
                        $toEmail = $email;
                        $toName = $firstname . ' ' . $lastname;


                        //get mail function
                        require './mail/sendGridMail.php';
                        
                        //send it
                        $response = sendMail($mailSubject,$messageHTML,$messageText,$fromEmail,$fromName,$toEmail,$toName);
                        
                        if($response->statusCode()===202){
                            //=================== mail success ===================
                            echo '<div class="alert alert-success"><strong>Account Registered</strong>
                                    <p>A confirmation email has been sent to your email address.  
                                        Please click on the link in that email in order to activate 
                                        your account.
                                    </p>
                                  </div>';
                        }else{
                            //=================== mail failure =================== 
                            echo '<div class="alert alert-success"><strong>Account Registered</strong>
                                <p>Warning:  There was a problem sending a confirmation email to the following email: <strong>' .
                                $email . '</strong>.</p> <p>Please contact customer support!</p>                                 
                              </div>';    
                        }                                             
  
                     }else{
                         //false - failure to insert a new user
                         echo '<div class="alert alert-danger"><strong>Registration Failed</strong>
                                <p>A server error has occured and your account has not been 
                                   registered!
                                </p>
                              </div>';
                     }
                     
                    //finish page:  hide form
                    echo '</div>';
                    include './includes/footer.php'; //footer
                    exit();  
                
                
                }else{
                    //duplicate email - problem
                    //echo "duplicate email";
                    echo '<div class="alert alert-warning"><strong>Email Problem</strong>
                        <p>Your email has been registered. Please pick another email.</p>
                      </div>';
                }

                
            } else {
                //Validation Errors: Display Errors
                //var_dump($reg_errors);
                echo '<div class="alert alert-danger">';
                echo '<ul>';
                foreach ($reg_errors as $error) {
                    echo "<li>$error</li>";
                }
                echo '</ul>';
                echo '</div> ';
            }//END IF Reg errors
        }//End IF POST
    ?>
    <!-- end breadcrumb -->
    <form method="post" action="register.php" class="mb-4">
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-6">
                    <label for="firstname">First name</label>
                    <input class="form-control" id="firstname" name="firstname"
                           type="text"  
                           oninvalid="this.setCustomValidity('Please enter your first name')" 
                           oninput="setCustomValidity('')"
                           placeholder="Enter first name" required autofocus
                           value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
                </div>
                <div class="col-md-6">
                    <label for="lastname">Last name</label>
                    <input class="form-control" id="lastname" name="lastname"
                           type="text"  
                           oninvalid="this.setCustomValidity('Please enter your last name')" 
                           oninput="setCustomValidity('')"
                           placeholder="Enter last name" required
                           value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input class="form-control" id="email" name="email"
                   type="email" oninvalid="this.setCustomValidity('Please enter your email')" 
                   oninput="setCustomValidity('')"                   
                   placeholder="Enter email" required
                   value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-6">
                    <label for="password1">Password</label>
                    <input class="form-control" id="password1" name="password1"
                           type="password" 
                           oninvalid="this.setCustomValidity('Please enter password')" 
                           oninput="setCustomValidity('')" autocomplete="off"                  
                           placeholder="Enter password" required>
                </div>
                <div class="col-md-6">
                    <label for="password2">Confirm password</label>
                    <input class="form-control" id="password2" name="password2"
                           type="password" 
                           oninvalid="this.setCustomValidity('Please confirm password')" 
                           oninput="setCustomValidity('')" autocomplete="off"                 
                           placeholder="Confirm password" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form> 
    <div class="mt-4">&nbsp;</div>
</div>