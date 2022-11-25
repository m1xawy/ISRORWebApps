<?php $title = 'Download'; ?>
<?php require_once 'header.php'; ?>

    <section class="account-page">
        <div id="cs-page" class="main-page in-page">
            <div class="page-top">
                <h1>Download Client</h1>
            </div>
            <div class="download page-nav" id="page-nav">
                <ul>
                    <?php
                        foreach ($downloads as $key => $download) {
                    ?>
                        <li>
                            <div class="download-box">
                                <img src="<?= $download['image'] ?>" width="100">
                                <span><?= $download['name'] ?></span>
                                <a href="<?= $download['url'] ?>" target="_blank" class="page-btn">Download</a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="page-content" style="margin-top: 40px">
                <div class="page-top">
                    <h1>Sysytem Requirements</h1>
                </div>
                <div class="page-box">
                    <div class="page-box-content">
                        <table class="page-table-04">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Minimum Requirements</th>
                                <th>Recommended Requirements</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="pg-td" scope="row">CPU</th>
                                <td class="pg-td">Pentium 3 800MHz or higher</td>
                                <td class="pg-td">Intel i3 or higher</td>
                            </tr>
                            <tr>
                                <th class="pg-td" scope="row">RAM</th>
                                <td class="pg-td">2GB</td>
                                <td class="pg-td">4GB</td>
                            </tr>
                            <tr>
                                <th class="pg-td" scope="row">VGA</th>
                                <td class="pg-td">3D speed over GeForce2 or ATI 9000</td>
                                <td class="pg-td">3D speed over GeForce FX 5600 or ATI9500</td>
                            </tr>
                            <tr>
                                <th class="pg-td" scope="row">SOUND</th>
                                <td class="pg-td">DirectX 9.0c Compatibility card</td>
                                <td class="pg-td">DirectX 9.0c Compatibility card</td>
                            </tr>
                            <tr>
                                <th class="pg-td" scope="row">HDD</th>
                                <td class="pg-td">5GB or higher(including swap and temporary file)</td>
                                <td class="pg-td">8GB or higher(including swap and temporary file)</td>
                            </tr>
                            <tr>
                                <th class="pg-td" scope="row">OS</th>
                                <td class="pg-td">Windows 7</td>
                                <td class="pg-td">Windows 10</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>