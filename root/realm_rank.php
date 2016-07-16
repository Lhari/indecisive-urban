<?php

$realm = true;
$area = true;
$world = false;

?>

<a class="ranking" href="http://www.wowprogress.com/guild/eu/emerald-dream/Indecisive/">
	<?php if($realm) : ?>
		<div class="ranking__wrapper">
			<h3 class="ranking__header">Realm Ranking</h3>
			<p class="ranking__position"><span class="js-realmrank js-ranks is-hidden"></span></p>
		</div>
		<div class="divider"></div>
	<?php endif; ?>
	<?php if($area) : ?>
		<div class="ranking__wrapper">
			<h3 class="ranking__header">Region Ranking</h3>
			<p class="ranking__position"><span class=" js-arearank js-ranks is-hidden"></span></p>
		</div>
		<?php
		if($world)
			echo '<div class="divider"></div>';
		?>
	<?php endif; ?>
	<?php if($world) : ?>

		<div class="ranking__wrapper">
			<h3 class="ranking__header">World Ranking</h3>
			<p class="ranking__position"><span class="js-worldrank js-ranks is-hidden"></span></p>
		</div>
	<?php endif; ?>
</a>