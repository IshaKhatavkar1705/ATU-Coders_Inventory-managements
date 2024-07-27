<?php
require_once('inc/config/constants.php');
require_once('inc/config/db.php');
include("mail/mail.php");

$checkUserSql = "SELECT * FROM user WHERE username = 'admin'";
$checkUserStatement = $conn->prepare($checkUserSql);
$checkUserStatement->execute();
$email="";

if ($checkUserStatement->rowCount() > 0) {   
    $row = $checkUserStatement->fetch(PDO::FETCH_ASSOC);    
    $email = $row['email'];
}

$itemDetailsSql = "SELECT * FROM item WHERE DATEDIFF(expireDate,CURDATE())<=5 AND DATEDIFF(expireDate,CURDATE())>=0 AND status = 'Active'";
$itemDetailsStatement = $conn->prepare($itemDetailsSql);
$itemDetailsStatement->execute();
$message = "Following items are expire soon.<br><br>
            <table>
            <tr>
            <th>Product id</th>
            <th>Product number</th>
            <th>Product name</th>
            <th>Product Stock</th>
            <th>Expire Date</th>
            </tr>
";

if ($itemDetailsStatement->rowCount() > 0) {

    while ($row = $itemDetailsStatement->fetch(PDO::FETCH_ASSOC)) {
        $message = $message . "
        <tr>
            <td>" . $row['productID'] . "</td>
            <td>" . $row['itemNumber'] . "</td>
            <td>" . $row['itemName'] . "</td>
            <td>" . $row['stock'] . "</td>
            <td>" . $row['expireDate'] . "</td>            
        </tr>
        ";
        
    }

    $message = $message . "</table>";
    $subject = "Expire product list";
    $mailstatus = mailsend($email, $message, $subject, "Inventory System");
    echo "mail send ".$mailstatus;
}


