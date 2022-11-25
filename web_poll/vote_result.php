<?php
require_once 'config.php';
session_start();
ob_start();

$msg = '';
$key = '';
$genKey = '';
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
    <div id="content">
        <?php
        if (isset($_POST['data']) && !empty($_POST['data'])){
            if (isset($_SESSION['key']) && !empty($_SESSION['key'])){
                $data = explode('|',$_POST['data']);

                $cp_jid = $data[0];
                $shard_id = $data[1];
                $charname = $data[2];
                $charlevel = $data[3];
                $job = $data[4];
                $class = $data[5];
                $country_code = $data[6];
                $key = $_SESSION['key'];
                $genKey = strtoupper(md5($cp_jid.$shard_id.$charname.$charlevel.$job.$class.$country_code.$saltKey));
                $ip = ($_SERVER['REMOTE_ADDR'] == "::1") ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];

                if ($genKey == $key){

                    $stmt = $conn->prepare("SELECT [JID] FROM [SILKROAD_R_ACCOUNT].[dbo].[TB_User] WHERE JID = :jid");
                    $stmt->execute([':jid' => $cp_jid]);
                    if ($stmt->rowCount()){

                        $poll_num = $_POST['ques']['poll_num'];
                        $que_num = $_POST['ques']['que_num'];
                        $ans_num = $_POST['ques']['ans_num'];
                        $giveitem_code = !empty($_POST['ques']['giveitem_code']) ? $_POST['ques']['giveitem_code'] : null;
                        $giveitem_qty = !empty($_POST['ques']['giveitem_qty']) ? $_POST['ques']['giveitem_qty'] : null;

                        $stmt = $conn->prepare("INSERT INTO [SILKROAD_R_ACCOUNT].[dbo].[VOTE_USER] VALUES (:jid, :poll_num, :shard_id, :charname, :charlevel, :job, :class, :country_code, :ip, GETDATE())");
                        $stmt->execute([':jid' => $cp_jid, ':poll_num' => $poll_num, ':shard_id' => $shard_id, ':charname' => $charname, ':charlevel' => $charlevel, ':job' => $job, ':class' => $class, ':country_code' => $country_code, ':ip' => $ip]);

                        if ($stmt->rowCount()){
                            $stmt = $conn->prepare("SELECT TOP 1 [idx] FROM [SILKROAD_R_ACCOUNT].[dbo].[VOTE_USER] WHERE [poll_num] = :poll_num AND [cp_jid] = :jid ORDER BY [idx] DESC");
                            $stmt->execute([':poll_num' => $poll_num, ':jid' => $cp_jid]);

                            if ($stmt->rowCount()){
                                $ref_idx = $stmt->fetch()['idx'];

                                foreach ($ans_num as $ans) {
                                    $ans_list[] = '('.$ref_idx.', '.$que_num.', '.$ans.')';
                                }
                                $ans_id_list = implode(', ', $ans_list);

                                $stmt = $conn->prepare("INSERT INTO [SILKROAD_R_ACCOUNT].[dbo].[VOTE_USER_DETAIL] VALUES ".$ans_id_list);
                                $stmt->execute();

                                if ($stmt->rowCount()){
                                    if($giveitem_code != null){
                                        $stmt = $conn->prepare("EXEC [SILKROAD_R_SHARD].._ADD_ITEM_EXTERN_CHEST :charname, 1, :giveitem_code, :giveitem_qty, 0");
                                        $stmt->execute([':charname' => $charname, ':giveitem_code' => $giveitem_code, ':giveitem_qty' => $giveitem_qty]);

                                        //$msg = "<br> check your chest for collect rewards.";
                                    }

                                    //$msg = "We've received your feedback";
                                    header("Location: vote_result.php?poll=$ref_idx&lg=$country_code");
                                }
                            }
                        }
                    }else {
                        header("Location: vote_result.php?msg=invalidUser");
                        die();
                    }
                }else {
                    header("Location: vote_result.php?msg=invalidKey");
                    die();
                }
            }else{
                header("Location: vote_result.php?msg=404");
                die();
            }
        }

        if (isset($_GET['msg']) && !empty($_GET['msg'])){
            if($_GET['msg'] == 'noparam'){
                $msg = "noparam";

            }elseif ($_GET['msg'] == 'invalidUser'){
                $msg = "Invalid User!";

            }elseif ($_GET['msg'] == 'invalidKey'){
                $msg = "Invalid Key!";

            }elseif ($_GET['msg'] == '404'){
                $msg = "a7a, not found!";

            }elseif ($_GET['msg'] == 'nosurvey'){
                $msg = "There is no survey currently running.<br>Please be looking forward for next survey.";

            }else {
                $msg = " ";
            }
            ?>
            <p class="done">
                <?= $msg ?>
            </p>
            <?php
        }

        if (isset($_GET['poll']) && !empty($_GET['poll'])){
            if (isset($_SESSION['key']) && !empty($_SESSION['key'])) {

                $ref_poll = $_GET['poll'];
                $country_code = $_GET['lg'];
                $languages = array("US" => "us", "DE" => "de", "TR" => "tr", "ES" => "es", "EG" => "eg");
                $user_lang = array_key_exists($country_code, $languages) ? $languages[$country_code] : 'us';

                if ($genKey == $key){
                    $stmt = $conn->prepare("SELECT * FROM VOTE_MAIN WHERE idx IN(SELECT poll_num FROM VOTE_USER WHERE idx = :ref_poll)");
                    $stmt->execute([':ref_poll' => $ref_poll]);
                    if ($stmt->rowCount()){
                        foreach ($stmt->fetchAll() as $poll) {
                            $subject = $poll['subject_'.$user_lang];
                            ?>
                            <h1><?= $subject ?></h1>
                            <p class="desc"></p>
                            <?php
                        }
                    }

                    $stmt = $conn->prepare("SELECT * FROM VOTE_QUESTION WHERE poll_num IN(SELECT poll_num FROM VOTE_USER WHERE idx = :ref_poll)");
                    $stmt->execute([':ref_poll' => $ref_poll]);
                    if ($stmt->rowCount()){

                        foreach ($stmt->fetchAll() as $que) {
                            $question = $que['question_' . $user_lang];
                            $que_idx = $que['idx'];
                            ?>
                            <p class="done"><?= $question ?></p>
                            <?php
                            $query = "SELECT *, (counter * 100 / SUM(counter) OVER()) AS [percentage]
                                        FROM
                                        (
                                        SELECT
                                            VOTE_ANSWER.idx,
                                            VOTE_ANSWER.answer_us,
                                            VOTE_ANSWER.answer_de,
                                            VOTE_ANSWER.answer_tr,
                                            VOTE_ANSWER.answer_es,
                                            VOTE_ANSWER.answer_eg,
                                        
                                            (SELECT SUM(CAST((CASE WHEN VOTE_USER_DETAIL.ans_num in (VOTE_ANSWER.idx) THEN +1 ELSE 0 END) AS INT))) AS [counter]
                                        
                                        FROM
                                            VOTE_USER_DETAIL
                                            JOIN VOTE_USER ON VOTE_USER.idx = VOTE_USER_DETAIL.ref_idx
                                            JOIN VOTE_MAIN ON VOTE_MAIN.idx = VOTE_USER.poll_num
                                            JOIN VOTE_QUESTION ON VOTE_QUESTION.poll_num = VOTE_MAIN.idx
                                            JOIN VOTE_ANSWER ON VOTE_ANSWER.que_num = VOTE_QUESTION.idx
                                        
                                        WHERE
                                            VOTE_ANSWER.que_num = :que_idx
                                        
                                        GROUP BY
                                            VOTE_QUESTION.poll_num,
                                            VOTE_ANSWER.idx,
                                            VOTE_ANSWER.answer_us,
                                            VOTE_ANSWER.answer_de,
                                            VOTE_ANSWER.answer_tr,
                                            VOTE_ANSWER.answer_es,
                                            VOTE_ANSWER.answer_eg
                                        ) t
                                        ";
                            $stmt = $conn->prepare($query);
                            $stmt->execute([':que_idx' => $que_idx]);

                            if ($stmt->rowCount()) {
                                ?>
                                <table style="width:100%">
                                    <?php
                                    foreach ($stmt->fetchAll() as $ans) {
                                        $answer = $ans['answer_'.$user_lang];
                                        $counter = $ans['counter'];
                                        $percentage = $ans['percentage'];
                                    ?>
                                    <tr>
                                        <td style="width: 75%"><?= $answer ?></td>
                                        <td style="text-align: center"><?= $percentage ?>%</td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                <br>
                                <br>
                                <?php
                            }else{
                                $msg = "not found!";
                                die();
                            }
                        }
                    }
                }else {
                    header("Location: vote_result.php?msg=invalidKey");
                    die();
                }
            }else{
                header("Location: vote_result.php?msg=404");
                die();
            }
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    $('#content').scrollbar(); // Custom Scrollbar
</script>
</body>
</html>
