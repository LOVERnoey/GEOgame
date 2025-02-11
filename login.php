<?php

session_start();

include("connection.php");
include("functions.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//something was posted
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];
	$remember_me = isset($_POST['remember_me']);

	if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {

		//read from database
		$query = "select * from users where user_name = '$user_name' limit 1";
		$result = mysqli_query($con, $query);

		if ($result) {
			if ($result && mysqli_num_rows($result) > 0) {

				$user_data = mysqli_fetch_assoc($result);

				if ($user_data['password'] === $password) {

					$_SESSION['user_id'] = $user_data['user_id'];

					if ($remember_me) {
						setcookie('user_name', $user_name, time() + (86400 * 30), "/"); // 30 days
					} else {
						setcookie('user_name', '', time() - 3600, "/"); // delete cookie
					}

					header("Location: ./user/dashboard.php");
					die;
				}
			}
		}

		$error_message = "wrong username or password!";
	} else {
		$error_message = "wrong username or password!";
	}
}

$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : '';

?>

<!DOCTYPE html>
<html>

<head>
	<title>Login</title>
	<style>
		.card {
			max-width: 800px;
			margin: 50px auto 0 auto; /* Added top margin */
			float: none;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
			border-radius: 10px;
			background-color: #f2f2f2;
			display: flex;
			justify-content: center;
		}
		.card .login {
			width: 48%;
			background-color: white;
			padding: 20px;
			border-radius: 10px 0px 0px 10px;
		}
		.card .signup {
			width: 48%;
			background-color: #578E7E;
			padding: 20px;
			border-radius: 0px 10px 10px 0px;
		}
		.card input[type="text"], .card input[type="password"] {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			display: inline-block;
			border: 1px solid #ccc;
			box-sizing: border-box;
		}
		.card input[type="submit"] {
			background-color: #4CAF50;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
			border: none;
			cursor: pointer;
			width: 100%;
		}
		.card input[type="submit"]:hover {
			opacity: 0.8;
		}
	</style>
</head>

<body>
	<div>
		<div style="text-align: center; margin-top: 50px;">
			<a href="index.html"><img src="image/logo.jpg" alt="Budget Buddy Icon" style="vertical-align: middle; width: 500px; height: 150px; margin-top: 40px;"></a>
		</div>
	</div>
	<div class="card">
		<div class="login">
			<form method="post">
				<div style="font-size: 40px;margin: 10px;color: 578E7E; ">Login</div>

				<div style="font-size: 20px; margin: 10px; margin-top: 20px; color: 578E7E; ">USERNAME</div>
				<input id="text" type="text" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" style="margin: 5px; background-color: #D9D9D9; border-radius: 40px; border: none;" placeholder="Username" ><br><br>
				<div style="font-size: 20px;margin: 10px;color: 578E7E; ">PASSWORD</div>
				<input id="text" type="password" name="password" style="margin: 5px; background-color: #D9D9D9; border-radius: 40px; border: none;" placeholder="Password"><br><br>
				<input id="button" type="submit" value="LOGIN" style="margin-left: 5px; font-size: 20px; padding: 8px 16px 8px 16px; border-radius: 40px; background-color: #578E7E;"><br><br>
			</form>
			<?php if (!empty($error_message)): ?>
		<div style="text-align: center; color: red; margin-top: -10px">
			<?php echo $error_message; ?>
		</div>
	<?php endif; ?>
		</div>
		<div class="signup">
			<div style="text-align: center;">
				<div style="font-size: 40px; margin-top: 100px;color: white;">You New Here?</div>
				<p style="color: white; margin-top: 5px ">Don’t have an account?</p>
				<a href="signup.php">
					<button style="background-color: #578E7E; color: white; padding: 10px 20px; border: 2px solid white; border-radius: 20px; cursor: pointer;  font-size: 20px;">Sign Up</button>
				</a>
			</div>
	</div>
	
</body>

</html>