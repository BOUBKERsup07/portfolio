<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>message</title>
</head>
<body>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'vendor/autoload.php';
    
    $host = 'localhost';      // Adresse de votre serveur MySQL
    $dbname = 'cv';     // Nom de votre base de données
    $username = 'root'; // Nom d'utilisateur MySQL
    $password = ''; // Mot de passe MySQL
    
    // Connexion à la base de données
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $message = htmlspecialchars(trim($_POST['message']));
    
        if (empty($nom) || empty($email) || empty($message)) {
            echo "Tous les champs sont obligatoires.";
            exit;
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Adresse email invalide.";
            exit;
        }
    
        // Insertion des données dans la base de données (table contacts)
        $sql = "INSERT INTO contacts (nom, email, message) VALUES (:nom, :email, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
    
        try {
            $stmt->execute();
            echo "Bien :";
        } catch (PDOException $e) {
            echo "**" . $e->getMessage();
        }
    
        $mail = new PHPMailer(true);
    
        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cheyoukhb@gmail.com'; 
            $mail->Password = 'qbfc qymh bqkv sshl'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Destinataires
            $mail->setFrom('cheyoukhb@gmail.com', 'Nom du site');
            $mail->addAddress('cheyoukhb@gmail.com'); 
    
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = "Nouveau message de $nom";
            $mail->Body = "<strong>Nom :</strong> $nom<br><strong>Email :</strong> $email<br><strong>Message :</strong><br>$message";
    
            $mail->send();
            echo "Message envoyé avec succès !";
        } catch (Exception $e) {    
            echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
        }
    }    
    ?>
</body>
</html>
