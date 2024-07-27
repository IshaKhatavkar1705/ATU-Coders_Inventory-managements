<?php
require_once('inc/config/constants.php');
require_once('inc/config/db.php');
include("mail/mail.php");

$itemDetailsSql = "SELECT v.fullName,v.email, i.itemName, i.stock FROM  vendor as v INNER JOIN purchase as p ON v.vendorID = p.vendorID INNER JOIN item as i on p.itemNumber = i.itemNumber WHERE i.stock < 10 AND i.status='Active';";
$itemDetailsStatement = $conn->prepare($itemDetailsSql);
$itemDetailsStatement->execute();

if ($itemDetailsStatement->rowCount() > 0) {
    $count =0;
    while ($row = $itemDetailsStatement->fetch(PDO::FETCH_ASSOC)) {
        $message = "Dear ".$row['fullName']."<br>".
        "We are from DYP Shop. Following product is less in quantity in our shop. Please provide fresh stock for us<br> ".
        "Product name : ".$row['itemName']."<br>".
        "Product stock : ".$row['stock']."<br><br>".

        "Regards<br> DYP Shop"
        ;
        $subject = "Product requirement at DYP shop";  
        $mailstatus = mailsend($row['email'], $message, $subject, "Inventory System");
        $count++;
    }   
    echo $count . " mail send ";
}


