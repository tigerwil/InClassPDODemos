<div class="container">
    <h1 class="mt-4 mb-3">Login</h1>
    <?php
        if($_POST){
            //FORM HAS BEEN POSTED - CHECK LOGIN CREDENTIALS
            
            $success = true;  //testing purposes only
            
            if($success){
                //Login success
                //For testing only - fictional user 
                $_SESSION['user_id'] = 1; //pretend user id 1 is logged in 
                $_SESSION['user_not_expired'] = true;  //pretend user account is valid
                
                $_SESSION['admin']= true;  //pretend the admin user is logged in
                
                // show success and redirect user back to home page (countdown)
                //header('Location: index.php');     
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
                
            }else{
                //login failure - show alert message
                echo '<div class="alert alert-danger" role="alert">
                       <strong>Login Failed<strong>!
                       <p>Invalid credentials entered, please try again.</p>
                      </div>';
                
            }
        }
    
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
