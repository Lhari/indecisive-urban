<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.10
 */

function template_main() {
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="ie6_header floatleft">', !empty($settings['use_buttons']) ? '<img src="' . $settings['images_url'] . '/buttons/search.gif" alt="" class="icon" />' : ' ', $txt['set_parameters'], '</span>
			</h3>
		</div>';

	if (!empty($context['search_errors']))
		echo '
		<p id="search_error" class="error">', implode('<br />', $context['search_errors']['messages']), '</p>';

	// Simple Search?
	if ($context['simple_search'])
	{
		echo '
		<fieldset id="simple_search">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<div id="search_term_input">
					<strong>', $txt['search_for'], ':</strong>
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input_text" />
					', $context['require_verification'] ? '' : '&nbsp;<input type="submit" name="submit" value="' . $txt['search'] . '" class="button_submit" />
				</div>';

		if (empty($modSettings['search_simple_fulltext']))
			echo '
				<p class="smalltext">', $txt['search_example'], '</p>';

		if ($context['require_verification'])
			echo '
				<div class="verification>
					<strong>', $txt['search_visual_verification_label'], ':</strong>
					<br />', template_control_verification($context['visual_verification_id'], 'all'), '<br />
					<input id="submit" type="submit" name="submit" value="' . $txt['search'] . '" class="button_submit" />
				</div>';

		echo '
				<a href="', $scripturl, '?action=search;advanced" onclick="this.href += \';search=\' + escape(document.forms.searchform.search.value);">', $txt['search_advanced'], '</a>
				<input type="hidden" name="advanced" value="0" />
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>';
	}

	// Advanced search!
	else
	{
		echo '
		<fieldset id="advanced_search">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<input type="hidden" name="advanced" value="1" />
				<span class="enhanced">
					<strong>', $txt['search_for'], ':</strong>
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input_text" />
					<script type="text/javascript"><!-- // --><![CDATA[
						function initSearch()
						{
							if (document.forms.searchform.search.value.indexOf("%u") != -1)
								document.forms.searchform.search.value = unescape(document.forms.searchform.search.value);
						}
						createEventListener(window);
						window.addEventListener("load", initSearch, false);
					// ]]></script>
					<select name="searchtype">
						<option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['all_words'], '</option>
						<option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['any_words'], '</option>
					</select>
				</span>';

		if (empty($modSettings['search_simple_fulltext']))
			echo '
				<em class="smalltext">', $txt['search_example'], '</em>';

		echo '
				<dl id="search_options">
					<dt>', $txt['by_user'], ':</dt>
					<dd><input id="userspec" type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" class="input_text" /></dd>
					<dt>', $txt['search_order'], ':</dt>
					<dd>
						<select id="sort" name="sort">
							<option value="relevance|desc">', $txt['search_orderby_relevant_first'], '</option>
							<option value="num_replies|desc">', $txt['search_orderby_large_first'], '</option>
							<option value="num_replies|asc">', $txt['search_orderby_small_first'], '</option>
							<option value="id_msg|desc">', $txt['search_orderby_recent_first'], '</option>
							<option value="id_msg|asc">', $txt['search_orderby_old_first'], '</option>
						</select>
					</dd>
					<dt class="options">', $txt['search_options'], ':</dt>
					<dd class="options">
						<label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['search_show_complete_messages'], '</label><br />
						<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['search_subject_only'], '</label>
					</dd>
					<dt class="between">', $txt['search_post_age'], ': </dt>
					<dd>', $txt['search_between'], ' <input type="text" name="minage" value="',
						empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="4" class="input_text" />&nbsp;', $txt['search_and'], '&nbsp;<input type="text" name="maxage" value="',
						empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="4" class="input_text" /> ', $txt['days_word'], '</dd>
				</dl>';

		// Require an image to be typed to save spamming?
		if ($context['require_verification'])
		{
			echo '
				<p>
					<strong>', $txt['verification'], ':</strong>
					', template_control_verification($context['visual_verification_id'], 'all'), '
				</p>';
		}

		// If $context['search_params']['topic'] is set, that means we're searching just one topic.
		if (!empty($context['search_params']['topic']))
			echo '
				<p>', $txt['search_specific_topic'], ' &quot;', $context['search_topic']['link'], '&quot;.</p>
				<input type="hidden" name="topic" value="', $context['search_topic']['id'], '" />';

		echo '
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>';

		if (empty($context['search_params']['topic']))
		{
			echo '
		<fieldset class="flow_hidden">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<div class="title_bar">
					<h4 class="titlebg">
						<a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;"><img src="', $settings['images_url'], '/expand.gif" id="expandBoardsIcon" alt="" /></a> <a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;"><strong>', $txt['choose_board'], '</strong></a>
					</h4>
				</div>
				<div class="flow_auto" id="searchBoardsExpand"', $context['boards_check_all'] ? ' style="display: none;"' : '', '>
					<ul class="ignoreboards floatleft">';

	$i = 0;
	$limit = ceil($context['num_boards'] / 2);
	foreach ($context['categories'] as $category)
	{
		echo '
						<li class="category">
							<a href="javascript:void(0);" onclick="selectBoards([', implode(', ', $category['child_ids']), ']); return false;">', $category['name'], '</a>
							<ul>';

		foreach ($category['boards'] as $board)
		{
			if ($i == $limit)
				echo '
							</ul>
						</li>
					</ul>
					<ul class="ignoreboards floatright">
						<li class="category">
							<ul>';

			echo '
								<li class="board">
									<label for="brd', $board['id'], '" style="margin-', $context['right_to_left'] ? 'right' : 'left', ': ', $board['child_level'], 'em;"><input type="checkbox" id="brd', $board['id'], '" name="brd[', $board['id'], ']" value="', $board['id'], '"', $board['selected'] ? ' checked="checked"' : '', ' class="input_check" /> ', $board['name'], '</label>
								</li>';

			$i ++;
		}

		echo '
							</ul>
						</li>';
	}

	echo '
					</ul>
				</div>
				<br class="clear" />';

			echo '
				<div class="padding">
					<input type="checkbox" name="all" id="check_all" value=""', $context['boards_check_all'] ? ' checked="checked"' : '', ' onclick="invertAll(this, this.form, \'brd\');" class="input_check floatleft" />
					<label for="check_all" class="floatleft">', $txt['check_all'], '</label>
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit floatright" />
				</div>
				<br class="clear" />
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>';
		}

	}

	echo '
	</form>

	<script type="text/javascript"><!-- // --><![CDATA[
		function selectBoards(ids)
		{
			var toggle = true;

			for (i = 0; i < ids.length; i++)
				toggle = toggle & document.forms.searchform["brd" + ids[i]].checked;

			for (i = 0; i < ids.length; i++)
				document.forms.searchform["brd" + ids[i]].checked = !toggle;
		}

		function expandCollapseBoards()
		{
			var current = document.getElementById("searchBoardsExpand").style.display != "none";

			document.getElementById("searchBoardsExpand").style.display = current ? "none" : "";
			document.getElementById("expandBoardsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
		}';

	echo '
	// ]]></script>';
}

function template_results() {
	global $context, $settings, $options, $txt, $scripturl, $message;

	echo '<div id="search_results">';
		template_search_header( $txt['mlist_search_results'] . ':&nbsp;' . $context['search_params']['search'] );

	echo '<form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="topicForm">';
		template_results_topics();
	echo '</form';

	echo '</div>';
}

function template_search_header($headerText) {
	global $context, $txt, $options;

	echo '
	<div class="cat_bar">
		<h3 class="titlebg">' .	$headerText;

			if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1) {
				echo '
					<div style="float:right;margin-top:15px;">
						<input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" />
					</div>';
			}

		echo '
		</h3>
	</div>';

	echo '<div class="pagesection">';
		echo '<div class="pagelinks">', $context['page_index'], '</div>';
	echo '</div>';
}

