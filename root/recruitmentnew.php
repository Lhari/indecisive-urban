<style type="text/css">
	.recruitment__roles .is-active {
		opacity: 1;
	}
	.recruitment__roles i {
		opacity: 0.3;
	}
	.inactive {
		opacity: 0.17;
	}
	.no-padding, .no-padding--desk {
		padding-top: 0;
		margin-top: 0;
	}
</style>


<?php
$classes = array(
	array(
		'name' => 'Death Knight',
		'active' => true,
		'roles' => array(
			'melee' => false,
			'tank' => true
		)
	),
	array(
		'name' => 'Demon Hunter',
		'active' => true,
		'roles' => array(
			'melee' => true,
			'tank' => false
		)
	),
	array(
		'name' => 'Druid',
		'active' => true,
		'roles' => array(
			'ranged' => false,
			'melee' => true,
			'tank' => false,
			'healer' => true
		)
	),
	array(
		'name' => 'Hunter',
		'active' => true,
		'roles' => array(
			'ranged' => false,
			'melee' => true
		)
	),
	array(
		'name' => 'Mage',
		'active' => false,
		'roles' => array(
			'ranged' => false
		)
	),
	array(
		'name' => 'Monk',
		'active' => true,
		'roles' => array(
			'melee' => true,
			'tank' => false,
			'healer' => true
		)
	),
	array(
		'name' => 'Paladin',
		'active' => true,
		'roles' => array(
			'melee' => true,
			'tank' => false,
			'healer' => true
		)
	),
	array(
		'name' => 'Priest',
		'active' => true,
		'roles' => array(
			'ranged' => true,
			'healer' => true
		)
	),
	array(
		'name' => 'Rogue',
		'active' => true,
		'roles' => array(
			'melee' => true
		)
	),
	array(
		'name' => 'Shaman',
		'active' => true,
		'roles' => array(
			'melee' => true,
			'ranged' => true,
			'healer' => true
		)
	),
	array(
		'name' => 'Warlock',
		'active' => true,
		'roles' => array(
			'ranged' => true
		)
	),
	array(
		'name' => 'Warrior',
		'active' => true,
		'roles' => array(
			'melee' => true,
			'tank' => false
		)
	)
);

?>

<div class="recruitment">
	<div class="grid size-12">
	<?php
	foreach($classes as $key => $class) {
		
		$name = strtolower(str_replace(' ', '_', $class['name']));

		if($key == 0)
			$addClass = ' no-padding';
		else if($key == 1)
			$addClass = ' no-padding--desk';
		else
			$addClass = '';

		$displayName = str_replace(' ', '<br />', $class['name']);

		echo '<div class="recruitment__class grid size-6 size-12--desk-smallsize-12--lap size-12--palm'.($class['active'] ? '' : ' inactive').'">';
			echo '<h3 data-key="'.$key.'" class="class-color--'.$name.$addClass.'" style="text-align: center; width:100%; margin-bottom: 0; font-size: 1em;">'.$displayName.'</h3>';
			echo '<img src="/inde/icon/classicon-'.str_replace('_', '', $name).'.png" alt="'.$class['name'].'" class="size-12 size-12--lap size-12--palm" style="margin: 0 auto; display: block; padding-bottom: 11px;"/>';
			echo '<div class="recruitment__roles" style="margin: 0 auto; display: block; text-align: center; height: 22px;">';
	foreach ($class['roles'] as $role => $availability) {

		$isActive = false;

		if($availability)
			echo '<i class="class-color--'.$name.' icon-'.$role.' is-active"></i>';

	}
		echo '</div>';
	echo '</div>';

}

?>

</div>
<div class="recruitment__button grid size-12" style="text-align: center; padding: 22px;">
	<a href="" class="button button--secondary">Apply now</a>
</div>
</div>


