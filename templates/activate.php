<div class="container">
        <h1 class="mt-4 mb-3">Account Activation</h1>        
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Account Activation</li>            
        </ol>
        
<?php
    //Check for required activation parameters (x and y)
if (isset($_GET['x']) && isset($_GET['y'])){
    //good to go
    //Retrieve url params
    $email = $_GET['x'];
    $active = $_GET['y'];
    //var_dump($email);
    //var_dump($active);
    
    $errors = array();
    //validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email address!';
    }   
    
    //validate activation code
    if (strlen($active)!=32) {
       $errors['active'] = 'Invalid activation code!';
    }
    
    //var_dump($errors);
    if (empty($errors)) {
        //ok to proceed - update database
        $stmt = $dbc->prepare("UPDATE users SET active=NULL, 
                                     date_expires=ADDDATE(date_expires, INTERVAL 1 YEAR)
                               WHERE email=:email AND active = :active");

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':active', $active, PDO::PARAM_STR);


        $result = $stmt->execute();
        $count = $stmt->rowCount();

        //Check for successfull update
        if ($count > 0) {
            //User successfully activated
            echo '<div class="alert alert-success"><strong>Account Activated</strong>
                    <p>Proceed to the login page!</p>
                    <a class="btn btn-success" href="login.php">Login</a>
                 </div>';
        } else {
            //Failed to activate user
            echo '<div class="alert alert-danger"><strong>Activation Failed</strong>
                    <p>Account activation has failed!</p>
                 </div>'; 
        }
 
        
    }else{
        //Validation Errors: Display Errors
        //var_dump($reg_errors);
        echo '<div class="alert alert-danger">';
            echo '<ul>';
                    foreach($errors as $error){
                        echo "<li>$error</li>";
                    }
            echo '</ul>';
        echo '</div> ';
    }  
         
    
}else{
    //missing
    echo '<div class="alert alert-danger"><strong>Activation Failed</strong>
            <p>This page has been accessed in error.</p>
         </div>';   
}
?>        
</div>