function template_results_topics() {
	global $context, $settings, $options, $txt, $scripturl, $message;

	while ($topic = $context['get_topics']()) {

		$color_class = '';
		if ($topic['is_sticky']) $color_class = 'stickybg';
		if ($topic['is_locked']) $color_class .= 'lockedbg';

		foreach ($topic['matches'] as $message) {
			echo '
				<div class="search_results_posts ', $message['alternate'] == 0 ? 'windowbg' : 'windowbg2', '">
					<div class="core_posts">';

					echo '
						<div class="grid-group topic_details">';

						echo '
							<div class="counter grid size-1">', $message['counter'], '</div>
							<div class="grid size-10">',
				      	'<h4>', $topic['board']['link'], '<i class="icon-up-open"></i><a href="', $scripturl, '?topic=', $topic['id'], '.msg', $message['id'], '#msg', $message['id'], '">', $message['subject_highlighted'], '</a></h4>
								<span class="smalltext">', $txt['by'],'&nbsp;<strong>', $message['member']['link'], '</strong>&nbsp;',$txt['on'],'&nbsp;', $message['time'], '&nbsp;</span>
				    	</div>';

							if (!empty($options['display_quick_mod'])) {
								echo '
									<div class="grid size-1 quick_mod">';

									if ($options['display_quick_mod'] == 1) {
										echo '<input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />';
									}

								echo '
									</div>';
							}

					echo '
						</div>';

						if ($message['body_highlighted'] != '') {
							echo '
							  <br class="clear" />
							  <div class="list_posts grid-group">';

							  echo '<div class="grid size-1">&nbsp;</div><div class="grid size-11">', $message['body_highlighted'], '</div>';

							echo '
							  </div>';
						}

					echo '
						</div>
					</div>';
		}
	}

	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics'])) {
		echo '
			<select name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
				<option value="">--------</option>', $context['can_remove'] ? '
				<option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', $context['can_lock'] ? '
				<option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', $context['can_sticky'] ? '
				<option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '',	$context['can_move'] ? '
				<option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', $context['can_merge'] ? '
				<option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', '
				<option value="markread">', $txt['quick_mod_markread'], '</option>
			</select>';

		if ($context['can_move']) {
				echo '
				<select id="moveItTo" name="move_to" disabled="disabled">';

				foreach ($context['move_to_boards'] as $category)
				{
					echo '
					<optgroup label="', $category['name'], '">';
					foreach ($category['boards'] as $board)
							echo '
					<option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', '>', $board['child_level'] > 0 ? str_repeat('==', $board['child_level'] - 1) . '=&gt;' : '', ' ', $board['name'], '</option>';
					echo '
					</optgroup>';
				}
				echo '
				</select>';
		}

		echo '
				<input type="hidden" name="redirect_url" value="', $scripturl . '?action=search2;params=' . $context['params'], '" />
				<input type="submit" value="', $txt['quick_mod_go'], '" onclick="return this.form.qaction.value != \'\' &amp;&amp; confirm(\'', $txt['quickmod_confirm'], '\');" class="button_submit" />
				<input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />';
	}


}

?>
