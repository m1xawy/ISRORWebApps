<?php $title = 'Guild Ranking'; ?>
<?php require_once '../header.php'; ?>

    <section class="account-page">
        <div id="cs-page" class="main-page in-page">
            <div class="page-nav" id="page-nav">
                <ul>
                    <li>
                        <a href="ranking.php" class="">
                            <span class="page-nav-name">Player Ranking</span>
                        </a>
                    </li>
                    <li>
                        <a href="ranking_guild.php" class="page-nav-active">
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
                         _Guild.ID, _Guild.Name,  _Guild.Lvl, _Guild.GatheredSP,
                         
                        (select CharID from SILKROAD_R_SHARD.._GuildMember where GuildID = _Guild.ID and MemberClass = 0) as MasterID,
                        (select CharName from SILKROAD_R_SHARD.._GuildMember where GuildID = _Guild.ID and MemberClass = 0) as MasterName,
                        (select COUNT(CharID) from SILKROAD_R_SHARD.._GuildMember where GuildID = _Guild.ID) AS TotalMember,
                    
                        + (CAST((sum(_Items.OptLevel))
                        + SUM(_RefObjItem.ItemClass)
                        + SUM(_RefObjCommon.Rarity)
                        + (CASE WHEN sum(_BindingOptionWithItem.nOptValue) > 0 THEN sum(_BindingOptionWithItem.nOptValue) ELSE 0 END) as INT)) 
                        AS ItemPoints
                    
                    FROM
                        SILKROAD_R_SHARD.._Guild
                        JOIN SILKROAD_R_SHARD.._GuildMember ON _Guild.ID = _GuildMember.GuildID
                        JOIN SILKROAD_R_SHARD.._Inventory ON _GuildMember.CharID = _Inventory.CharID
                        JOIN SILKROAD_R_SHARD.._Items ON _Inventory.ItemID = _Items.ID64
                        LEFT JOIN SILKROAD_R_SHARD.._BindingOptionWithItem ON _Inventory.ItemID = _BindingOptionWithItem.nItemDBID
                        JOIN SILKROAD_R_SHARD.._RefObjCommon ON _Items.RefItemID = _RefObjCommon.ID
                        JOIN SILKROAD_R_SHARD.._RefObjItem ON _RefObjCommon.Link = _RefObjItem.ID
                    
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
            <div class="page-content" style="margin-top: 40px">
                <div class="page-top">
                    <h1>Guild Ranking</h1>
                </div>
                <div class="page-box">
                    <div class="page-box-content">
                        <table class="page-table-04">
                            <thead>
                            <tr>
                                <th> </th>
                                <th>#</th>
                                <th>Guild</th>
                                <th>Level</th>
                                <th>Members</th>
                                <th>Point</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($stmt->rowCount()) {
                                $guild_count = 1;
                                foreach ($stmt->fetchAll() as $guild){
                                    switch ($guild_count) {
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
                                    ?>
                                    <tr>
                                        <td class="pg-td"></td>
                                        <td class="pg-td"><?= $guild_count++ ?></td>
                                        <td class="pg-td"><?= $guild['Name'] ?></td>
                                        <td class="pg-td"><?= $guild['Lvl'] ?></td>
                                        <td class="pg-td"><?= $guild['TotalMember'] ?></td>
                                        <td class="pg-td"><?= $guild['ItemPoints'] == null ? 0 : $guild['ItemPoints'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="6" style="text-align:center">No Records Found!</td>
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