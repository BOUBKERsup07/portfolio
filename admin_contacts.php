<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "cv"); 

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les données de la table contacts
$sql = "SELECT id, nom, email, message, date_envoi FROM contacts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Messages</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center text-green-600 mb-8">Liste des Messages Reçus</h1>

        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-green-600 text-white">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Nom</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Message</th>
                    <th class="border border-gray-300 px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Afficher chaque ligne de la table
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='hover:bg-gray-100'>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['id'] . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['nom']) . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['message']) . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['date_envoi'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-red-500 py-4'>Aucun message trouvé.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
