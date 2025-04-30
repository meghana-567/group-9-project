<?php
include_once("header.php");
include('../Database/connect.php');
session_start();

if(isset($_POST['submit'])) {
    $fnm = $_FILES["image"]["name"];
    $nm = trim($_POST['nm']);
    $pr = trim($_POST['price']);

    $errors = [];
    if(empty($fnm)) {
        $errors[] = "Image is required.";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if(!in_array($_FILES["image"]["type"], $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF files are allowed.";
        }
    }
    if(empty($nm)) {
        $errors[] = "Name is required.";
    }
    if(empty($pr) || !is_numeric($pr)) {
        $errors[] = "Valid price is required.";
    }

    if(count($errors) == 0) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($fnm);

        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if(isset($_SESSION['admin'])) {
                $stmt = $con->prepare("INSERT INTO anniversary (img, nm, price) VALUES (?, ?, ?)");
                $stmt->bind_param("ssd", $fnm, $nm, $pr);
                if($stmt->execute()) {
                    echo "<script>alert('Added');</script>";
                    echo '<script type="text/javascript">window.location="anni_disp.php";</script>';
                } else {
                    echo "<script>alert('Not added');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Unauthorized access');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file');</script>";
        }
    } else {
        $error_msg = implode("\\n", $errors);
        echo "<script>alert('$error_msg');</script>";
    }
}
?>
<div class="codes">
    <div class="container">
        <h3 class='w3ls-hdg' align="center">EDIT ANNIVERSARY</h3>
        <div class="grid_3 grid_4">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Enter Image</label>
                            <div class="col-sm-8">
                                <input type="file" name="image">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Enter Price</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="price" id="focusedinput"
                                    placeholder="Theme Price"
                                    value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtarea1" class="col-sm-2 control-label">Enter Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="nm" id="focusedinput"
                                    placeholder="Theme Name"
                                    value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : ''; ?>">
                            </div>
                        </div>
                        <div class="contact-w3form" align="center">
                            <input type="submit" name="submit" class="btn" value="SEND">
                            <input type="button" value="DISPLAY" class="btn my" onClick="javascript:location.href='anni_disp.php'" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once("footer.php");
?>
