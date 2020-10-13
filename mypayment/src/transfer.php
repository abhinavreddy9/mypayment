<?php
//<!--Start session-->
session_start();
include('connection.php'); 
$email = $_SESSION['email'];
$sendemail = $_POST["sendemail"];
$amount = $_POST["amount"];
$memo = $_POST["memo"];
$sql = "SELECT * FROM users WHERE email = '$sendemail' and activation = 'activated'";
$result = mysqli_query($link, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
}
$results = mysqli_num_rows($result);
if(!$results){
    echo '<div class="alert alert-danger">That email is not registered. Transaction Failed</div>';  exit;
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$results = mysqli_fetch_array($result);
$balance = $results['balance'];
if($balance < $amount){
    echo '<div class="alert alert-danger">Insufficient Balance!</div>'; exit;
}

$sql = "update users set balance = balance + '$amount' where email = '$sendemail'";
$result = mysqli_query($link, $sql);

$sql = "update users set balance = balance - '$amount' where email = '$email'";
$result = mysqli_query($link, $sql);

$sql = "insert into activity (`sender_email`, `rec_email`, `amount`, `comments`) values ('$email', '$sendemail', '$amount', '$memo')";
$result = mysqli_query($link, $sql);


$message = "Thank you for using our Payment System\n\n";
$message .= "You have received $".$amount." from ".$email;
mail($sendemail, 'Payment Confirmation', $message, 'From:'.'<mypayments@rajeevchanchlani.com>');

$message = "Thank you for using our Payment System\n\n";
$message .= "You have sent $".$amount." to ".$sendemail;
if(mail($email, 'Payment Confirmation', $message, 'From:'.'<mypayments@rajeevchanchlani.com>')){
       echo "<div class='alert alert-success'>Transaction Successfully Completed! A confirmation email has been sent to $email.</div>";
}
