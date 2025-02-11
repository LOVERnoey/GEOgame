<?php
session_start();

include("connection.php");
include("functions.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve form data and check if each field is set
    $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';  // Ensure confirm_password is set

    // Validate if the fields are not empty and the username is not numeric
    if (!empty($user_name) && !empty($password) && !empty($confirm_password) && !is_numeric($user_name)) {

        // Debugging: Check what values are being submitted
        error_log("user_name: " . $user_name);
        error_log("password: " . $password);
        error_log("confirm_password: " . $confirm_password);

        // Check if password matches confirm password
        if ($password !== $confirm_password) {
            $error_message = "Passwords do not match!";
        } 
        // Ensure password meets length requirement (minimum 6 characters)
        elseif (strlen($password) < 6) {
            $error_message = "Password must be at least 6 characters long.";
        } else {
            // Save to the database
            $user_id = random_num(20);
            // Hash the password before storing it

            // Prepare the SQL query to avoid SQL injection
            $query = "INSERT INTO users (user_id, user_name, password) VALUES (?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sss", $user_id, $user_name, $password);

            if ($stmt->execute()) {
                // Redirect to login page after successful signup
                header("Location: login.php");
                die;
            } else {
                // Error while inserting into database
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        // Error when fields are not valid
        if (empty($user_name)) {
            $error_message = "Username is required!";
        } elseif (empty($password)) {
            $error_message = "Password is required!";
        } elseif (empty($confirm_password)) {
            $error_message = "Confirm password is required!";
        } elseif (is_numeric($user_name)) {
            $error_message = "Username should not be a number!";
        } else {
            $error_message = "Please enter valid information!";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Signup</title>
	<style>
		body {
			text-align: center;
		}

		.header {
			display: flex;
			align-items: left;
			justify-content: left;
			margin-bottom: 20px;
		}

		.header img {
			width: 40px;
			height: 40px;
			margin: 20px 5px 0px 40px;
		}

		.header h1 {
			margin: 0;
			font-size: 24px;
			color: #333;
		}

		.form-container {
			display: inline-block;
			text-align: left;
			padding: 20px;
			border-radius: 10px;
			color: white;
		}

		#text {
			font-size: 20px;
			margin-top: 20px;
			background-color: #D9D9D9;
			border-radius: 40px;
			border: none;
			width: 100%;
			padding: 10px;
		}

		#button {

			width: 350px;
			height: 50px;
			color: white;
			border: none;
			cursor: pointer;
			margin-top: 30px;
			margin-left: 5px;
			font-size: 20px;
			padding: 8px 16px 8px 16px;
			border-radius: 40px;
			background-color: #00AAFF;
		}

		#button-login {
			width: 350px;
			height: 50px;
			color: #00AAFF;
			border: none;
			cursor: pointer;
			margin-top: 10px;
			margin-left: 5px;
			font-size: 20px;
			padding: 8px 16px 8px 16px;
			border-radius: 40px;
			border: 1px solid #00AAFF;
			background-color: white;
		}

		#button:hover {
			background-color: #777;
		}

		.error-message {
			color: red;
			font-size: 20px;
			margin-top: 20px;
		}
	</style>
</head>

<body>

	<div class="header">
		<img src="image/iconBB2.png" alt="Budget Buddy">
		<h1 style="font-size: 40px; margin: 16px 10px 0px 5px;">Budget Buddy</h1>
	</div>
	<hr style="width: 100%; color: black">

	<div class="form-container">
		<form method="post">
			<div style="font-size: 60px;margin: 10px; margin-top: -10px; color: black;">Sign Up</div>

			<div style="font-size: 30px; margin: 10px; margin-top: 20px; color: black; ">USERNAME</div>
			<input id="text" type="text" name="user_name" placeholder="Username" style="padding: 14px 20px;"><br><br>
			<div style="font-size: 30px; margin: 10px; margin-top: 20px; color: black; ">PASSWORD</div>
			<input id="text" type="password" name="password" placeholder="Password" style="padding: 14px 20px;"><br><br>
			<div style="font-size: 30px; margin: 10px; margin-top: 20px; color: black; ">CONFIRM PASSWORD</div>
			<input id="text" type="password" name="confirm_password" placeholder="Confirm Password"
				style="padding: 14px 20px;"><br><br>
			<?php
			echo "<div class='error-message' style='margin: 5px 0px 0px 80px; '>$error_message</div>";
			?>

			<input id="button" type="submit" value="Sign Up"
				style="font-size: 30px; margin-left: 30px; wight: 100px"><br><br>


		</form>
		<a href="login.php"><button id="button-login"
				style="font-size: 30px; margin-left: 30px; wight: 100px">Login</button></a<br><br>

	</div>

</body>

</html>