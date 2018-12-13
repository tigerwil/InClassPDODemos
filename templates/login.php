<div class="container">
    <h1 class="mt-4 mb-3">Login</h1>
    <?php
        if($_POST){
            //FORM HAS BEEN POSTED - CHECK LOGIN CREDENTIALS
            
            //get post params
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            //Attempt login
            $stmt = $dbc->prepare("SELECT COUNT(*) FROM users 
                                   WHERE email = :email");
            $stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $stmt->execute();
            $num_rows = $stmt->fetchColumn();
            
            if($num_rows==1){
                //valid email - test password
                //echo "valid email";
                //Test actual login email + password pairing
                $stmt = $dbc->prepare("SELECT pass from users 
                                       WHERE email = :email");
                $stmt->bindValue(':email', $email,PDO::PARAM_STR);
                $stmt->execute();
                $row= $stmt->fetchColumn();
                
                //var_dump($row);
                //exit();
                
                if(password_verify($password, $row)){
                    //Password is valid - return that user's information
                    //prepare the statement
                    $stmt = $dbc->prepare("SELECT id, type, email, first_name, last_name,
                                                  IF(date_expires>=NOW(),true,false) as notexpired,
                                                  IF(type='admin',true,false) as admin
                                           FROM users
                                           WHERE email = :email");
                    //bind the query parameters
                    $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                    //execute the query
                    $stmt->execute();
                    //fetch our row 
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    //var_dump($user);
                    //exit();
                    
                    //Populate the SESSION object with user info
                    $_SESSION['user_id'] = $user['id']; //who is this user
                    $_SESSION['user_not_expired'] = $user['notexpired']; //is account expired or not                     
                    $_SESSION['fullname'] = $user['first_name'].' '. $user['last_name']; //user fullname
                    $_SESSION['admin']= $user['admin'];  // is the user a member or admin
                    
                    echo '<div class="alert alert-success" role="alert">
                              <strong>Login Success<strong>!
                              <p>You will be rediredted to the home page in <span id="count"></span> seconds...</p>
                          </div>'; 
                
                    echo "<script>
                              var delay = 3;
                              var url = 'index.php';
                              function countdown(){
                                   setTimeout(countdown,1000);
                                   $('#count').html(delay);
                                   delay --;
                                   if(delay <0){
                                       window.location = url;
                                       delay = 0;
                                   }
                               }
                               countdown();
                           </script>";
                    
                    //finish the page
                    echo "</div>";//for container
                    include './includes/footer.php';//footer
                    exit();
                    
                    
                }else{
                    //invalid password - show alert';
                    echo '<div class="alert alert-danger" role="alert">
                              <strong>Login Failed<strong>!
                              <p>Invalid credentials entered, please try again.</p>
                         </div>';
                }//end of password verify
                        
    
                
            }else{
                //invalid email - show message
                //echo "invalid email";
                echo '<div class="alert alert-danger" role="alert">
                       <strong>Login Failed<strong>!
                       <p>Invalid credentials entered, please try again.</p>
                      </div>';
            }//end of email check
            
           
            
            

            
        }//end if post
    
    ?>
    
    
    
    <div class="nav justify-content-center">
        <i class="fas fa-user-lock fa-5x"></i>
    </div>
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Please sign in</div>
            <div class="card-body">
                <form method="post" action="login.php" novalidate>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input class="form-control" id="email" name="email" 
                               type="email" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input class="form-control" id="password" name="password"
                               type="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox"> Remember Password</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div class="text-center">
                    <a class="d-block small mt-3" href="register.php">Register an Account</a>
                    <a class="d-block small" href="forgot-password.php">Forgot Password?</a>
                </div>
        </div>
    </div>    
    
    <div class="mt-4">&nbsp;</div>
</div>
