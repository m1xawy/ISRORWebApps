<?php
$title = 'Login';
$error = '';

require_once 'header.php';

if (isset($_SESSION['loggedIn'])){
    header('Location: index.php');
}

if (isset($_POST['username']) && isset($_POST['password'])){
    if (!empty($_POST['username']) && !empty($_POST['password'])){

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != "" ) {
            $secret = $reCAPTCHA_secret_key;
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
        }
        $responseData->success = ($reCAPTCHA_enable == false) ? true : $responseData->success;

        if ($responseData->success) {
            if (preg_match('/^[a-zA-Z0-9]*$/i', $username) || strlen($username) >= 6 && strlen($username) <= 25) {
                if (strlen($password) >= 6 && strlen($password) <= 32) {
                    $stmt = $conn->prepare('SELECT * FROM [GB_JoymaxPortal].[dbo].[MU_User] WHERE UserID = :username');
                    $stmt->execute([':username' => $username]);

                    if ($stmt->rowCount()) {
                        foreach ($stmt->fetchAll() as $user){
                            $JID = $user['JID'];
                            $username = $user['UserID'];
                            $dbPassword = $user['UserPwd'];
                        }

                        if (md5($password) === $dbPassword){
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['portalJid'] = $JID;
                            $_SESSION['username'] = $username;

                            $stmt = $conn->prepare('UPDATE [GB_JoymaxPortal].[dbo].[MU_User] SET [LoginDate] = GETDATE() WHERE UserID = :username');
                            $stmt->execute(['username' => $username]);

                            header('refresh: .5; url= /account/panel.php');
                        }else{
                            $error = 'Invalid Data!!';
                        }
                    } else {
                        $error = 'Invalid Data!';
                    }
                } else {
                    $error = 'Password Must Contain at least 8 Characters';
                }
            } else {
                $error = 'Invalid Username';
            }
        } else {
            $error = 'Please check the the captcha form.';
        }
    }
}
?>

    <section class="signin" style="background-image: none;">
    <div class="signin-area">
        <img class="sign-logo" src="/assets/img/logo.png" width="250">
        <h1>User login</h1>

        <div class="alert-danger">
            <span class="info"><?php echo $error; ?></span>
        </div>

        <form id="login-form" class="signin-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input id="signin-id" type="text" name="username" placeholder="Username" minlength="6" maxlength="25" required>
            <input id="signin-pw" type="password" name="password" placeholder="password" minlength="6" maxlength="32" required>

            <div id="ca-check">
                <?php if($reCAPTCHA_enable == true){ ?>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" data-sitekey="<?= $reCAPTCHA_site_key ?>"></div>
                <?php } ?>

                <input type="checkbox" name="remember">
                <span>remember my account</span>
            </div>
            <button id="signin-btn" type="submit" name="login">Login</button>
        </form>
        <ul>
            <li><a id="lost-pw" class="signin-a" href="/forget_password.php">Forgot password?</a></li>
        </ul>
    </div>
</section>

<?php include 'footer.php'; ?>