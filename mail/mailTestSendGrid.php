<?php 

//require '../vendor/autoload.php';
require './sendGridMail.php';

$mailSubject = 'Mail Test';
$messageHTML="<p>You have received a new message from <strong>InClassDemo</strong></p>
              <p>Here are the details:  Hello</p>";
$messageText="You have received a new message from InClassDemo\n
              Here are the details:  Hello";
$fromEmail =  "mwilliams@oultoncollege.com" ;                          
$fromName = "Marc Williams";
$toEmail = "tigerwil@gmail.com";
$toName =  "Marc Williams";


//send it
$response = sendMail($mailSubject,$messageHTML,$messageText,$fromEmail,$fromName,$toEmail,$toName);


var_dump($response);

if($response->statusCode()===202){
    echo "Message has been sent";
}else{
    echo "Mail error: ";
}