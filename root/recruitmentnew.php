<style type="text/css">
	.recruitment div {
		padding-bottom: 11px;
	}
	.recruitment div:last-child {
		border-bottom: 0;
	}
	.recruitment h3 {
		margin:0 0 11px 0;
		text-align: center;
	}
</style>


<?php
$classes = array(
	array(
		'name' => 'Death Knight',
		'roles' => array(
			'melee' => true,
			'tank' => false
		)
	),
	array(
		'name' => 'Demon Hunter',
		'roles' => array(
			'melee' => false,
			'tank' => false
		)
	),
	array(
		'name' => 'Druid',
		'roles' => array(
			'ranged' => true,
			'melee' => true,
			'tank' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Hunter',
		'roles' => array(
			'ranged' => true,
			'melee' => true
		)
	),
	array(
		'name' => 'Mage',
		'roles' => array(
			'ranged' => true
		)
	),
	array(
		'name' => 'Monk',
		'roles' => array(
			'melee' => false,
			'tank' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Paladin',
		'roles' => array(
			'melee' => false,
			'tank' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Priest',
		'roles' => array(
			'ranged' => false,
			'healer' => false
		)
	),
	array(
		'name' => 'Rogue',
		'roles' => array(
			'melee' => true
		)
	),
	array(
		'name' => 'Shaman',
		'roles' => array(
			'melee' => true,
			'ranged' => true,
			'healer' => false
		)
	),
	array(
		'name' => 'Warlock',
		'roles' => array(
			'ranged' => true
		)
	),
	array(
		'name' => 'Warrior',
		'roles' => array(
			'melee' => true,
			'tank' => false
		)
	)
);

$roles = array('Tank', 'Healer', 'Ranged', 'Melee');

?>

<div class="recruitment grid size-12">

<?php
	foreach ($roles as $role) {
		$roleHTML = "";
		$roleLowerCase = strtolower($role);

		foreach($classes as $key => $class) {
			if($class['roles'][$roleLowerCase]) {
				$roleHTML = $roleHTML . '<img src="/inde/ClassIcons/icon-class-' . str_replace(' ', '', $class['name']).'.png" alt="' . $class['name'].'" class="size-3--desk-wide size-4--desk size-4--palm" style="padding: 5px;"/>';
			}
		}

		if(!$roleHTML == "") {
			echo '<div class="divider">';
				echo '<h3>' . $role . '</h3>' . $roleHTML;
			echo '</div>';
		}
	}
?>

</div>
