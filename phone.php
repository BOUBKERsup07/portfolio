<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <?php
    // Activer l'affichage des erreurs pour le débogage
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Informations de connexion à la base de données
    $host = 'localhost';     
    $dbname = 'cv';           
    $username = 'root';       
    $password = '';          

    // Connexion à la base de données
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }

    // Vérifier si la méthode de requête est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si tous les champs requis sont définis
        if (isset($_POST['nom'], $_POST['telephone'], $_POST['message'])) {
            // Récupérer et sécuriser les données du formulaire
            $nom = htmlspecialchars(trim($_POST['nom']));
            $telephone = htmlspecialchars(trim($_POST['telephone']));
            $message = htmlspecialchars(trim($_POST['message']));

            // Validation des champs
            if (empty($nom) || empty($telephone) || empty($message)) {
                echo "<p class='error'>Tous les champs sont obligatoires.</p>";
            } else {
                // Validation du numéro de téléphone (uniquement des chiffres, longueur 10 à 15)
                if (!preg_match('/^[0-9]{10,15}$/', $telephone)) {
                    echo "<p class='error'>Numéro de téléphone invalide. Il doit contenir entre 10 et 15 chiffres.</p>";
                } else {
                    // Requête SQL pour insérer les données dans la table `numero`
                    $sql = "INSERT INTO numero (nom, telephone, message) VALUES (:nom, :telephone, :message)";
                    $stmt = $pdo->prepare($sql);

                    // Liaison des paramètres
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':telephone', $telephone);
                    $stmt->bindParam(':message', $message);

                    // Exécution de la requête
                    try {
                        $stmt->execute();
                        echo "<p class='success'>Message enregistré avec succès !</p>";
                    } catch (PDOException $e) {
                        echo "<p class='error'>Erreur lors de l'enregistrement du message : " . $e->getMessage() . "</p>";
                    }
                }
            }
        } else {
            echo "<p class='error'>Tous les champs sont obligatoires.</p>";
        }
    }
    ?>
</body>
</html>