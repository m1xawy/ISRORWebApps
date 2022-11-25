<?php $title = 'Unique Ranking'; ?>
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
                        <a href="ranking_unique.php" class="page-nav-active">
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
            foreach ($uniques as $key => $unique) {
                $cfg_uniques_id_list[] = $unique['id'];
                $cfg_uniques_point_list[] = "+ (CASE WHEN _CharUniqueKill.MobID = " .$unique['id']. " THEN +" .$unique['points']. " ELSE 0 END)";
            }
            $uniques_id_list = implode(', ', $cfg_uniques_id_list);
            $uniques_point_list = implode(' ', $cfg_uniques_point_list);

            $query = "SELECT TOP (50)
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
                        SILKROAD_R_SHARD.._CharUniqueKill 
                        JOIN SILKROAD_R_SHARD.._Char ON _Char.CharID = _CharUniqueKill.CharID
                        JOIN SILKROAD_R_SHARD.._Guild ON _Char.GuildID = _Guild.ID
                    
                    WHERE 
                        _CharUniqueKill.MobID IN ($uniques_id_list)
                        AND _Char.CharName16 NOT LIKE '%[[]GM]%'
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
                                        <td class="pg-td"></td>
                                        <td class="pg-td"><?= $player_count++ ?></td>
                                        <td class="pg-td"><?= $race ?></td>
                                        <td class="pg-td"><?= $player['CharName16'] ?></td>
                                        <td class="pg-td"><?= $player['CurLevel'] ?></td>
                                        <td class="pg-td"><?= $player['Points'] ?></td>
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