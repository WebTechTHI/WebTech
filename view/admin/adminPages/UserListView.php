<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Bestellungen</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <!-- javascript -->
    <script src="/assets/javascript/admin/changeStatusUser.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>


    <div class="page-wrapper">

        <a class="back-last-page" href="/index.php?page=admin">Zurück</a>

        <div class="orders-wrapper">
            <?php foreach ($users as $user): ?>
                <div class="user">
                    <div class="user-header">
                        <div>Benutzer-ID: #<?= $user['user_id'] ?></div>
                        <div><?= $user['role_id'] ?></div>
                    </div>
                        <div class="user-info">
                            <div class="user-label-wrapper">
                                <strong>Benutzer-ID:</strong>
                                <div><?= $user['user_id'] ?></div>
                            </div>
                            <div class="user-label-wrapper">
                                <strong>Benutzername:</strong>
                                <div><?= $user['username'] ?></div>
                            </div>
                            <div class="user-label-wrapper">
                                <strong>Adresse: </strong>
                                <div class="adress">
                                    <div><?= $user['stadt'] ?>, <?= $user['plz'] ?></div>
                                    <div><?= $user['straße'] ?></div>
                                    <strong>
                                        <div><?= $user['land'] ?></div>
                                    </strong>
                                </div>
                            </div>
                            <div class="user-label-wrapper">
                                <strong>E-Mail:</strong>
                                <div><?= $user['email'] ?></div>
                            </div>
                        </div>
                    <div class="user-status-more">
                        <div class="status-color" id="status-color-user-<?= $user['user_id'] ?>" style="background-color:
                                <?= $user['role_id'] == 1 ? 'crimson' : '' ?>
                                <?= $user['role_id'] == 2 ? 'forestgreen' : '' ?>">
                        </div>
                        <div class="status-more-wrapper">
                            <div class="status-styling">
                                <label for="status-select-user-<?= $user['user_id'] ?>">Berechtigungen:</label>
                                <select id="status-select-user-<?= $user['user_id'] ?>"
                                    onchange="changeUserStatus(<?= $user['user_id'] ?>)">
                                    <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin
                                    </option>
                                    <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Normal
                                    </option>
                                </select>
                            </div>
                            <a class="more"
                                href="index.php?page=admin&action=user&user-id=<?= $user['user_id'] ?>">Mehr</a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>


</html>