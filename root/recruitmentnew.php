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
		'active' => false,
		'roles' => array(
			'melee' => false,
			'tank' => false
		)
	),
	array(
		'name' => 'Demon Hunter',
		'active' => false,
		'roles' => array(
			'melee' => false,
			'tank' => false
		)
	),
	array(
		'name' => 'Druid',
		'active' => false,
		'roles' => array(
			'ranged' => false,
			'melee' => false,
			'tank' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Hunter',
		'active' => false,
		'roles' => array(
			'ranged' => false,
			'melee' => false
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
			'melee' => false,
			'tank' => false,
			'healer' => true
		)
	),
	array(
		'name' => 'Paladin',
		'active' => false,
		'roles' => array(
			'melee' => false,
			'tank' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Priest',
		'active' => false,
		'roles' => array(
			'ranged' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Rogue',
		'active' => false,
		'roles' => array(
			'melee' => false
		)
	),
	array(
		'name' => 'Shaman',
		'active' => true,
		'roles' => array(
			'melee' => false,
			'ranged' => false,
			'healer' => true
		)
	),
	array(
		'name' => 'Warlock',
		'active' => false,
		'roles' => array(
			'ranged' => false
		)
	),
	array(
		'name' => 'Warrior',
		'active' => false,
		'roles' => array(
			'melee' => false,
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

		$displayName = $class['name'];

		if($class['active']) {

		echo '<div class="recruitment__class grid-group'.($class['active'] ? '' : ' inactive').'">';
			echo '<div class="grid size-5">';
			echo '<img src="/inde/icon/classicon-'.str_replace('_', '', $name).'.png" alt="'.$class['name'].'" class="size-12 size-12--lap size-12--palm" style="margin: 0 auto; display: block; padding-bottom: 11px;"/>';
			echo '</div>';
			echo '<div class="grid size-7" style="text-shadow: 0 2px 2px black">';
			echo '<h3 data-key="'.$key.'" class="class-color--'.$name.' no-padding" style="width:100%; padding-top: 22px; margin-bottom: 6px; font-size: 1em;">'.$displayName.'</h3>';
	foreach ($class['roles'] as $role => $availability) {

		$isActive = false;

		if($availability)

			echo '<i class="class-color--'.$name.' icon-'.$role.' is-active"></i>';

	}
		echo '</div>';
	echo '</div>';
	}
}

?>

</div>

</div>


