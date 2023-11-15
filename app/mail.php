
<?php
         $to_address = "sender@test.com";       
         $from_address = "from@test.com";
         $subject = "Test_subject";
         
         //Sending a mail
         $res =  imap_mail($to_address, $from_address, $subject);
         if($res){
            print("Mail sent successfully");
         }else{
            print("Error Occurred");
         }
?>