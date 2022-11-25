<?php
$title = 'Register';
$error = '';
require_once 'header.php';

if (isset($_SESSION['loggedIn'])){
    header('Location: index.php');
}

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){

        $username = $_POST['username'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirm'];

        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
        $nickName = filter_var($_POST['nickname'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        $year = filter_var($_POST['birthYear'], FILTER_VALIDATE_INT);
        $month = filter_var($_POST['birthMonth'], FILTER_VALIDATE_INT);
        $day = filter_var($_POST['birthDay'], FILTER_VALIDATE_INT);
        $birthDay = strftime("%F", strtotime($year."-".$month."-".$day));

        $userIP = ($_SERVER['REMOTE_ADDR'] == "::1") ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
        $agree = isset($_POST['agree']) ? 'YES' : 'NO';

        /*
        if ((date("Y")-3).date("-m-d") > $birthDay || (date("Y")-100).date("-m-d") < $birthDay) {
            $error = "Minimum age is 3 years.";
            die();
        }
        */

        if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != "" ) {
            $secret = $reCAPTCHA_secret_key;
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
        }
        $responseData->success = ($reCAPTCHA_enable == false) ? true : $responseData->success;

        if ($responseData->success) {
            if ($agree == 'YES') {
                if (preg_match('/^[a-zA-Z0-9]*$/i', $username) || strlen($username) >= 6 && strlen($username) <= 25) {
                    if (strlen($password) >= 6 && strlen($password) <= 32) {
                        if ($passwordConfirm === $password) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL) || empty($_POST['email'])) {

                                if ($register_method == 2) {
                                    $stmt = $conn->prepare("EXEC _Rigid_Register_User :username, :password, :email, (SELECT [GB_JoymaxPortal].[dbo].[F_GetCountryCodeByIPString](:countryCode)), :userIP");
                                    $stmt->execute([':username' => $username, ':password' => md5($password), ':email' => $email, ':countryCode' => $userIP, ':userIP' => $userIP]);

                                    if ($stmt->rowCount()) {
                                        $returnCode = $stmt->fetch()['ReturnCode'];
                                            switch ($returnCode) {
                                                case -1000:
                                                    $error = "Username already exists";
                                                    break;
                                                case -2000:
                                                    $error = "Email already exists";
                                                    break;
                                                case -2001:
                                                    $error = "Username already exists!";
                                                    break;
                                                default:
                                                    $error = "Database Error [$returnCode]";
                                            }
                                    }else {
                                        $error = "You are successfully registered";
                                    }
                                } else {
                                    $stmt = $conn->prepare('SELECT * FROM [SILKROAD_R_ACCOUNT].[dbo].[TB_User] WHERE StrUserID = :username');
                                    $stmt->execute([':username' => $username]);

                                    if ($stmt->rowCount()) {
                                        $error = "Username already exists";

                                    } else {
                                        $stmt = $conn->prepare('SELECT * FROM [GB_JoymaxPortal].[dbo].[MU_Email] WHERE EmailAddr = :email');
                                        $stmt->execute([':email' => $email]);

                                        if ($stmt->rowCount()) {
                                            $error = "Email already exists";

                                        } else {
                                            $stmt = $conn->prepare('SELECT * FROM [GB_JoymaxPortal].[dbo].[MU_User] WHERE NickName = :nickname OR UserID = :username');
                                            $stmt->execute([':nickname' => $nickName, ':username' => $username]);

                                            if ($stmt->rowCount()) {
                                                $error = "NickName already exists";

                                            } else {
                                                $conn->beginTransaction();
                                                try {
                                                    $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[MU_User] VALUES(:username, :password, :gender, :birthday, :nickname, (SELECT [GB_JoymaxPortal].[dbo].[F_GetCountryCodeByIPString](:countryCode)), 0, NULL, NULL)");
                                                    $stmt->execute([':username' => $username, ':password' => md5($password), ':gender' => $gender, ':birthday' => $birthDay, ':nickname' => $nickName, ':countryCode' => $userIP]);

                                                    if ($stmt->rowCount()) {
                                                        $stmt = $conn->prepare('SELECT JID FROM [GB_JoymaxPortal].[dbo].[MU_User] WHERE UserID = :username');
                                                        $stmt->execute([':username' => $username]);

                                                        if ($stmt->rowCount()) {
                                                            $portalJID = $stmt->fetch()['JID'];
                                                            $stmt = $conn->prepare("INSERT INTO SILKROAD_R_ACCOUNT.dbo.TB_User VALUES (:portalJid, :username, 1, :password, 1, :userIP, (SELECT [GB_JoymaxPortal].[dbo].[F_GetCountryCodeByIPString](:countryCode)), GETDATE(), GETDATE(), 3, 3, 0, 0, 0)");
                                                            $stmt->execute([':portalJid' => $portalJID, ':username' => $username, ':password' => md5($password), ':userIP' => $userIP, ':countryCode' => $userIP]);

                                                            if ($stmt->rowCount()) {
                                                                $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[MUH_AlteredInfo] VALUES(:portalJid, GETDATE(), :nickname, :nickname2, :email, 'Y', 'Y', (SELECT [GB_JoymaxPortal].[dbo].[F_MakeIPStringToIPBinary](:userIP)), (SELECT [GB_JoymaxPortal].[dbo].[F_GetCountryCodeByIPString](:countryCode)), :username, 1, 'N')");
                                                                $stmt->execute([':portalJid' => $portalJID, ':nickname' => $nickName, ':nickname2' => $nickName, ':email' => $email, ':userIP' => $userIP, ':countryCode' => $userIP, ':username' => $username]);

                                                                if ($stmt->rowCount()) {
                                                                    $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[APH_ChangedSilk] VALUES (NULL, NULL, NULL, :portalJid, :silk_own, 0, 1, 2, GETDATE(), DATEADD(YEAR, 10, GETDATE()), 'Y')");
                                                                    $stmt->execute([':portalJid' => $portalJID, ':silk_own' => $silk_own]);

                                                                    $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[APH_ChangedSilk] VALUES (NULL, NULL, NULL, :portalJid, :silk_own_premium, 0, 3, 2, GETDATE(), DATEADD(YEAR, 10, GETDATE()), 'Y')");
                                                                    $stmt->execute([':portalJid' => $portalJID, ':silk_own_premium' => $silk_own_premium]);

                                                                    if ($stmt->rowCount()) {
                                                                        $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[MU_Email] VALUES(:portalJid, :email)");
                                                                        $stmt->execute([':portalJid' => $portalJID, ':email' => $email]);

                                                                        if ($stmt->rowCount()) {
                                                                            $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[AUH_AgreedService] VALUES ( :portalJid, 1, GETDATE(), DATEADD(YEAR, 10, GETDATE()), (SELECT [GB_JoymaxPortal].[dbo].[F_MakeIPStringToIPBinary](:userIP)))");
                                                                            $stmt->execute([':portalJid' => $portalJID, ':userIP' => $userIP]);

                                                                            if ($stmt->rowCount()) {
                                                                                $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[MU_JoiningInfo] VALUES ( :portalJid, (SELECT [GB_JoymaxPortal].[dbo].[F_MakeIPStringToIPBinary](:userIP)), GETDATE(), (SELECT [GB_JoymaxPortal].[dbo].[F_GetCountryCodeByIPString](:countryCode)), 'JOYMAX')");
                                                                                $stmt->execute([':portalJid' => $portalJID, ':userIP' => $userIP, ':countryCode' => $userIP]);

                                                                                if ($stmt->rowCount()) {
                                                                                    $stmt = $conn->prepare("INSERT INTO [GB_JoymaxPortal].[dbo].[MU_VIP_Info] VALUES (:portalJid, 2, 1, GETDATE(), DATEADD(MONTH, 1, GETDATE()))");
                                                                                    $stmt->execute([':portalJid' => $portalJID]);

                                                                                    $error = 'You are successfully registered';
                                                                                    $_SESSION['loggedIn'] = true;
                                                                                    $_SESSION['portalJid'] = $portalJID;
                                                                                    $_SESSION['username'] = $username;

                                                                                    header('refresh: .5; url= /account/panel.php');
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $conn->commit();
                                                } catch(Exception $e) {
                                                    $conn->rollBack();
                                                    die( print_r( $e->getMessage() ) );
                                                }
                                            }
                                        }
                                    }

                                }
                            } else {
                                $error = 'Not a valid e-mail address';
                            }
                        } else {
                            $error = 'Password do not much';
                        }
                    } else {
                        $error = 'Password Must Contain at least 8 Characters';
                    }
                } else {
                    $error = 'Invalid Username';
                }
            } else {
                $error = 'Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy';
            }
        }else {
            $error = "Please check the the captcha form.";
        }
    }
}
?>

    <section class="signup" style="background-image: none;">
        <div class="signup-area">
            <img class="sign-logo" src="/assets/img/logo.png" width="250">
            <h1>Registration</h1>

            <div class="alert-danger">
                <span class="info"><?php echo $error; ?></span>
            </div>

            <form class="signup-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <input id="ca-new-id" type="text" name="username" placeholder="Username (length 6-25 characters)" minlength="6" maxlength="25" required>
                <input id="ca-new-pw" type="password" name="password" placeholder="password (length 6-32 characters)" minlength="6" maxlength="32" required>
                <input id="ca-new-pw-r" type="password" name="password_confirm" placeholder="Please re-enter your password" minlength="6" maxlength="32" required>

                <div class="ca-mobile">
                    <select name="gender" id="mobile_con">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    <input id="mobile" type="text" name="nickname" placeholder="Nick Name (length 6-30 characters)" minlength="6" maxlength="25" required>
                </div>
                <input id="ca-email" type="email" name="email" placeholder="Please enter email address" autocomplete="off" required>

                <div class="ca-birth">
                    <select id="ca-birth-year" name="birthYear">
                        <?php
                        $year = date('Y');
                        $min = $year - 60;
                        $max = $year;
                        for( $i=$max; $i>=$min; $i-- ) {
                            echo '<option value='.$i.'>'.$i.'</option>';
                        }
                        ?>
                    </select>
                    <select id="ca-birth-month" name="birthMonth">
                        <?php for( $m=1; $m<=12; ++$m ) {
                            $month_label = date('F', mktime(0, 0, 0, $m, 1));
                            ?>
                            <option value="<?php echo $m; ?>"><?php echo $month_label; ?></option>
                        <?php } ?>
                    </select>
                    <select id="ca-birth-month" name="birthDay">
                        <?php
                        $start_date = 1;
                        $end_date   = 31;
                        for( $j=$start_date; $j<=$end_date; $j++ ) {
                            echo '<option value='.$j.'>'.$j.'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div id="ca-check">
                    <input type="checkbox" name="agree">
                    <span>
                        I agree to accept the membership terms of <a href="#" target="_blank">Privacy Policy<i class="fas fa-external-link-alt" aria-hidden="true"></i></a>.
                    </span>
                    <?php if($reCAPTCHA_enable == true){ ?>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" data-sitekey="<?= $reCAPTCHA_site_key ?>"></div>
                    <?php } ?>
                </div>

                <button type="submit" id="ca-submit">Register</button>
            </form>
        </div>
    </section>

<?php include 'footer.php'; ?>