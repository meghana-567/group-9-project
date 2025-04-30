<?php
	include_once("header.php");
	include_once("Database/connect.php");

	if(isset($_POST['submit']))
	{
		
		$name = trim($_POST['nm']);
		$surnm = trim($_POST['surnm']);
		$unm = trim($_POST['unm']);
		$email = trim($_POST['email']);
		$pswd = $_POST['pswd'];
		$mo = trim($_POST['mo']);
		$adrs = trim($_POST['adrs']);

		
		if(empty($name) || empty($surnm) || empty($unm) || empty($email) || empty($pswd) || empty($mo)) {
			echo "<script>alert('Please fill all required fields');</script>";
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "<script>alert('Invalid email format');</script>";
		} else {
			
			$stmt = $conn->prepare("SELECT unm FROM registration WHERE unm = ?");
			$stmt->bind_param("s", $unm);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows > 0) {
				echo "<script>alert('Username already exists');</script>";
			} else {
				
				$hashed_password = password_hash($pswd, PASSWORD_DEFAULT);

				
				$stmt_insert = $conn->prepare("INSERT INTO registration (nm, surnm, unm, email, pswd, mo, adrs) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$stmt_insert->bind_param("sssssss", $name, $surnm, $unm, $email, $hashed_password, $mo, $adrs);
				if($stmt_insert->execute()) {
					
					$stmt_login = $conn->prepare("INSERT INTO login (unm, pswd) VALUES (?, ?)");
					$stmt_login->bind_param("ss", $unm, $hashed_password);
					if($stmt_login->execute()) {
						echo "<script>alert('Please first login to your account');</script>";
						echo "<script>window.location.assign('login.php');</script>";
					} else {
						echo "<script>alert('Error creating login credentials');</script>";
					}
				} else {
					echo "<script>alert('Registration failed');</script>";
				}
			}
			$stmt->close();
		}
	}
?>

<div class="banner about-bnr">
    <div class="container">
    </div>
</div>
<div class="codes">
    <div class="container">
        <h2 class="w3ls-hdg" align="center">Registration Form</h2>

        <div class="grid_3 grid_4">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="" method="post" name="reg" onsubmit="return validate(this)">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" pattern="[A-Za-z\s]{2,30}"
                                    title="Only Letter For Name" name="nm" id="focusedinput" placeholder="Name"
                                    required=""
                                    value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Surname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="surnm" pattern="[A-Za-z\s]{2,30}"
                                    id="focusedinput" placeholder="Surname" required=""
                                    value="<?php echo isset($_POST['surnm']) ? htmlspecialchars($_POST['surnm']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">User Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="unm" id="focusedinput"
                                    placeholder="User Name" required=""
                                    value="<?php echo isset($_POST['unm']) ? htmlspecialchars($_POST['unm']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smallinput" class="col-sm-2 control-label label-input-sm">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control1 input-sm"
                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Enter Proper Email Id"
                                    name="email" id="smallinput" placeholder="Email"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control1" name="pswd"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                    title="Must Contain At Least One Number & One Uppercase & One Lowercase Letter, & At Least 8 Or More Characters"
                                    id="inputPassword" placeholder="Password" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="smallinput" class="col-sm-2 control-label label-input-sm">Mobile no</label>
                            <div class="col-sm-8">
                                <input type="text" onkeydown="return onlyNumbers(event);"
                                    pattern="([7-9]{1})+([0-9]{9})" title="Only Number" class="form-control1 input-sm"
                                    name="mo" maxlength="10" id="smallinput" placeholder="Mobile no" required=""
                                    value="<?php echo isset($_POST['mo']) ? htmlspecialchars($_POST['mo']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtarea1" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-8"><textarea name="adrs" id="txtarea1" cols="50" rows="4"
                                    class="form-control1"><?php echo isset($_POST['adrs']) ? htmlspecialchars($_POST['adrs']) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="contact-w3form" align="center">
                            <input type="submit" name="submit" value="SEND">
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