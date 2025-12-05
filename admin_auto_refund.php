<?php
include "config.php";

require "includes/src/PHPMailer.php";
require "includes/src/SMTP.php";
require "includes/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

// fetch all cancelled bookings
$q = $conn->query("
    SELECT * FROM bookings
    WHERE status='CANCELLED'
    AND refund_status!='COMPLETED'
");

while($b = $q->fetch_assoc()) {

    $cancel_time = strtotime($b['cancelled_at']);
    $now = time();

    // If 24 hrs passed
    if(($now - $cancel_time) >= 86400){

        // update
        $conn->query("UPDATE bookings SET refund_status='COMPLETED' WHERE id=".$b['id']);

        // EMAIL
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host="smtp.gmail.com";
            $mail->SMTPAuth=true;
            $mail->Username="YOUR_EMAIL@gmail.com";
            $mail->Password="YOUR_APP_PASSWORD";
            $mail->Port=587;
            $mail->setFrom("YOUR_EMAIL@gmail.com","RoyalStay Refund");
            $mail->addAddress($b['email']);
            $mail->isHTML(true);
            $mail->Subject="Refund Completed – RoyalStay";
            $mail->Body="
                <h3>Your refund is now completed.</h3>
                <p>Booking ID: <b>".$b['id']."</b></p>
                <p>Amount Refunded: ₹".$b['room_price']."</p>
            ";
            $mail->send();
        }catch(Exception $e){}
    }
}

echo "Auto refund check complete.";
?>
