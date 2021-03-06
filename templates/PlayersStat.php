<h2>Рейтинг игроков</h2>
<br>
<table class="table table-striped table-condensed">
    <tr>
        <th>#</th>
        <th>Игрок</th>
        <th><a href="?sort=rating">Рейтинг</a><?php if (!isset($_GET['sort']) || $_GET['sort'] == 'rating') { ?><span class="icon-arrow-down"></span><?php } ?></th>
        <th><a href="?sort=avg">Среднее место</a><?php if (isset($_GET['sort']) && $_GET['sort'] == 'avg') { ?><span class="icon-arrow-up"></span><?php } ?></th>
        <th><abbr title="Среднеквадратичное отклонение">СКО</abbr></th>
        <th>Сыграно игр</th>
    </tr>
    <?php $n = 1; ?>
    <?php foreach ($usersData as $item) { ?>
    <tr>
        <td><?php echo $n++; ?></td>
        <td><a href="/graphs/?user=<?php echo rawurlencode(IS_ONLINE ? base64_decode($item['username']) : $item['username']);?>"><?php echo $aliases[$item['username']];?></a></td>
        <td><span class="badge<?php if ($item['rating'] >= START_RATING) { echo ' badge-success'; } else {echo ' badge-important';}; ?>"><?php echo $item['rating'];?></span></td>
        <td><?php echo $item['place_avg'];?></td>
        <td><?php echo $item['stddev'];?></td>
        <td><?php echo $item['games_played'];?></td>
    </tr>
    <?php } ?>
</table>
