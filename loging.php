<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "latitudemx";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["username"];
    $contraseña = $_POST["password"];

    // Aquí puedes realizar las validaciones necesarias y consultar la base de datos
    // para verificar si el usuario y la contraseña son correctos
    // Utiliza las funciones de PHP y SQL para realizar las consultas necesarias

    // Ejemplo de consulta para verificar si el usuario y la contraseña coinciden
    $sql = "SELECT * FROM accesos WHERE CORREO = '$usuario' AND PASSWORD = '$contraseña'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // El usuario y la contraseña son correctos, se puede permitir el acceso
        // Guardar información en la sesión
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $usuario;

        // Redirigir al usuario a otra página o mostrar un mensaje de éxito
        header("Location: inicio.php");
        exit();
    } else {
        // La contraseña es incorrecta, muestra un mensaje de error en el formulario
        $error_message = "La contraseña es incorrecta. Inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/loging.css">
  <link rel="icon" type="image/x-icon" href="img/latitude.ico"> 
  <title>Formulario de Inicio de Sesión</title>
</head>
<body>
  <div class="container">
  <a href="https://www.latitude.mx"><img src="img/latitude2.png"></a>
    <h2>Iniciar Sesión</h2>
    <?php if (isset($error_message)) { ?>
      <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="form-group">
        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Iniciar Sesión">
      </div>
    </form>
  </div>
</body>
</html>
