<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    header("location: login.php");
}

if(!isset($_SESSION['loggedin'])){
    header("location: login.php");
}
require_once("dbconfig.php");


if ($_POST){
    $id = $_POST['id'];
    $doc_num = $_POST['doc_num'];
    $doc_title = $_POST['doc_title'];
    $doc_start_date = $_POST['doc_start_date'];
    $doc_to_date = $_POST['doc_to_date'];
    $doc_status = $_POST['doc_status'];
    $doc_file_name = $_FILES["doc_file_name"]["name"];
   
  


    //$doc_file_name = isset($_FILES["doc_file_name"]["name"]) ? $_FILES["doc_file_name"]["name"] : ""; 

   /* print_r($_FILES);


    $doc_file_name = "";
    if(array_key_exists("doc_file_name", $_FILES)){
        $doc_file_name = $_FILES["doc_file_name"]["name"];
    }else{
        $doc_file_name = "";
    }
    echo $doc_file_name."Hello";
*/
    if($doc_file_name<>""){
        $sql = "UPDATE documents 
            SET doc_num = ?, 
                doc_title = ?,
                doc_start_date = ?,
                doc_to_date = ?,
                doc_status = ?,
                doc_file_name = ?
            WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssi",$doc_num,$doc_title,$doc_start_date,$doc_to_date,$doc_status,$doc_file_name, $id);
        $stmt->execute();

        //uploadpart
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["doc_file_name"]["name"]);
        if (move_uploaded_file($_FILES["doc_file_name"]["tmp_name"], $target_file)) {
            //echo "The file ". htmlspecialchars( basename( $_FILES["doc_file_name"]["name"])). " has been uploaded.";
        } else {
            //echo "Sorry, there was an error uploading your file.";
        }


    }else {
        $sql = "UPDATE documents 
            SET doc_num = ?, 
                doc_title = ?,
                doc_start_date = ?,
                doc_to_date = ?,
                doc_status = ?
            WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssi",$doc_num,$doc_title,$doc_start_date,$doc_to_date,$doc_status,$id);
        $stmt->execute();
    }


    header("location: document.php");
} else {
    $id = $_GET['id'];
    $sql = "SELECT *
            FROM documents 
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_object();
}
echo "Welcome ".$_SESSION['stf_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>แก้ไขคำสั่งแต่งตั้ง</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>แก้ไขคำสั่งแต่งตั้ง</h1>
        <form action="editdocument.php" method="post"  enctype="multipart/form-data">
            <div class="form-group">
                <label for="doc_num">เลขที่คำสั่ง</label>
                <input type="text" class="form-control" name="doc_num" id="doc_num" value="<?php echo $row->doc_num;?>">
            </div>
            <div class="form-group">
                <label for="doc_title">ชื่อคำสั่ง</label>
                <input type="text" class="form-control" name="doc_title" id="doc_title" value="<?php echo $row->doc_title;?>">
            </div>
            <div class="form-group">
                <label for="doc_start_date">วันที่เริ่มต้นคำสั่ง</label>
                <input type="date" class="form-control" name="doc_start_date" id="doc_start_date" value="<?php echo $row->doc_start_date;?>">
            </div>
            <div class="form-group">
                <label for="doc_to_date">วันที่สิ้นสุด</label>
                <input type="date" class="form-control" name="doc_to_date" id="doc_to_date" value="<?php echo $row->doc_to_date;?>">
            </div>
            <div class="form-group">
                <label for="doc_status">สถานะเอกสาร</label>
                <input type="radio"  name="doc_status" id="doc_status" value="Active"
                    <?php if($row->doc_status == "Active"){echo "checked";}?>> Active
                <input type="radio"  name="doc_status" id="doc_status" value="Expire"
                    <?php if($row->doc_status == "Expire"){echo "checked";}?>> Expire
            </div>
            <div class="form-group">
                <label for="doc_file_name">อัพโหลดไฟล์</label>
                <input type="file" class="form-control" name="doc_file_name" id="doc_file_name" >
            </div>
            <br>
            <input type="hidden" name="id" value="<?php echo $row->id;?>">
            <button type="button" class="btn btn-warning" onClick="window.history.back()">Back</button>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
</body>

</html>
