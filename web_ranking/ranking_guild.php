<?php include 'head.php'; ?>

<?php
$query = "
SELECT TOP(20)
	 _Guild.ID, _Guild.Name,  _Guild.Lvl, _Guild.GatheredSP,
	 
	(select CharID from _GuildMember where GuildID = _Guild.ID and MemberClass = 0) as MasterID,
	(select CharName from _GuildMember where GuildID = _Guild.ID and MemberClass = 0) as MasterName,
    (select COUNT(CharID) from _GuildMember where GuildID = _Guild.ID) AS TotalMember,

    + (CAST((sum(_Items.OptLevel))
    + SUM(_RefObjItem.ItemClass)
    + SUM(_RefObjCommon.Rarity)
    + (CASE WHEN sum(_BindingOptionWithItem.nOptValue) > 0 THEN sum(_BindingOptionWithItem.nOptValue) ELSE 0 END) as INT)) 
    AS ItemPoints

FROM
	_Guild
	JOIN _GuildMember ON _Guild.ID = _GuildMember.GuildID
	JOIN _Inventory ON _GuildMember.CharID = _Inventory.CharID
	JOIN _Items ON _Inventory.ItemID = _Items.ID64
	LEFT JOIN _BindingOptionWithItem ON _Inventory.ItemID = _BindingOptionWithItem.nItemDBID
	JOIN _RefObjCommon ON _Items.RefItemID = _RefObjCommon.ID
	JOIN _RefObjItem ON _RefObjCommon.Link = _RefObjItem.ID

WHERE
	_Inventory.Slot between 0 and 12
	and _Inventory.Slot NOT IN (7, 8)
	and _Inventory.ItemID > 0
	AND _Guild.ID > 0

GROUP BY
	_Guild.ID,
	_Guild.Name,
	_Guild.Lvl,
	_Guild.GatheredSP

ORDER BY
	ItemPoints DESC,
	_Guild.Lvl DESC,
	_Guild.GatheredSP DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
?>
    <div id="rankmain">
        <div id="rankmenu_container">
            <ul>
                <li ><a href="ranking.php">Player</a></li>
                <li class="selected"><a href="ranking_guild.php">Guild</a></li>
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
                <th class="th4">Guild</th>
                <th class="th5">Point</th>
                <th class="th6">Change</th>
            </tr>

            <?php
            if ($stmt->rowCount()) {
            $guild_count = 1;
            foreach ($stmt->fetchAll() as $guild){
                switch ($guild_count) {
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
                ?>
                <tr onMouseOver='this.style.background="#2e261e"' onMouseOut='this.style.background="none"'>
                    <td class="td1"><?= $rank ?></td>
                    <td class="td2"><?= $guild_count++ ?></td>
                    <td class="td4"><?= $guild['Name'] ?></td>
                    <td class="td5"><?= $guild['ItemPoints'] == null ? 0 : $guild['ItemPoints'] ?></td>
                    <td class="td6"><center><img style="width: 16px; height: 16px;" src="images/nochange.png" title="No Change"></center></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" style="text-align:center">No Records Found!</td>
                </tr>
            <?php } ?>
        </table>
        <div id="button_website" onclick="blankurl('<?= $btnUrl ?>')">Official Site</div>
    </div>
</body>
</html>
