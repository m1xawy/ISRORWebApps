<?php $title = 'Home'; ?>
<?php $msg = ''; ?>
<?php require_once '../header.php'; ?>

<?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true){ ?>
    <section class="account-page">
        <div id="cs-page" class="donate in-page">
            <div class="page-nav" id="page-nav">
                <ul>
                    <li>
                        <a href="/account/panel.php" class="">
                            <i class="page-nav-icn far fa-question-circle"></i>
                            <span class="page-nav-name">ACCOUNT PANEL</span>
                        </a>
                    </li>
                    <li>
                        <a href="/account/donate.php" class="page-nav-active">
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
                        <?php
                        if(isset($_GET["coinbase"]) && $_GET["coinbase"] == "successful"){
                            $msg = 'Successful Payment';
                            header('refresh: .5; url= /account/panel.php');
                        }
                        if(isset($_GET["coinbase"]) && $_GET["coinbase"] == "failed"){
                            $msg = 'Payment Failed!';
                            header('refresh: .5; url= /account/panel.php');
                        }
                        ?>
                        <div class="alert-danger">
                            <span class="info"><?php echo $msg; ?></span>
                        </div>
                        <?php
                        if (isset($_SESSION['portalJid']) && !empty($_SESSION['portalJid'])) {
                            foreach ($package['silk'] as $key => $value){
                                ?>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h4 class="m-0 font-weight-bold">
                                                <?= $value['value'] ?> <?= $value['name'] ?>
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <?= $value['description'] ?>
                                            </h6>
                                            <form action="/api/coinbase/create.php" method="post">
                                                <input type="hidden" name="jid" value="<?= $_SESSION['portalJid'] ?>">
                                                <input type="hidden" name="name" value="<?= $value['name'] ?>">
                                                <input type="hidden" name="currency" value="<?= $value['currency'] ?>">
                                                <input type="hidden" name="amount" value="<?= $value['amount'] ?>">
                                                <input type="hidden" name="value" value="<?= $value['value'] ?>">
                                                <input type="hidden" name="description" value="<?= $value['description'] ?>">
                                                <input type="submit" class="page-btn card-link" value="Buy now!">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }else {
    header('Location: ../login.php');
} ?>
<?php include '../footer.php'; ?>