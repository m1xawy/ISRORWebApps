<?php $title = 'Ranking'; ?>
<?php require_once '../header.php'; ?>

    <section class="account-page">
        <div id="cs-page" class="main-page in-page">
            <div class="page-nav" id="page-nav">
                <ul>
                    <li>
                        <a href="ranking.php" class="page-nav-active">
                            <span class="page-nav-name">Player Ranking</span>
                        </a>
                    </li>
                    <li>
                        <a href="ranking_guild.php" class="">
                            <span class="page-nav-name">Guild Ranking</span>
                        </a>
                    </li>
                    <li>
                        <a href="#!" class="">
                            <span class="page-nav-name">Job Ranking</span>
                        </a>
                    </li>
                    <li>
                        <a href="ranking_unique.php" class="">
                            <span class="page-nav-name">Unique Ranking</span>
                        </a>
                    </li>
                    <li>
                        <a href="#!" class="">
                            <span class="page-nav-name">Union Ranking</span>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
            $query = "SELECT TOP(50)
                        _Char.CharID, _Char.CharName16, _Char.CurLevel, _Char.RefObjID, _Guild.ID, _Guild.Name,
                        
                        + (CAST((sum(_Items.OptLevel))
                        + SUM(_RefObjItem.ItemClass)
                        + SUM(_RefObjCommon.Rarity)
                        + (CASE WHEN sum(_BindingOptionWithItem.nOptValue) > 0 THEN sum(_BindingOptionWithItem.nOptValue) ELSE 0 END) as INT)) 
                        AS ItemPoints
                    
                    FROM
                        SILKROAD_R_SHARD.._Char
                        JOIN SILKROAD_R_SHARD.._Guild ON _Char.GuildID = _Guild.ID
                        JOIN SILKROAD_R_SHARD.._Inventory ON _Char.CharID = _Inventory.CharID
                        JOIN SILKROAD_R_SHARD.._Items ON _Inventory.ItemID = _Items.ID64
                        LEFT JOIN SILKROAD_R_SHARD.._BindingOptionWithItem ON _Inventory.ItemID = _BindingOptionWithItem.nItemDBID
                        JOIN SILKROAD_R_SHARD.._RefObjCommon ON _Items.RefItemID = _RefObjCommon.ID
                        JOIN SILKROAD_R_SHARD.._RefObjItem ON _RefObjCommon.Link = _RefObjItem.ID
                    
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
                        ItemPoints DESC,
                        _Char.CurLevel DESC
                    ";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            ?>
            <div class="page-content" style="margin-top: 40px">
                <div class="page-top">
                    <h1>Player Ranking</h1>
                </div>
                <div class="page-box">
                    <div class="page-box-content">
                        <table class="page-table-04">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Race</th>
                                <th>Character</th>
                                <th>Guild</th>
                                <th>Level</th>
                                <th>Point</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($stmt->rowCount()) {
                                $player_count = 1;
                                foreach ($stmt->fetchAll() as $player){
                                    switch ($player_count) {
                                        case 1:
                                            $rank = '<img src="/assets/img/rank1.png" style="vertical-align:text-top" />';
                                            break;
                                        case 2:
                                            $rank = '<img src="/assets/img/rank2.png" style="vertical-align:text-top" />';
                                            break;
                                        case 3:
                                            $rank = '<img src="/assets/img/rank3.png" style="vertical-align:text-top" />';
                                            break;
                                        default;
                                            $rank = '';
                                    }
                                    if ($player['RefObjID'] > 2000) {
                                        $race = '<img src="/assets/img/european.png" style="vertical-align:text-top" />';
                                    } else {
                                        $race = '<img src="/assets/img/chinese.png" style="vertical-align:text-top" />';
                                    }
                                    ?>
                                    <tr>
                                        <td class="pg-td"><?= $rank ?></td>
                                        <td class="pg-td"><?= $player_count++ ?></td>
                                        <td class="pg-td"><?= $race ?></td>
                                        <td class="pg-td"><?= $player['CharName16'] ?></td>
                                        <td class="pg-td"><a href="#!"><?= $player['Name'] ?></a></td>
                                        <td class="pg-td"><?= $player['CurLevel'] ?></td>
                                        <td class="pg-td"><?= $player['ItemPoints'] == null ? 0 : $player['ItemPoints'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" style="text-align:center">No Records Found!</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include '../footer.php'; ?>