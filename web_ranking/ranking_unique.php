<?php include 'head.php'; ?>

<?php
foreach ($uniques as $key => $unique) {
    $cfg_uniques_id_list[] = $unique['id'];
    $cfg_uniques_point_list[] = "+ (CASE WHEN _CharUniqueKill.MobID = " .$unique['id']. " THEN +" .$unique['points']. " ELSE 0 END)";
}
$uniques_id_list = implode(', ', $cfg_uniques_id_list);
$uniques_point_list = implode(' ', $cfg_uniques_point_list);

$query = "
SELECT TOP (20)
    _CharUniqueKill.CharID,
    _CharUniqueKill.MobID,
    _Char.CharName16,
	_Char.CurLevel,
    _Char.RefObjID,
    _Guild.ID,
    _Guild.Name,

	(SELECT SUM(CAST(
    $uniques_point_list
    AS INT))) AS Points

FROM 
    _CharUniqueKill 
    JOIN _Char ON _Char.CharID = _CharUniqueKill.CharID
    JOIN _Guild ON _Char.GuildID = _Guild.ID

WHERE 
    _CharUniqueKill.MobID IN ($uniques_id_list)
    AND _Char.CharName16 NOT LIKE '[GM]%'
    AND _Char.deleted = 0
    AND _Char.CharID > 0

GROUP BY
    _CharUniqueKill.CharID,
    _CharUniqueKill.MobID,
    _Char.CharName16,
	_Char.CurLevel,
    _Char.RefObjID,
    _Guild.ID,
    _Guild.Name

ORDER BY
    Points DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
?>
<div id="rankmain">
    <div id="rankmenu_container">
        <ul>
            <li ><a href="ranking.php">Player</a></li>
            <li ><a href="ranking_guild.php">Guild</a></li>
            <li class="selected"><a href="ranking_unique.php">Unique</a></li>
            <li ><a href="ranking_level.php">Level</a></li>
            <!--
            <li ><a href="ranking_fortress_player.php">Fortress War(Player)</a></li>
            <li ><a href="ranking_fortress_guild.php">Fortress War(Guild)</a></li>
            -->
        </ul>
    </div>
    <table class="table_rank" cellpadding="0" cellspacing="0">
        <tr>
            <th class="th1"> </th>
            <th class="th2">#</th>
            <th class="th3">Race</th>
            <th class="th4">Character</th>
            <th class="th5">Point</th>
            <th class="th6">Change</th>
        </tr>

        <?php
        if ($stmt->rowCount()) {
            $player_count = 1;
            foreach ($stmt->fetchAll() as $player){
                switch ($player_count) {
                    case 1:
                        $rank = '<img src="images/rank1.png" style="vertical-align:text-top" />';
                        break;
                    case 2:
                        $rank = '<img src="images/rank2.png" style="vertical-align:text-top" />';
                        break;
                    case 3:
                        $rank = '<img src="images/rank3.png" style="vertical-align:text-top" />';
                        break;
                    default;
                        $rank = '';
                }
                if ($player['RefObjID'] > 2000) {
                    $race = '<img src="images/european.png" style="vertical-align:text-top" />';
                } else {
                    $race = '<img src="images/chinese.png" style="vertical-align:text-top" />';
                }
                ?>
                <tr onMouseOver='this.style.background="#2e261e"' onMouseOut='this.style.background="none"'>
                    <td class="td1"><?= $rank ?></td>
                    <td class="td2"><?= $player_count++ ?></td>
                    <td class="td3"><?= $race ?></td>
                    <td class="td4"><?= $player['CharName16'] ?></td>
                    <td class="td5"><?= $player['Points'] ?></td>
                    <td class="td6"><center><img style="width: 16px; height: 16px;" src="images/nochange.png" title="No Change"></center></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" style="text-align:center">No Records Found!</td>
            </tr>
        <?php } ?>
    </table>
    <div id="button_website" onclick="blankurl('<?= $btnUrl ?>')">Official Site</div>
</div>
</body>
</html>
