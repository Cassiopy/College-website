<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Emerald 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20120902

-->
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Laboratorio Programaci&oacute;n III</title>
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
	<div id="wrapper">
		<div id="header-wrapper" class="container">
			<div id="header" class="container">
				<div id="logo">
					<h1><a href="#">Usuarios</a></h1>
				</div>
				<div id="menu">
					<ul>
						<li class="current_page_item"><a href="index.php">Homepage</a></li>
						<li><a href="agregar.php">Nuevo</a></li>
						<li><a href="listar.php">Listar</a></li>
						<li><a href="borrar.php">eliminar</a></li>
					</ul>
				</div>
			</div>
			<div><img src="images/img03.png" width="1000" height="40" alt="" /></div>
		</div>
		<!-- end #header -->

		<div id="page">
			<div id="content">
				<div class="post">
					<h2>Sistema de Administraci&oacute;n de Usuarios</h2>
					<p class="meta"><span class="date"><?php echo date("d - m - Y"); ?></span></p>
					<div style="clear: both;">&nbsp;</div>
					<div class="entry">
						<h3>Nuevo Usuario</h3>
						<br>
						<?php
							function test_input($data)
							{
								$data = trim($data);
								$data = stripslashes($data);
								$data = htmlspecialchars($data);
  								return $data;
							}
							
							$nameErr = $emailErr = $surErr = $nickErr = $adErr = $telErr = "";
							$name = $surname = $email = $nick = $adress = $phone = $gender = "";
							$advice = "";
							$error = $dbconn = false;

							if ($send = isset(S_POST['Registrar']))
							{
								if(empty($_POST["name"]))
								{
									$nameErr = "* Inserte un nombre";
								}
								else
								{
									$name = test_input($_POST["name"]);
									if(!preg_match("/^[a-zA-Z ]*$/",$name))
									{
										$nameErr = "Solo se permiten letras y espacios";
									}
								}

								if(empty($_POST["surname"]))
								{
									$surnameErr = "* Inserte un apellido";
								}
								else
								{
									$surname = test_input($_POST["surname"]);
									if(!preg_match("/^[a-zA-Z ]*$/",$surname))
									{
										$surnameErr = "Solo se permiten letras y espacios";
									}
								}

								if (empty($_POST["email"])) 
								{
									$emailErr = "* Inserte un email";
								} 
								else
								{
									$email = test_input($_POST["email"]);
									// check if email address is well-formed
									if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
									{
										$emailErr = "* Email no válido"; 
									}
								}

								if (empty($_POST["nick"])) 
								{
									$nickErr = "* Inserte un nick";
								} 
								else 
								{
									$nickname = test_input($_POST["nickn"]);
									if (!preg_match("/^[0-9a-zA-Z ]*$/",$nick)) 
									{
										$nickErr = "* Solo se permiten letras y números"; 
									}
								}

								if (empty($_POST["phone"])) 
								{
									$phoneErr = "* Inserte un teléfono";
								} 
								else 
								{
									$phone = test_input($_POST["phone"]);
									if (!preg_match("/^[0-9]*$/",$phone)) 
									{
									  $phoneErr = "* Solo se permiten números"; 
									}
								}
								if (empty($_POST["adress"])) 
								{
									$adErr = "* Ingrese una dirección";
								} 
								else 
								{
									$adress = test_input($_POST["adress"]);
									if (!preg_match("/^[0-9a-zA-Z ]*$/",$adress)) {
									  $adErr = "* Solo se permiten letras y números"; 
									}
								}
							}
							
							$error = !(empty($nameErr)&&empty($surnameErr)&&empty($adError)&&empty($nickErr)&&empty($phoneErr));
					
							if(!$error )
							{
								// Se conecta a la base de datos
								$dbconn = pg_connect("host=localhost dbname=laboratorio user=SofiaFlores password=admin123")
								or die('No se ha podido conectar: ' . pg_last_error());
								// Se busca un usuario con el nick ingresado en el formulario
								$result = pg_query_params($dbconn, 'SELECT * FROM usuario WHERE nick = $1', array($nick));
								if ($line = pg_fetch_assoc($result)) 
								{
									if (count($line) > 0) 
									{
										$nickError = "El nick ya existe";
										$error = true;
									}
								}
							}
							
							if($send && (!$error))
							{
								$array = array($nombre, $apellido, $nick, $email, $direccion, $telefono, $gender);
								$sql = 'INSERT INTO usuario(nombre, apellido, nick, email, direccion, telefono, genero) values ($1, $2, $3, $4, $5, $6, $7);';
								// Se inserta en la base de datos el nuevo usuario
								$result = pg_query_params($dbconn, $sql, $array);
								if(!$result)
								{
									echo "Ha ocurrido un error";
									exit;
								}
								else
								{
									$advice = "El usuario se registró exitosamente";
									$nameErr = $emailErr = $surErr = $nickErr = $adErr = $telErr = "";
									$name = $surname = $email = $nick = $adress = $phone = $gender = "";
								}
							}
						
							// se cierra la conexión a la base de datos
							if ($dbconn) 
							{
								pg_close($dbconn);
							}

							echo "<h2>$advice</h2>";

						?>
						
						<form method = "post" action ="<?php echohtmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<label>Nombre: </label>
							<input type = "text" name = "name" value = "<?php echo $name;?>"/>
							<span class = "error"> <?php echo $nameErr;?> </span>
							<br>

							<label>Apellido: </label>
							<input type = "text" name = "surname" value = "<?php echo $surname;?>"/>
							<span class = "error"> <?php echo $surnameErr;?> </span>
							<br>

							<label>Sexo: </label>
							<select name = "gender">
								<option value = "Hombre">Hombre</option>
								<option value = "Mujer">Mujer</option>
								<option valur = "Otro" selected>Otro</option>
							</select>
							<br>

							<label>Email: </label>
							<input type="text" name="email" value="<?php echo $email;?>" >
							<span class="error"> <?php echo $emailErr;?> </span>
							<br>

							<label>Direccion: </label> 
							<input type="text" name="adress"value="<?php echo $adress;?>">
							<span class="error"> <?php echo $adressErr;?> </span>
							<br>

							<label>Nick: </label>
							<input type="text" name="nick" value="<?php echo $nick;?>">
							<span class="error"> <?php echo $nickErr;?> </span>
							<br>

							<label>Telefono: </label>
            				<input type="text" name="phone" value="<?php echo $phone;?>">
         				    <span class="error"> <?php echo $phoneErr;?> </span>
							<br><br>

							<input type="reset" value="Limpiar">
							<input type="submit" name="submit" value="Registrar"> 
					</form>

					</div>
				</div>
				<div style="clear: both;">&nbsp;</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<div class="container"><img src="images/img03.png" width="1000" height="40" alt="" /></div>
	</div>
	<div id="footer-content"></div>
	<div id="footer">
		<p>Copyright (c) 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org/" rel="nofollow">FreeCSSTemplates.org</a>.</p>
	</div>
</body>

</html>