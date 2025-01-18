<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
        $telephone = htmlspecialchars(trim($_POST['telephone'])); // Récupérer le numéro de téléphone
        $message = htmlspecialchars(trim($_POST['message']));

        if (empty($nom) || empty($telephone) || empty($message)) {
            echo "Tous les champs sont obligatoires.";
            exit;
        }

        if (!preg_match('/^[0-9]{10,15}$/', $telephone)) { // Valider le numéro de téléphone
            echo "Numéro de téléphone invalide.";
            exit;
        }

        // Insertion des données dans la base de données (table contacts)
        $sql = "INSERT INTO contacts (nom, telephone, message) VALUES (:nom, :telephone, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':telephone', $telephone); // Utiliser le numéro de téléphone
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
            $mail->Body = "<strong>Nom :</strong> $nom<br><strong>Téléphone :</strong> $telephone<br><strong>Message :</strong><br>$message";

            $mail->send();
            echo "Message envoyé avec succès !";
        } catch (Exception $e) {    
            echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
        }
    }
    ?>
</body>
</html>