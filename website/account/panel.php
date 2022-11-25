<?php $title = 'Home'; ?>
<?php $error = ''; ?>
<?php require_once '../header.php'; ?>

<?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){ ?>

    <section class="account-page">
        <div id="cs-page" class="in-page">
            <div class="page-nav" id="page-nav">
                <ul>
                    <li>
                        <a href="/account/panel.php" class="page-nav-active">
                            <i class="page-nav-icn far fa-question-circle"></i>
                            <span class="page-nav-name">ACCOUNT PANEL</span>
                        </a>
                    </li>
                    <li>
                        <a href="/account/donate.php" class="">
                            <i class="page-nav-icn fas fa-coins"></i>
                            <span class="page-nav-name">DONATE</span>
                        </a>
                    </li>
                    <li>
                        <a href="/logout.php" class="">
                            <i class="page-nav-icn fa fa-sign-out"></i>
                            <span class="page-nav-name">LOGOUT</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="page-content">
                <div class="page-top">
                    <h1>ACCOUNT PANEL</h1>
                </div>
                <div class="page-box">
                    <div class="page-box-content">
                        <table class="page-table-04">
                            <tbody>

                            <?php
                            if (isset($_SESSION['portalJid']) && !empty($_SESSION['portalJid'])) {
                                $stmt = $conn->prepare('SELECT * FROM [GB_JoymaxPortal].[dbo].[MUH_AlteredInfo] WHERE JID = :jid');
                                $stmt->execute([':jid' => $_SESSION['portalJid']]);

                                if ($stmt->rowCount()) {

                                    foreach ($stmt->fetchAll() as $user) {
                                        $username = $_SESSION['username'];
                                        $email = $user['EmailAddr'];
                                        $joinDate = $user['AlterationDate'];
                                    }
                                    $stmt = $conn->prepare('EXEC [GB_JoymaxPortal].[dbo].[X_GetJCash] :jid');
                                    $stmt->execute([':jid' => $_SESSION['portalJid']]);

                                    if ($stmt->rowCount()) {
                                        foreach ($stmt->fetchAll() as $silk) {
                                            $premiumSilk = $silk['PremiumSilk'];
                                            $silk = $silk['Silk'];
                                        }
                                    }
                                }else {
                                    $error = 'Invalid Username';
                                    die();
                                }
                            }else {
                                header('Location: ../login.php');
                                die();
                            }
                            ?>
                            <div class="alert-danger">
                                <span class="info"><?php echo $error; ?></span>
                            </div>

                            <tr>
                                <td class="pg-td">Username: </td>
                                <td class="pg-td"><?= $username ?></td>
                            </tr>
                            <tr>
                                <td class="pg-td">Email: </td>
                                <td class="pg-td"><?= $email ?></td>
                            </tr>
                            <tr>
                                <td class="pg-td">Join Date: </td>
                                <td class="pg-td"><?= $joinDate ?></td>
                            </tr>
                            <tr>
                                <td class="pg-td">Silk Premium: </td>
                                <td class="pg-td"><?= $premiumSilk ?></td>
                            </tr>
                            <tr>
                                <td class="pg-td">Silk: </td>
                                <td class="pg-td"><?= $silk ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }else {
    header('Location: ../login.php');
} ?>
<?php include '../footer.php'; ?>