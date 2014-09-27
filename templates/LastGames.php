<h2>Последние игры</h2>
<br>
<?php

if (empty($gamesData) && $currentPage == 1) {
    echo "Ни одной игры еще не сыграно";
    return;
}

//$gamesCounter = 1;
echo "
    <table class='table table-striped table-condensed'>
    <tr>
        <th>#</th>
        <th>Время регистрации</th>
        <th>Игроки</th>
        <th>Всякое разное</th>
    </tr>";
$gamesCounter = $offset + 1;
foreach ($gamesData as $game) {
    // игроки и очки
    $players = "<table class='table table-'>\n";
    foreach ($scoresData as $score) {
        if ($game['id'] != $score['game_id']) {
            continue;
        }
        if ($score['result_score'] > 0) {
            $plus = '+';
        } else {
            $plus = '';
        }

        if ($score['result_score'] > 0) {
            $label = 'badge-success';
        } elseif ($score['result_score'] < 0) {
            $label = 'badge-important';
        } else {
            $label = 'badge-info';
        }

        $players .= "<tr>
            <td style='background: transparent; width: 50%;'><span class='icon-user'></span>&nbsp;<b>" . $aliases[$score['username']] . "</b></td>
            <td style='background: transparent; width: 25%;'>{$score['score']}</td>
            <td style='background: transparent; width: 25%;'><span class='badge {$label}'>{$plus}{$score['result_score']}</span></td>
        </tr>\n";
    }
    $players .= "</table>";

    // лучшая рука
    $bestHan = 0;
    $bestFu = 0;
    $player = '';
    $yakuman = 0;
    $chomboCount = 0;

    foreach ($roundsData as $round) {
        if ($game['id'] != $round['game_id']) {
            continue;
        }

		if ($round['result'] == 'chombo') {
            $chomboCount ++;
        }

        if ($round['yakuman']) {
            $bestHan = $bestFu = 0;
            $player = $aliases[$round['username']];
            $yakuman = 1;
            break;
        }
        if ($round['han'] > $bestHan) {
            $bestHan = $round['han'];
            $bestFu = $round['fu'];
            $player = $aliases[$round['username']];
        }
        if ($round['han'] == $bestHan && $round['fu'] > $bestFu) {
            $bestFu = $round['fu'];
            $player = $aliases[$round['username']];
        }
    }

    if ($bestHan >= 5) {
        $cost = $bestHan . ' хан';
    } else {
        $cost = $bestHan . ' хан, ' . $bestFu . ' фу';
    }

    if ($yakuman) {
        $cost = 'якуман!';
    }

    $ronWins = $game['ron_count'] . ' ' . plural($game['ron_count'], 'победа', 'победы', 'побед');
    $tsumoWins = $game['tsumo_count'] . ' ' . plural($game['tsumo_count'], 'победа', 'победы', 'побед');
    $draws = $game['drawn_count'] . ' ' . plural($game['drawn_count'], 'ничья/пересдача', 'ничьи/пересдачи', 'ничьих/пересдач');

    $chombosLi = '';
    if ($chomboCount > 0) {
        $chomboCount .= ' ' . plural($chomboCount, 'штраф чомбо', 'штрафа чомбо', 'штрафов чомбо');
        $chombosLi = "<li>В игре было {$chomboCount}</li>";
    }

    echo "<tr>
        <td>{$gamesCounter}</td>
        <td>{$game['play_date']}</td>
        <td>{$players}</td>
        <td>
            <ul>
                <li>Лучшая рука собрана игроком <b>" . $player . "</b> - {$cost}</li>
                <li>В игре было {$ronWins} по рон и {$tsumoWins} по цумо</li>
                <li>В игре было {$draws}</li>
                {$chombosLi}
            </ul>
        </td>
    </tr>";
    $gamesCounter ++;
}

function plural($count, $form1, $form2, $form3)
{
    if ($count >= 11 && $count <= 14) {
        return $form3;
    }

    if ($count % 10 == 1) {
        return $form1;
    }

    if ($count % 10 >= 2 && $count % 10 <= 4) {
        return $form2;
    }

    return $form3;
}

if (empty($currentPage)) {
	$currentPage = 1;
}

if ($currentPage == 1) {
	$prevPage = 1;
} else {
	$prevPage = $currentPage - 1;
}

$nextPage = $currentPage + 1;

$paginator = "<div class='pagination'><ul>
<li><a href='?page={$prevPage}'>Назад</a></li>
<li><a href='?page={$nextPage}'>Вперед</a></li>
</ul></div>";

echo "<tr><td colspan=4>{$paginator}</td></tr>";
echo "</table>";
