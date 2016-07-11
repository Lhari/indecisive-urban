<?php

$realm = true;
$area = true;
$world = false;

$ranking = file_get_contents('http://www.wowprogress.com/guild/eu/emerald-dream/Indecisive/json_rank');
$ranking = json_decode($ranking);

?>

<a class="ranking" href="http://www.wowprogress.com/guild/eu/emerald-dream/Indecisive/">
	<?php if($realm) : ?>
		<div class="ranking__wrapper">
			<h3 class="ranking__header">Realm Ranking</h3>
			<p class="ranking__position"><?php echo $ranking->realm_rank; ?></p>
		</div>
		<div class="divider"></div>
	<?php endif; ?>
	<?php if($area) : ?>
		<div class="ranking__wrapper">
			<h3 class="ranking__header">Region Ranking</h3>
			<p class="ranking__position"><?php echo $ranking->area_rank; ?></p>
		</div>
		<?php
		if($world)
			echo '<div class="divider"></div>';
		?>
	<?php endif; ?>
	<?php if($world) : ?>

		<div class="ranking__wrapper">
			<h3 class="ranking__header">World Ranking</h3>
			<p class="ranking__position"><?php echo $ranking->world_rank; ?></p>
		</div>
	<?php endif; ?>
</a>