<?php
require_once __DIR__ . '/db.php';

$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['testName']) && isset($_GET['value'])) {
    try {
        Database::insertTestResult($_GET['testName'], $_GET['value']);
        exit;
    } catch (Exception $e) {
        $error = 'Erreur lors de l\'insertion du résultat : ' . $e->getMessage();
    }
}


try {
    $testNames = Database::getTests();
} catch (Exception $e) {
    $error = 'Erreur lors de la récupération des tests : ' . $e->getMessage();
    $testNames = [];
}

try {
    if (isset($_GET['testName']) && !empty($_GET['testName'])) {
        $rows = Database::getTestResults($_GET['testName']);
    } else {
        $rows = [];
    }
} catch (Exception $e) {
    $error = 'Erreur lors de la récupération des résultats : ' . $e->getMessage();
    $rows = [];
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['testName'])) {
    try {
        Database::deleteTestResults($_GET['testName']);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $error = 'Erreur lors de la suppression des résultats : ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sélecteur de test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Sélectionnez un test</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="get" action="">
        <label for="testName">Test :</label>
        <select name="testName" id="testName" onchange="this.form.submit()">
            <option value="">-- Choisissez un test --</option>
            <?php foreach ($testNames as $testName): ?>
                <option value="<?php echo htmlspecialchars($testName, ENT_QUOTES, 'UTF-8'); ?>" <?php if (isset($_GET['testName']) && $_GET['testName'] === $testName) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($testName, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit">Voir</button></noscript>
        <?php if (isset($_GET['testName']) && !empty($_GET['testName'])): ?>
        <button type="submit" name="action" value="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer tous les résultats de ce test ?')">Supprimer les résultats</button>
        <?php endif; ?>
    </form>

    <?php if (isset($_GET['testName'])): ?>
        <h2>Résultats pour <?php echo htmlspecialchars($_GET['testName'], ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if (count($rows) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($rows[0]) as $column): ?>
                            <th><?php echo htmlspecialchars($column, ENT_QUOTES, 'UTF-8'); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun résultat trouvé pour ce test.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
