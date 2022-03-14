<?php
require_once("dbconfig.php");

// ตรวจสอบว่ามีการ post มาจากฟอร์ม ถึงจะลบ
if ($_POST){
    // ดึงค่าที่โพสจากฟอร์มตาม name ที่กำหนดในฟอร์มมากำหนดให้ตัวแปร $id
    $id = $_POST['id'];

    // เตรียมคำสั่ง DELETE
    $sql = "DELETE 
            FROM documents
            WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // redirect ไปยังหน้า actor.php
    header("location: doc.php");
} else {
    // ดึงค่าที่ส่งผ่านมาทาง query string มากำหนดให้ตัวแปร $id
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>php db demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h1>ลบข้อมูล</h1>
        <table class="table table-hover">
            <tr>
                <th style='width:120px'>เลขที่คำสั่ง</th>
                <td><?php echo $row->doc_num;?></td>
            </tr>
            <tr>
                <th>ชื่อคำสั่ง</th>
                <td><?php echo $row->doc_title;?></td>
            </tr>
            <tr>
                <th>วันที่เริ่มต้นคำสั่ง</th>
                <td><?php echo $row->doc_start_date?></td>
            </tr>
            <tr>
                <th>วันที่สิ้นสุด </th>
                <td><?php echo $row->doc_to_date;?></td>
            </tr>
            <tr>
                <th>สถานะ</th>
                <td><?php echo $row->doc_status;?></td>
            </tr>
            <tr>
                <th>ชื่อไฟล์เอกสาร</th>
                <td><?php echo $row->doc_file_name?></td>
            </tr>
            <tr>
        </table>
        <form action="deletedoc.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row->id;?>">
            <input type="submit" value="Confirm delete" class="btn btn-danger">
            <button type="button" class="btn btn-warning" onClick="window.history.back()">Cancel Delete</button>
        </form>
</body>

</html>