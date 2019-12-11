<?php 

require '../vendor/autoload.php';
//https://sendgrid.com/docs/api-reference/





    function SendMail($mailSubject,  $messageHTML,
                            $messageText, $fromEmail,
                            $fromName, $toEmail, $toName){
        //Instantiate the SendGrid object
        $email = new \SendGrid\Mail\Mail(); 
      
        $email->addTo($toEmail, $toName);        
        $email->setSubject($mailSubject);        
        $email->setFrom($fromEmail, $fromName);       
        $email->addContent("text/plain", $messageText);       
        $email->addContent("text/html",$messageHTML);


          //API Key
          $sendgrid = new \SendGrid('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');

        //try {
            $response = $sendgrid->send($email);
            
       // } catch (Exception $e) {
       //     echo 'Caught exception: '. $e->getMessage() ."\n";
       // }

       return $response;

    }

