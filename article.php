<?php
    $page_title = 'Article';
    $current= "article";
    include 'includes/header.php';
    if(isset($_SESSION['user_id'])){
        //user is logged in and account is not expired - show article
         include 'templates/article.php';
    }else{
        //user is not logged in 
        include 'templates/membersonly.php';
    }     
    include 'includes/footer.php';