<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// PNG favicon
	echo '
		<link rel="icon" type="image/png" href="/favicon.png" />';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	echo '
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/mootools-1.2.2-core-nc.js"></script>';

	// Here comes the JavaScript bits!
	echo '
	<script src="//code.jquery.com/jquery-3.0.0.min.js"   integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/main.js?fin20"></script>

	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body class="limited-header--2">';
}

function template_body_above() {
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '

	<div class="offcanvas">
		<div class="offcanvas__inner">';
			echo template_menu().'
		</div>
	</div>
	<div class="oncanvas">
		<div class="oncanvas__overlay js-overlay is-hidden"></div>
	<div class="background">
		<div id="bg-video-wrapper">
			<video preload="auto" autoplay="true" loop="loop" style="opacity: 1;">

				<source src="/inde/key-render.webm" type="video/webm">
			</video>
		</div>
	</div>';

	if(!$context['user']['is_logged']) {

		echo '

	<div class="login js-login is-hidden">
		<div class="login__wrapper grid size-12--palm">
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
			<form
				action="', $scripturl, '?action=login2"
				method="post" accept-charset="', $context['character_set'], '"
				class="smalltext" ', empty($context['disable_login_hashing']) ? '
				onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
				<h2 class="">Log in</h2>
				<div id="ds-usernme" class="login__username--wrapper icon-user-1">
					<input id="usrnme" placeholder="Username" name="user" type="text" />
					</div>
				<div id="ds-passwrd" class="login__password--wrapper icon-login">
					<input id="psswrd" name="passwrd" placeholder="Password" type="password" />
				</div>
					<input id="loginbutton" type="submit" value="Login" class="login__button" name="submit" />
					<input type="button" value="Register" class="login__button login__button--register  js-register" />
					<input type="hidden" name="cookielength" value="-1" />
			</form>
		</div>
	</div>
	';
	}

	echo '
	<div class="header-wrap">
	<div id="header" class="grid-group content--wrapper">
		<div class="grid" id="head-wrap">
			<div class="logo">
			';
			if($_SERVER['REQUEST_URI'] == '/')
				echo '<img class="logo" src="/inde/indecisive-logo.svg">';
			else
				echo '<a href="'.$scripturl.'" title=""><img class="logo" src="/inde/indecisive-logo.svg"></a>';

			echo '
			</div>
		</div>
		<div class="grid grid--last header__menu">';
			if(!$context['user']['is_logged'])
				echo '<div class="js-login__toggle"><span class="is-hidden--palm is-hidden--lap">Login</span><i class="icon-login"></i></div>';
			else
				echo '<div class="js-offcanvas__toggle extra-spacing"><span class="is-hidden--palm is-hidden--lap">Menu</span><i class="icon-menu"></i></div>';
		echo '
		</div>

	</div>
	</div>
	<div id="userbar">
		<div id="user-wrap">

			<div id="userarea">';
				if($context['user']['is_logged'])
				{
				echo '<div class="loggedin">';

					// Only tell them about their messages if they can read their messages!


					// Is the forum in maintenance mode?
					if ($context['in_maintenance'] && $context['user']['is_admin'])
					echo '[<strong>', $txt['maintenance'], '</strong>]';

					// Are there any members waiting for approval?
					if (!empty($context['unapproved_members']))
					echo '[<a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] , ' ', $txt['approve'], '</a>]';
					echo '
					</div>';

				}
				// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
				else
				{
				}

		echo '
			</div>
		</div>
	</div>
		<div class="grid-group content--wrapper">
			';

				// Show the navigation tree.
				//theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

		echo '<div id="footer" class="grid size-12">
			<div class="footer-content" style="font-size: 13px; text-align: center; margin-top: 22px;">
				', theme_copyright(), '<br />
				<strong>Indecisive Theme</strong> design by <a href="http://www.indecisive.eu" target="_blank">us</a>. We are awesome!
			</div>
			<div class="clr"></div>';

				// Show the load time?
				if ($context['show_load_time'])
				echo '
				<span class="smalltext">', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</span>';

			echo '
		</div>';

	echo '

		</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!

}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	</div>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false) {
	global $context, $settings, $options, $shown_linktree, $txt;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;


	echo '
	<div class="grid size-12 breadcrumb">';
		//if($_SERVER['REQUEST_URI'] != '/') {
		echo '<ul>';
			echo '<li class="link-title"></li>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{

		echo '
			<li onclick="ExpandCrumbs();"', ($link_num == count($context['linktree']) - 1) ? ' class="link-current"' : ' class="link-previous"', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		if(strtolower($tree['name']) == 'indecisive homepage')
			$tree['name'] = 'Home';

		// Show the link, including a URL if it should have one.
		if($settings['linktree_link'] && isset($tree['url'])) {
			if($link_num != count($context['linktree']) - 1) {
				echo '<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>';
			} else {
				echo '<a href="javascript:void(0);"><span>' . $tree['name'] . '</span></a>';
			}
		} else {
			echo '<span>' . $tree['name'] . '</span>';
		}

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		// if ($link_num != count($context['linktree']) - 1 && !$context['right_to_left'])
			echo '<i class="icon-up-open"></i>';

		echo '
			</li>';
	}
	echo '
		</ul>';
	//}
	echo '</div>';



	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
   global $context, $settings, $options, $scripturl, $txt;

	echo '
			<ul class="offcanvas__nav">';

			?>

			<li class="offcanvas__search">
				<div class="grid size-3">
					<a href="/" tabindex="-1" class="icon-home-1"></a>
				</div>
				<div class="grid size-9 offcanvas__search--wrapper">
					<?php echo '<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">'; ?>
							<?php echo '<div class="form__wrapper icon-search"><input class="icon-search" tabindex="-1" type="text" name="search" value="', $txt['search'], '..." onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['search'], '...\';" /></div>'; ?>
					</form>
				</div>
			</li>

			<?php

			foreach ($context['menu_buttons'] as $act => $button)
			{

				if(!strstr(strtolower($button['title']), 'moderate') && !strstr($button['title'], 'My Messages') && !strstr(strtolower($button['title']), 'home')) {

					if($button['title'] == 'Logout' || $button['title'] == 'Profile') {
						$moveElement[$button['title']] = $button['href'];
					}
					else {

						echo '
							<li class="'.($button['active_button'] ? 'active' : '').(!empty($button['sub_buttons']) ? ' has-children' : '').' list">';

							if(!empty($button['sub_buttons']))
								echo '<a tabindex="-1" class="icon-up-open">'.ucfirst(strtolower($button['title'])).'</a>';
							else
								echo '<a tabindex="-1" href="'.$button['href'].'">'.ucfirst(strtolower($button['title'])).'</a>';



					// Render sub button menu
					if(!empty($button['sub_buttons'])) {
					echo '<ul class="offcanvas__nav js-offcanvas-level-1">';

						if(strstr(strtolower($button['title']), 'admin'))
							echo '<li><a tabindex="-1" href="'.$button['href'].'">Admin Center</a></li>';

						if(strstr(strtolower($button['title']), 'media'))
							echo '<li><a tabindex="-1" href="'.$button['href'].'">Gallery</a></li>';

						if(strstr(strtolower($button['title']), 'tinyportal'))
							echo '<li><a tabindex="-1" href="'.$button['href'].'">Tinyportal Admin</a></li>';

					foreach ($button['sub_buttons'] as $subact => $sbutton) {
						if($sbutton['href'] != '#top' && (!strstr(strtolower($button['title'], 'media')) && strtolower($sbutton['title']) != 'home' ) ) {

							echo '
							<li>
								<a tabindex="-1" href="', $sbutton['href'], '">'.ucwords(strtolower($sbutton['title'])).'</a>
							</li>';
						}
					}


					echo '
						</ul>';
					}


					echo '
						</li>';

					}
				}
			}

				echo '<li class="grid-group">';
				if($context['user']['is_logged']) {
					echo '<div class="usermenu loggedIn">';
					if ($context['allow_pm']) {
					echo '
						<a tabindex="-1" class="grid size-4" href="/pm/"><i class="icon-mail">';
						if($context['user']['unread_messages'] > 0)
							echo '<span class="messages__unread">'.$context['user']['unread_messages'].'</span>';
						echo '</i></a></span>';
					}
					echo '<a tabindex="-1" class="grid size-4" href="'.$moveElement['Profile'].'"><i class="icon-user-1"></i></a>';
					echo '<a tabindex="-1" class="grid size-4" href="'.$moveElement['Logout'].'"><i class="icon-logout-2"></i></a>';
					echo '</div>';
				}

				echo '</li>';

		echo '
			</ul>
		</div>';


		/*

		*/
}


function template_button_strip_with_icons_and_text($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']])) {

			$hasId = (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '');
			$hasActive = $key.(isset($value['active']) ? ' active' : '');
			$hasCustom =  (isset($value['custom']) ? ' ' . $value['custom'] : '');

			$buttons[] = '<a '.$hasId.' class="button button--primary button_strip_'.$hasActive.'" href="' . $value['url'] .'"' .$hasCustom. '>'.($value['icon'] ? '<i class="'.$value['icon'].'"></i>' : '').'<span>'.$txt[$value['text']].'</span></a>';

		}
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	//$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);
	echo implode('', $buttons);
}

function template_button_strip_with_icons($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']])) {

			$hasId = (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '');
			$hasActive = $key.(isset($value['active']) ? ' active' : '');
			$hasCustom =  (isset($value['custom']) ? ' ' . $value['custom'] : '');
			$hasIcon = (isset($value['icon']) ? '<i class="'.$value['icon'].'"></i>' : $txt[$value['text']] );

			$buttons[] = '<a '.$hasId.' class="button_strip_'.$hasActive.'" href="' . $value['url'] .'"' .$hasCustom. '>' .$hasIcon. '</a>';
		}
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	//$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			'.
				implode('', $buttons).'
		</div>';
}


// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>
