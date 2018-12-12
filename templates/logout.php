<div class="container">
    <h1 class="mt-4 mb-3">Logout</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Logout</li>            
    </ol>
    <?php
        //unset all of the session variables
        $_SESSION = array();
        
        //destroy the session cookie
        if(ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(),'',
                    time()-42000, 
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]);
        }
        //Finally destroy the session object
        session_destroy();
    
    ?>
    <div class="alert alert-success" role="alert">
        <strong>You are now logged out.</strong>  
        Please come back soon!
    </div>   
    
    <script>
        var delay = 5;
        var url = 'index.php';
        function countdown(){
            setTimeout(countdown,500);
            delay--;
            if (delay<0){
                window.location = url;
                delay=0;
            }
        
        }
        
        countdown();
    </script>
    
</div>