<?php

include('connection.php');

//get the user_id
$email = $_SESSION['email'];

//run a query to look for notes corresponding to user_id
$sql = "SELECT * FROM activity WHERE sender_email ='$email' or rec_email = '$email' ORDER BY time DESC";

//shows notes or alert message
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_array($result)){
            $sender_email = $row['sender_email'];
            $rec_email = $row['rec_email'];
            $amount = $row['amount'];
            $comments = $row['comments'];
            $time = $row['time'];
            if($sender_email==$email){
                $message = '$'.$amount.' Sent to '.$rec_email;
            }
            else{
                $message = '$'.$amount.' Received from '.$sender_email;
            }
            echo "
        <div class='note'>
            
            <div class='noteheader' id='$note_id'>
                <div class='text'>$message</div>
                <div class='text'>$comments</div>
                <div class='timetext'>$time</div>    
            </div>
        </div>";
        }
    }else{
        echo '<div class="alert alert-warning">No Transactions yet!!</div>';
    }
    
}else{
    echo '<div class="alert alert-warning">An error occured!</div>';
//    echo "ERROR: Unable to excecute: $sql." . mysqli_error($link);
}

?>