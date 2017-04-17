<?php
define("USER", "root");
define("PASS", "");
define("DB", "assignclass");

$connectDB = new DbClass(USER, PASS, DB);
$user = new UserClass();
$form = new FormClass();
$allUsers = $user::getUsers();


if (isset($_POST) ) {
	echo "<pre>";
		print_r($_POST);
	echo "</pre>";
}

/**
* Main class
*/
class DbClass{
	
	function __construct($user, $pass, $dbName) 	{
		$db = mysql_connect('localhost', $user, $pass);
		$db_selected = mysql_select_db($dbName, $db);		
	}
}

/**
* Form class
*/
class FormClass {
	function insertForm($args)	{
		$formStr = "<form method='POST'>";
		foreach ($args as $key => $value) {
			switch ($value['type']) {
				case 'select':
					$formStr .= self::selectField($key, $value['option']);
					break;
				case 'radio':
					$formStr .= self::radioField($key, $value['option']);
					break;				
				default:
					$formStr .= self::simpleField($key, $value['type']);
					break;

			}
		}
		$formStr.= "<input type='submit' value='Добавити користувача'></form>"; 
		return $formStr;
	}

	function selectField($name, $options) {
		$radioOptions = array();
		$optionStr = "<select name='$name'>";
		foreach ($options as $key => $value) {
			$optionStr .= "<option value='$key'>$value</option>";
		}
			$optionStr .= "</select>";		
		return $optionStr;
	}

	function radioField($name, $options) {
		$radioOptions = explode(',', $options);
		//print_r($radioOptions);
		$optionStr = "";
		foreach ($radioOptions as $key) {
			$optionStr .= "<input type='radio' name='$name' value='$key'>";
		}
		return $optionStr;
	}

	function simpleField($name, $type)	{
		return "<input type='".$type."' name='".$name."'>";
	}
}



class UserClass {
	
	function getUsers(){
		$usersArray = array();
		$row = mysql_query("SELECT * FROM users");
		while ( $res = mysql_fetch_assoc($row) ) {
			array_push($usersArray, $res);
		}
		return $usersArray;
	}

	function insertUser($args){
		$name = $args['name'];
		$email = $args['email'];
		$pass = $args['pass'];
		$gender = $args['gender'];
		$skils = $args['skils'];
		$location = $args['location'];
		$sql = mysql_query("INSERT INTO users (name, email, pass, gender, skils, location, register) VALUE ('$name', '$email', '$pass', '$gender', '$skils', '$location') ");
		
		if ( $sql ) {
			$message = "Insert was success";
		} else {
			$message = "Error";
		}

		return $message;
	}

	function editUser($id, $args){
		$name = $args['name'];
		$email = $args['email'];
		$pass = $args['pass'];
		$gender = $args['gender'];
		$skils = $args['skils'];
		$location = $args['location'];
$data = "11-04-2017";
		$sqlUpdateUser = "UPDATE users SET name = '$name', email = '$email', pass = '$pass', gender = $gender, skils = $skils, location = $location, register = '$data' WHERE id = $id";
		mysql_query($sqlUpdateUser);
		return $message;
	}
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Assign Class test work</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
		
		<div class="row">
			<div class="col-md-12">
				<?php 
					$formArgs = array(
							'user_name' => array(
												'type' => 'text',
												),
							'user_email' => array(	
												'type' => 'email',
												),

							'gender' => array(
														'type' => 'radio',
														'option' => 'Man, Wommen',
														),
							'skils' => array(
														'type'=>'text',
														),
							'pass' => array(
														'type' => 'password',
														),
							'pass_corect' => array(
														'type' => 'password',
														),
							'location' => array(
															'type' => 'select',
															'option' => array (
																						"rivne" => "Рівне",
																						"lviv" => "Львів",
																						),
															),
							);
					echo $form::insertForm($formArgs);
				?>
			</div>
		</div>


	<div class="row">
		<div class="col-md-12">
			<a class="btn btn-default" href="/?add_user=true" >Добавити користувача</a>
		</div>	
	</div>

	<div class="row">
		<div class="col-md-12">
			<h1>Assign Class test work</h1>
			<table class="table">
				<tr>
					<td>№ п/п</td>
					<td>І'мя</td>
					<td>Е-mail</td>
					<td>Стать</td>
					<td>Навички</td>
					<td>Проживання</td>
					<td>Дата</td>
					<td>Редагування</td>
				</tr>
					<?php
						$count = 1;
						foreach ($allUsers as $key => $user) {
					?>
					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $user['name']; ?></td>
						<td><?php echo $user['email']; ?></td>
						<td><?php echo $user['gender']; ?></td>
						<td><?php echo $user['skils']; ?></td>
						<td><?php echo $user['location']; ?></td>
						<td><?php echo $user['date']; ?></td>
						<td><a href="/?edit=<?php echo $user['id']; ?>">Edit</a></td>
					</tr>
					<?php
					$count++;		
						}
					?>
			</table>			
		</div>
	</div>
</div>
	
</body>
</html>



