<?php
require_once 'config.php';
session_start();

$que_num = '';
$country_code = '';
$languages = array("US" => "us", "DE" => "de", "TR" => "tr", "ES" => "es", "EG" => "eg");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="all" href="style.css" />
    <title>Take Survey</title>

    <!-- Coded by m1xawy -->
    <script type="text/javascript" src="library/config/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="jquery.scroll.js"></script>

</head>
<body class="">
<div id="developer">
    <?php
    if (isset($_REQUEST['values']) && !empty($_REQUEST['values'])){
        $data = explode('|',$_REQUEST['values']);

        $cp_jid = filter_var($data[0], FILTER_VALIDATE_INT);
        $shard_id = $data[1];
        $charname = $data[2];
        $charlevel = $data[3];
        $job = $data[4];
        $class = $data[5];
        $country_code = $data[6];
        $key = $data[7];
        $genKey = strtoupper(md5($cp_jid.$shard_id.$charname.$charlevel.$job.$class.$country_code.$saltKey));
        $user_lang = array_key_exists($country_code, $languages) ? $languages[$country_code] : 'us';

        if ($genKey == $key){
            $_SESSION['key'] = $key;

            $stmt = $conn->prepare("SELECT [JID] FROM [SILKROAD_R_ACCOUNT].[dbo].[TB_User] WHERE JID = :jid");
            $stmt->execute([':jid' => $cp_jid]);
            if ($stmt->rowCount()){
                ?>
                <form action="vote_result.php" method="post">
                    <div id="content">
                        <?php
                        $stmt = $conn->prepare("SELECT TOP(1) * FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_MAIN] WHERE idx NOT IN (SELECT [poll_num] FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_USER] WHERE cp_jid = :jid) AND end_date >= GETDATE() ORDER BY end_date DESC");
                        $stmt->execute([':jid' => $cp_jid]);

                        if ($stmt->rowCount()) {
                            foreach ($stmt->fetchAll() as $poll){
                                $poll_idx = $poll['idx'];
                                $giveitem_code = $poll['giveitem_code'];
                                $giveitem_qty = $poll['giveitem_qty'];
                                $subject = $poll['subject_'.$user_lang];
                                ?>
                                <h1><?= $subject ?></h1>
                                <p class="desc"></p>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_QUESTION] WHERE poll_num = :poll_num");
                                $stmt->execute([':poll_num' => $poll_idx]);

                                if ($stmt->rowCount()) {
                                    foreach ($stmt->fetchAll() as $que){
                                        $que_idx = $que['idx'];
                                        $que_num = $que['poll_num'];
                                        $question = $que['question_'.$user_lang];
                                        ?>
                                        <div class="ques">
                                            <div class="tit"><?= $question ?></div><br>
                                            <?php
                                            $stmt = $conn->prepare("SELECT * FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_ANSWER] WHERE que_num = :que_num");
                                            $stmt->execute([':que_num' => $que_idx]);

                                            if ($stmt->rowCount()) {
                                                $count = 1;
                                                foreach ($stmt->fetchAll() as $ans){
                                                    $ans_idx = $ans['idx'];
                                                    $ans_num = $ans['que_num'];
                                                    $answer = $ans['answer_'.$user_lang];
                                                    ?>
                                                    <input type="radio" name="ques[ans_num][ans_<?= $que_idx ?>]" value="<?= $ans_idx ?>"/> <?= $answer ?><br>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } else {

                            $stmt = $conn->prepare("SELECT TOP 1 [idx] FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_USER] WHERE [cp_jid] = :jid ORDER BY [idx] DESC");
                            $stmt->execute([':jid' => $cp_jid]);

                            if ($stmt->rowCount()) {
                                $poll_idx = $stmt->fetch()['idx'];

                                header("Location: vote_result.php?poll=$poll_idx&lg=$country_code");
                                die();
                            }else {
                                header("Location: vote_result.php?msg=nosurvey");
                                die();
                            }
                         }
                        ?>
                    </div>
                    <input type="hidden" name="data" value="<?= $_REQUEST['values'] ?>"/>
                    <input type="hidden" name="ques[giveitem_code]" value="<?= $giveitem_code ?>"/>
                    <input type="hidden" name="ques[giveitem_qty]" value="<?= $giveitem_qty ?>"/>
                    <input type="hidden" name="ques[poll_num]" value="<?= $poll_idx ?>"/>
                    <input type="hidden" name="ques[que_num]" value="<?= $que_idx ?>"/>
                    <div id="closer">
                        <div class="bbs">
                            <div class="func">
                                <div class="btn">
                                    <button type="submit">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div id="alert">
                    <div class="tit">Alert</div>
                    <div class="note"></div>
                    <div class="func">
                        <div class="btn btn-confirm">
                            <button>Ok</button>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">

                    $('#content').scrollbar(); // Custom Scrollbar
                    country = '<?= $user_lang ?>'
                    switch (country)
                    {
                        case 'us':
                            var btn = 'Confirm'
                            var msg = 'Please answer all questions.'
                            break;
                        case 'tr':
                            var btn = 'Onaylamak'
                            var msg = 'Lütfen tüm soruları cevaplayın.'
                            break;
                        case 'de':
                            var btn = 'Bestätigen'
                            var msg = 'Bitte beantworten Sie alle Fragen.'
                            break;
                        case 'es':
                            var btn = 'Confirmar'
                            var msg = 'Por favor, responda todas las preguntas.'
                            break;
                        case 'eg':
                            var btn = 'تأكيد'
                            var msg = '.من فضلك اجب على جميع الاسئله'
                            break;
                        default:
                            var btn = 'Confirm'
                            var msg = 'Please answer all questions.'
                    }

                    $('#closer button').html(btn);
                    $('#alert button').click(function(){
                        $('#alert').hide();
                    });

                    $("form").submit(function(e){
                        var quesCnt = $(".ques").length
                        var cboxCnt = $(":radio").length
                        var checkCnt = 0

                        for (i=0; i <= cboxCnt -1 ; i++) {
                            if ($(":radio")[i].checked == true) {
                                checkCnt = checkCnt + 1
                            }
                        }

                        if (checkCnt !== quesCnt) {
                            $('#alert .note').html(msg);
                            $('#alert').show();
                            e.preventDefault();
                        }
                    });
                </script>
                <?php
                }else {
                    header("Location: vote_result.php?msg=invalidUser");
                    die();
                }
            }else{
                header("Location: vote_result.php?msg=invalidKey");
                die();
            }
        }else{
            header("Location: vote_result.php?msg=noparam");
            die();
        }
        ?>
</div>

</body>
</html>
