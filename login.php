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
	<div style="width:100%; height:100%; text-align: center; vertical-align: middle; line-height: 100%">
		
		<form	method="POST">
			<input type="password" name="pass">
			<input type="submit" value="Log in">
		</form>
	</div>
	<?PHP
		exit;
	}
?>
