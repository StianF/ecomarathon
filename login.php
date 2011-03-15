<?PHP
	session_start();
	if(isset($_GET['log_out'])){
		$_SESSION[logged_in] = false;	
	}

	if(isset($_POST['pass'])){
		if($_POST['pass'] == "ecomarathon"){
			$_SESSION[logged_in] = true;	
		}else{
			echo "Wrong password";
		}
	}
	if(!$_SESSION[logged_in]){
	?>
		<i>The password is at the moment "ecomarathon"</i>
		<form	method="POST">
			<input type="password" name="pass">
			<input type="submit" value="Log in">
		</form>
	<?PHP
		exit;
	}
?>
