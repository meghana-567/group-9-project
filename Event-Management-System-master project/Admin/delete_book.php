<?php
			include('../Database/connect.php');
			
			$de="delete from booking where id=".$_GET['id'];
			mysqli_query($conn,$de);
			echo '<script type="text/javascript">
				window.location="view_order.php";
			</script>';
			
?>