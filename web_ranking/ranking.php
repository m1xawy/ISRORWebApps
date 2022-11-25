<?php include 'head.php'; ?>

<?php
$query = "
SELECT TOP(20)
	_Char.CharID, _Char.CharName16, _Char.CurLevel, _Char.RefObjID, _Guild.ID, _Guild.Name,
	
    + (CAST((sum(_Items.OptLevel))
    + SUM(_RefObjItem.ItemClass)
    + SUM(_RefObjCommon.Rarity)
    + (CASE WHEN sum(_BindingOptionWithItem.nOptValue) > 0 THEN sum(_BindingOptionWithItem.nOptValue) ELSE 0 END) as INT)) 
    AS ItemPoints

FROM
	_Char
	JOIN _Guild ON _Char.GuildID = _Guild.ID
	JOIN _Inventory ON _Char.CharID = _Inventory.CharID
	JOIN _Items ON _Inventory.ItemID = _Items.ID64
	LEFT JOIN _BindingOptionWithItem ON _Inventory.ItemID = _BindingOptionWithItem.nItemDBID
	JOIN _RefObjCommon ON _Items.RefItemID = _RefObjCommon.ID
	JOIN _RefObjItem ON _RefObjCommon.Link = _RefObjItem.ID

WHERE
	_Inventory.Slot between 0 and 12
	and _Inventory.Slot NOT IN (7, 8)
	and _Inventory.ItemID > 0
	and _Char.CharName16 NOT LIKE '%[[]GM]%'
	AND _Char.deleted = 0
	AND _Char.CharID > 0

GROUP BY
	_Char.CharID,
	_Char.CharName16,
	_Char.CurLevel,
	_Char.RefObjID,
	_Guild.ID,
	_Guild.Name

ORDER BY
	_Char.CurLevel DESC,
	ItemPoints DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
?>
    <div id="rankmain">
        <div id="rankmenu_container">
            <ul>
                <li  class="selected"><a href="ranking.php">Player</a></li>
                <li ><a href="ranking_guild.php">Guild</a></li>
                <li ><a href="ranking_unique.php">Unique</a></li>
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
                        <td class="td5"><?= $player['ItemPoints'] == null ? 0 : $player['ItemPoints'] ?></td>
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
