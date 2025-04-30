<?php
	$conn = mysqli_connect("localhost", "root", "", "classic_events");
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>