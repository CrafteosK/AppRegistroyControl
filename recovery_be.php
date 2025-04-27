<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

include 'conexion_be.php';

if (isset($_POST['email'])) {
$email = $_POST['email'];

    // Depuración: Verificar si el correo se recibe correctamente
    if (empty($email)) {
        die("El correo electrónico está vacío.");
    }

$query = "SELECT * FROM usuarios WHERE email = '$email'";
$result = mysqli_query($enlace, $query);

    if ($result && $result->num_rows > 0) {
$row2 = $result->fetch_assoc();

// Cambiar 'id' por 'ID' para que coincida con la estructura de la tabla
if (isset($row2['ID'])) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kervindiaz2021@gmail.com';
        $mail->Password   = 'udgcpaaykjtrrhsr';
        $mail->Port       = 587;

        $mail->setFrom('kervindiaz2021@gmail.com', 'Kervin Diaz');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';
        $mail->Body = 'Hola, si te llegó este correo es porque has solicitado recuperar tu contraseña.<br>
        <br>Si no has solicitado este correo, por favor ignora este mensaje.<br><br>
        Entra al siguiente link para cambiar la contraseña: <a href="http://localhost/AppRegistroyControl/change_password.php?id=' . htmlspecialchars($row2['ID']) . '">Recuperar Contraseña</a>';
        $mail->AltBody = 'Este es el mensaje en texto plano para clientes que no soportan HTML.';

        $mail->send();
        header("Location: index.php?message=ok");
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            echo "No se encontró el ID del usuario.";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }
} else {
    header("Location: index.php?message=not_found");
}
?>
