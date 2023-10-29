<?php
session_start();

$error = false;
//session_destroy();
if (!isset($_SESSION['loggedin'])) {
    $_SESSION['loggedin'] = false;
}
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = false;
}
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = false;
}

$email = filter_input( INPUT_POST, 'email', FILTER_UNSAFE_RAW );
$password = filter_input( INPUT_POST, 'password', FILTER_UNSAFE_RAW );
$fp       = fopen( "./data/users.txt", "r" );
if ( $email && $password ) {
	$_SESSION['loggedin'] = false;
	$_SESSION['user'] = false;
	$_SESSION['role'] = false;
	while ( $data = fgetcsv( $fp ) ) {
		if ( $data[1] == $email && $data[2] == sha1( $password ) || password_verify($password, $data[2]) ) {
			$_SESSION['loggedin'] = true;
			$_SESSION['user'] = $email;
			$_SESSION['role'] = $data[3];
			header('location:index.php');
		}
	}
	if(!$_SESSION['loggedin']) {
		$error = true;
	}
}


if ( isset( $_GET['logout'] ) ) {
	$_SESSION['loggedin'] = false;
	$_SESSION['user'] = false;
	$_SESSION['role'] = false;
	session_destroy();
	header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Example</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <h2>This is a login form</h2>
        </div>
    </div>
    <div class="row">
        <div class="column column-60 column-offset-20">

			<?php
			//echo sha1("rabbit")."<br/>";
			if ( true == $_SESSION['loggedin'] ) {
				echo "Hello Admin, Welcome!";
			} else {
				echo "Hello User, Welcome to my site";
			}
			?>
        </div>
    </div>
    <div class="row" style="margin-top:100px;">
        <div class="column column-60 column-offset-20">
			<?php
			if ( $error ) {
				echo "<blockquote>Username and Password didn't match</blockquote>";
			}
			if ( false == $_SESSION['loggedin'] ):
				?>
                <form method="POST">
                    <label for=email>Email</label>
                    <input type="text" name='email' id="email">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <button type="submit" class="button-primary" name="submit">Log In</button>
                </form>
			<?php
			else:
				?>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="logout" value="1">
                    <button type="submit" class="button-primary" name="submit">Log Out</button>
                </form>
			<?php
			endif;
			?>
        </div>
    </div>
</div>