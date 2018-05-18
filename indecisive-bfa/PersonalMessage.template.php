<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.2
 */

// This is the main sidebar for the personal messages section.
function template_pm_above()
{
	global $context, $settings, $options, $txt;

	echo '
	<div id="personal_messages">';

	// Show the capacity bar, if available.
	if (!empty($context['limit_bar']))
	{
		echo '
			<table width="100%" cellspacing="1" cellpadding="3" class="bordercolor" style="margin-bottom: 1em">
				<tr class="titlebg2">
					<td width="200" align="right"><strong>', $txt['pm_capacity'], ':</strong></td>
					<td width="50%">
						<div class="capacity_bar">
							<div class="', $context['limit_bar']['percent'] > 85 ? 'full' : ($context['limit_bar']['percent'] > 40 ? 'filled' : 'empty'), '" style="width: ', $context['limit_bar']['bar'], '%;"></div>
						</div>
					</td>
					<td width="200"', $context['limit_bar']['percent'] > 90 ? ' class="alert"' : '', '>
						', $context['limit_bar']['text'], '
					</td>
				</tr>
			</table>';
	}

	// Message sent? Show a small indication.
	if (isset($context['pm_sent']))
		echo '
			<div class="windowbg" id="profile_success">
				', $txt['pm_sent'], '
			</div>';
}

// Just the end of the index bar, nothing special.
function template_pm_below()
{
	global $context, $settings, $options;

	echo '
	</div>';
}

function template_folder()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// The every helpful javascript!
	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		var allLabels = {};
		var currentLabels = {};
		function loadLabelChoices()
		{
			var listing = document.forms.pmFolder.elements;
			var theSelect = document.forms.pmFolder.pm_action;
			var add, remove, toAdd = {length: 0}, toRemove = {length: 0};

			if (theSelect.childNodes.length == 0)
				return;';

	// This is done this way for internationalization reasons.
	echo '
			if (!(\'-1\' in allLabels))
			{
				for (var o = 0; o < theSelect.options.length; o++)
					if (theSelect.options[o].value.substr(0, 4) == "rem_")
						allLabels[theSelect.options[o].value.substr(4)] = theSelect.options[o].text;
			}

			for (var i = 0; i < listing.length; i++)
			{
				if (listing[i].name != "pms[]" || !listing[i].checked)
					continue;

				var alreadyThere = [], x;
				for (x in currentLabels[listing[i].value])
				{
					if (!(x in toRemove))
					{
						toRemove[x] = allLabels[x];
						toRemove.length++;
					}
					alreadyThere[x] = allLabels[x];
				}

				for (x in allLabels)
				{
					if (!(x in alreadyThere))
					{
						toAdd[x] = allLabels[x];
						toAdd.length++;
					}
				}
			}

			while (theSelect.options.length > 2)
				theSelect.options[2] = null;

			if (toAdd.length != 0)
			{
				theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_apply'], '", "");
				setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_apply'], '");
				theSelect.options[theSelect.options.length - 1].disabled = true;

				for (i in toAdd)
				{
					if (i != "length")
						theSelect.options[theSelect.options.length] = new Option(toAdd[i], "add_" + i);
				}
			}

			if (toRemove.length != 0)
			{
				theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_remove'], '", "");
				setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_remove'], '");
				theSelect.options[theSelect.options.length - 1].disabled = true;

				for (i in toRemove)
				{
					if (i != "length")
						theSelect.options[theSelect.options.length] = new Option(toRemove[i], "rem_" + i);
				}
			}
		}
	// ]]></script>';

	echo '
<form action="', $scripturl, '?action=pm;sa=pmactions;', $context['display_mode'] == 2 ? 'conversation;' : '', 'f=', $context['folder'], ';start=', $context['start'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '" method="post" accept-charset="', $context['character_set'], '" name="pmFolder">';

	// If we are not in single display mode show the subjects on the top!
	if ($context['display_mode'] != 1)
	{
		template_subject_list();
		echo '<br />';
	}

	// Got some messages to display?
	if ($context['get_pmessage']('message', true))
	{
		// Show a few buttons if we are in conversation mode and outputting the first message.
		if ($context['display_mode'] == 2)
		{
			// Build the normal button array.
			$conversation_buttons = array(
				'reply' => array('text' => 'reply_to_all', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=pm;sa=send;f=' . $context['folder'] . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '') . ';pmsg=' . $context['current_pm'] . ';u=all'),
				'delete' => array('text' => 'delete_conversation', 'image' => 'delete.gif', 'lang' => true, 'url' => $scripturl . '?action=pm;sa=pmactions;pm_actions[' . $context['current_pm'] . ']=delete;conversation;f=' . $context['folder'] . ';start=' . $context['start'] . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '') . ';' . $context['session_var'] . '=' . $context['session_id'], 'custom' => 'onclick="return confirm(\'' . addslashes($txt['remove_message']) . '?\');"'),
			);

			// Show the conversation buttons.
			echo '
				<div class="modbuttons_top margintop flow_hidden right">';

			template_button_strip_with_icons_and_text($conversation_buttons, 'right');

			echo '
				</div>';
		}

	echo '
	<div class="tborder" style="padding: 1px">';

		// Show the helpful titlebar - generally.
		if ($context['display_mode'] != 1)
			echo '
		<div id="forumposts">
			<h3 class="titlebg">
				<span>', $txt['author'], '</span>
				<span id="top_subject">', $txt[$context['display_mode'] == 0 ? 'messages' : 'conversation'], '</span>
			</h3>
		</div>';

		// Cache some handy buttons.
		$quote_button = create_button('quote.gif', 'reply_quote', 'quote', 'align="middle"');
		$reply_button = create_button('im_reply.gif', 'reply', 'reply', 'align="middle"');
		$reply_all_button = create_button('im_reply_all.gif', 'reply_to_all', 'reply_to_all', 'align="middle"');
		$forward_button = create_button('quote.gif', 'reply_quote', 'reply_quote', 'align="middle"');
		$delete_button = create_button('delete.gif', 'remove_message', 'remove', 'align="middle"');

		while ($message = $context['get_pmessage']('message'))
		{
			$is_first_post = !isset($is_first_post) ? true : false;

			// Show information about the poster of this message.
			echo '
		<div class="bordercolor size-12" id="msg', $message['id'], '">
			<div class="clearfix ', !$is_first_post ? 'topborder ' : '', ($message['alternate'] == 0 ? '' : ''), ' grid-group">
				<div class="grid size-2 size-12--palm poster">
					<h3 class="titlebg">', $message['member']['link'], '</h3>
					<ul class="reset smalltext" id="msg_', $message['id'], '_extra_info" style="list-style: none;">';



		// Show online and offline buttons?
		if (!empty($modSettings['onlineEnable']) && !$message['member']['is_guest'])
			echo $context['can_send_pm'] ? '<a href="' . $message['member']['online']['href'] . '" title="' . $message['member']['online']['label'] . '">' : '',  $context['can_send_pm'] ? '</a>' : '';



		// Show the member's custom title, if they have one.
		if (!empty($message['member']['title']))
			echo '
								<li class="title">', $message['member']['title'], '</li>';

		// Show the member's primary group (like 'Administrator') if they have one.
		echo '<li class="membergroup">';
		if (!empty($message['member']['group']))
			echo $message['member']['group'];
		else
			echo 'Applicant';
		echo '</li>';

		// Don't show these things for guests.
		if (!$message['member']['is_guest'])
		{
			// Show avatars, images, etc.?

				echo '
					<li class="avatar">
						<a href="', $scripturl, '?action=profile;u=', $message['member']['id'], '">';

						if($message['member']['online']['label'] == 'Online')
							$icon = '/inde/classicon-'.strtolower($message['member']['options']['cust_class']).'.png';
						else {
							$icon = '/inde/classicon-'.strtolower($message['member']['options']['cust_class']).'-dis.png';
						}

						if(!$message['member']['avatar']['image']) {

							echo '
							<div class="member__image member__image--no-image round  class-background--'.str_replace(' ', '_', strtolower($message['member']['options']['cust_class'])).'">'.substr($message['member']['name'], 0, 2).'
								<div class="member__icon round class-border--'.str_replace(' ', '_', strtolower($message['member']['options']['cust_class'])).'" style="background-image: url('.$icon.')"></div>
							</div>';
						} else {

							$src = explode('src="', $message['member']['avatar']['image']);
							$src = explode('"', $src[1]);
							echo '
							<div class="member__image round  " style="background-image: url('.$src[0].')">
								<div class="member__icon round" style="background-image: url('.$icon.')"></div>
							</div>';

						}

				echo '</li>';

				// Show their personal text?
				if (!empty($settings['show_blurb']) && $message['member']['blurb'] != '')
					echo '
						<li class="blurb">', $message['member']['blurb'], '</li>';

			// Show the profile, website, email address, and personal message buttons.
			if ($settings['show_profile_buttons'])
			{
				echo '
								<li class="profile">
									<div class="member__profile">';
				// Don't show the profile button if you're not allowed to view the profile.
				if ($message['member']['can_view_profile'])
					echo '
										<div class="member__profile__icon member__profile__icon--first"><a href="', $message['member']['href'], '" class="member__profile--icon round icon-user-1"></a></div>';


				// Since we know this person isn't a guest, you *can* message them.
				if ($context['can_send_pm'])
					echo '
										<div class="member__profile__icon">
											<a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline'], '" class="member__profile--icon round icon-mail"></a></div>';

				echo '

				</li>';
			}




			// Any custom fields to show as icons?
			/*if (!empty($message['member']['custom_fields']))
			{
				$shown = false;
				foreach ($message['member']['custom_fields'] as $custom)
				{
					if ($custom['placement'] != 1 || empty($custom['value']))
						continue;
					if (empty($shown))
					{
						$shown = true;
						echo '
						<li class="margintop">
							<ul class="reset nolist">';
					}
					echo '
								<li>', $custom['value'], '</li>';
				}
				if ($shown)
					echo '
							</ul>
						</li>';
			}

			// This shows the popular messaging icons.

			/*
			if ($message['member']['has_messenger'] && $message['member']['can_view_profile'])
				echo '
						<li class="margintop">
							<ul class="reset nolist">
								', !isset($context['disabled_fields']['icq']) && !empty($message['member']['icq']['link']) ? '<li>' . $message['member']['icq']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['msn']) && !empty($message['member']['msn']['link']) ? '<li>' . $message['member']['msn']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['aim']) && !empty($message['member']['aim']['link']) ? '<li>' . $message['member']['aim']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['yim']) && !empty($message['member']['yim']['link']) ? '<li>' . $message['member']['yim']['link'] . '</li>' : '', '
							</ul>
						</li>';
			*/
			// Show the profile, website, email address, and personal message buttons.
			if ($settings['show_profile_buttons'])
			{
				echo '
						<li class="margintop">
							<ul class="reset nolist">';
				// Don't show the profile button if you're not allowed to view the profile.
				/*if ($message['member']['can_view_profile'])
					echo '
								<li><a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt['view_profile'] . '" title="' . $txt['view_profile'] . '" border="0" />' : $txt['view_profile']), '</a></li>';
				*/
				/*
				// Don't show an icon if they haven't specified a website.
				if ($message['member']['website']['url'] != '' && !isset($context['disabled_fields']['website']))
					echo '
								<li><a href="', $message['member']['website']['url'], '" title="' . $message['member']['website']['title'] . '" target="_blank" class="new_win">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/www_sm.gif" alt="' . $message['member']['website']['title'] . '" border="0" />' : $txt['www']), '</a></li>';
				*/
				/*
				// Don't show the email address if they want it hidden.
				if (in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
					echo '
								<li><a href="', $scripturl, '?action=emailuser;sa=email;uid=', $message['member']['id'], '" rel="nofollow">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . '" />' : $txt['email']), '</a></li>';
				*/
				/*
				// Since we know this person isn't a guest, you *can* message them.
				if ($context['can_send_pm'])
					echo '
								<li><a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/im_' . ($message['member']['online']['is_online'] ? 'on' : 'off') . '.gif" alt="' . ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']) . '" border="0" />' : ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']), '</a></li>';
				*/
				echo '
							</ul>
						</li>';
			}

			// Are we showing the warning status?
			if ($message['member']['can_see_warning'])
				echo '
						<li>', $context['can_issue_warning'] ? '<a href="' . $scripturl . '?action=profile;area=issuewarning;u=' . $message['member']['id'] . '">' : '', '<img src="', $settings['images_url'], '/warning_', $message['member']['warning_status'], '.gif" alt="', $txt['user_warn_' . $message['member']['warning_status']], '" />', $context['can_issue_warning'] ? '</a>' : '', '<span class="warn_', $message['member']['warning_status'], '">', $txt['warn_' . $message['member']['warning_status']], '</span></li>';
		}

		// Done with the information about the poster... on to the post itself.
		echo '
					</ul>
				</div>
				<div class="postarea grid size-10 size-12--palm">
					<div class="flow_hidden">
						<div class="keyinfo">
							<h3 class="titlebg">
								<strong>', $message['subject'], '</strong>';

			echo '<span class="grid grid--last">';
			// Show reply buttons if you have the permission to send PMs.
			if ($context['can_send_pm'])
			{
				// You can't really reply if the member is gone.
				if (!$message['member']['is_guest'])
				{
					// Were than more than one recipient you can reply to? (Only shown when not in conversation mode.)
					if ($message['number_recipients'] > 1 && $context['display_mode'] != 2)
						echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=all" class="icon-reply"></a>';

						echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '" class="icon-reply"></a>
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote', $context['folder'] == 'sent' ? '' : ';u=' . $message['member']['id'], '" class="icon-quote"></a>';
				}
				// This is for "forwarding" - even if the member is gone.
				else
						echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">', $forward_button, '</a>';
			}
						echo '
							<a href="', $scripturl, '?action=pm;sa=pmactions;pm_actions[', $message['id'], ']=delete;f=', $context['folder'], ';start=', $context['start'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', addslashes($txt['remove_message']), '?\');" class="icon-cancel-1"></a>';

			if (empty($context['display_mode']))
				echo '
							<input style="vertical-align: middle;" type="checkbox" name="pms[]" id="deletedisplay', $message['id'], '" value="', $message['id'], '" onclick="document.getElementById(\'deletelisting', $message['id'], '\').checked = this.checked;" class="input_check" />';
			echo '</span>';
			echo '</h3>';

			// Show who the message was sent to.
			echo '
							<div class="smalltext">
								&#171; <strong> ', $txt['sent_to'], ':</strong> ';

			// People it was sent directly to....
			if (!empty($message['recipients']['to']))
				echo implode(', ', $message['recipients']['to']);
			// Otherwise, we're just going to say "some people"...
			elseif ($context['folder'] != 'sent')
				echo '(', $txt['pm_undisclosed_recipients'], ')';

			echo '
								<strong> ', $txt['on'], ':</strong> ', $message['time'], ' &#187;
							</div>';

			// If we're in the sent items, show who it was sent to besides the "To:" people.
			if (!empty($message['recipients']['bcc']))
				echo '
							<div class="smalltext">&#171; <strong> ', $txt['pm_bcc'], ':</strong> ', implode(', ', $message['recipients']['bcc']), ' &#187;</div>';

			if (!empty($message['is_replied_to']))
				echo '
							<div class="smalltext">&#171; ', $txt['pm_is_replied_to'], ' &#187;</div>';

			echo '
						</div>
					</div>
					<div class="personalmessage">

						', $message['body'], '
					</div>';

			// Are there any custom profile fields for above the signature?
			if (!empty($message['member']['custom_fields']))
			{
				$shown = false;
				foreach ($message['member']['custom_fields'] as $custom)
				{
					if ($custom['placement'] != 2 || empty($custom['value']))
						continue;
					if (!$shown)
					{
						$shown = true;
						echo '
						<div class="custom_fields_above_signature">
							<ul class="reset nolist>';
					}
					echo '
								<li>', $custom['value'], '</li>';
				}
				if ($shown)
					echo '
							</ul>
						</div>';
			}

			// Show the member's signature?
			if (!empty($message['member']['signature']) && empty($options['show_no_signatures']) && $context['signature_enabled'])
				echo '
						<div class="divider"></div><br /><div class="signature">', $message['member']['signature'], '</div>';

			// Add an extra line at the bottom if we have labels enabled.
			if ($context['folder'] != 'sent' && !empty($context['currently_using_labels']) && $context['display_mode'] != 2)
			{
				echo '
						<div class="labels righttext">';

				// Add the label drop down box.
				if (!empty($context['currently_using_labels']))
				{
					echo '
							<select name="pm_actions[', $message['id'], ']" onchange="if (this.options[this.selectedIndex].value) form.submit();">
								<option value="">', $txt['pm_msg_label_title'], ':</option>
							<option value="" disabled="disabled">---------------</option>';

					// Are there any labels which can be added to this?
					if (!$message['fully_labeled'])
					{
						echo '
							<option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';

						foreach ($context['labels'] as $label)
						{
							if (!isset($message['labels'][$label['id']]))
								echo '
							<option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
						}
					}

					// ... and are there any that can be removed?
					if (!empty($message['labels']) && (count($message['labels']) > 1 || !isset($message['labels'][-1])))
					{
						echo '
								<option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
						foreach ($message['labels'] as $label)
							echo '
								<option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
					}
					echo '
							</select>
							<noscript>
								<input type="submit" value="', $txt['pm_apply'], '" class="button_submit" />
							</noscript>';
				}

				echo '
						</div>';
			}

			echo '
					</div>
				</div>
			</div>';
		}

		echo '
		</div>';

		if (empty($context['display_mode']))
			echo '
		<div class="catbg flow_hidden" style="padding: 1px; margin-top: 1ex;">
			<div class="floatleft pagesection">', $txt['pages'], ': ', $context['page_index'], '</div>
			<div class="floatright"><input type="submit" name="del_selected" value="', $txt['quickmod_delete_selected'], '" style="font-weight: normal;" onclick="if (!confirm(\'', $txt['delete_selected_confirm'], '\')) return false;" class="button_submit" /></div>
		</div>';

		// Show a few buttons if we are in conversation mode and outputting the first message.
		elseif ($context['display_mode'] == 2 && isset($conversation_buttons)) {
			echo '<div class="grid-group grid--last">';
			template_button_strip_with_icons_and_text($conversation_buttons);
			echo '</div>';
		}

		echo '
		<br />';
	}

	// Individual messages = buttom list!
	if ($context['display_mode'] == 1)
	{
		template_subject_list();
		echo '<br />';
	}

	echo '
	<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
</form>';
}

// Just list all the personal message subjects - to make templates easier.
function template_subject_list()
{
	global $context, $options, $settings, $modSettings, $txt, $scripturl;

	?>

	<h3 class="private-message__header titlebg">
		<div class="grid size-1">Status</div>
		<div class="grid size-2">Date</div>
		<div class="grid size-5">Subject</div>
		<div class="grid size-3">From</div>
		<div class="grid size-1 align-right">&nbsp;<input type="checkbox" /></div>

	</h3>

	<?

	/*
	echo '
		<table width="100%" class="table_grid">
			<tr class="titlebg">
				<td align="center" width="2%"><a href="', $scripturl, '?action=pm;view;f=', $context['folder'], ';start=', $context['start'], ';sort=', $context['sort_by'], ($context['sort_direction'] == 'up' ? '' : ';desc'), ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : ''), '"><img src="', $settings['images_url'], '/im_switch.gif" alt="', $txt['pm_change_view'], '" title="', $txt['pm_change_view'], '" width="16" height="16" /></a></td>
				<td style="width: 32ex;"><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=date', $context['sort_by'] == 'date' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt['date'], $context['sort_by'] == 'date' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></td>
				<td width="46%"><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt['subject'], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></td>
				<td><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', ($context['from_or_to'] == 'from' ? $txt['from'] : $txt['to']), $context['sort_by'] == 'name' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></td>
				<td align="center" width="24"><input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" /></td>
			</tr>';
	*/
	if (!$context['show_delete'])
		echo '
			<tr>
				<td class="windowbg" colspan="5">', $txt['msg_alert_none'], '</td>
			</tr>';

	$next_alternate = 0;
	$i = -1;
	while ($message = $context['get_pmessage']('subject')) {
		$i++;



		echo '<div class="grid size-12 private-message__content '.($i % 2 ? 'odd' : 'even').'">';





		echo '
				<div class="grid size-1">
				<script type="text/javascript"><!-- // --><![CDATA[
					currentLabels[', $message['id'], '] = {';

		if (!empty($message['labels']))
		{
			$first = true;
			foreach ($message['labels'] as $label)
			{
				echo $first ? '' : ',', '
					"', $label['id'], '": "', $label['name'], '"';
				$first = false;
			}
		}

		echo '
					};
				// ]]></script>
					', $message['is_replied_to'] ? ' <span class="icon-comment-1"></span>' : '<span class="icon-comment-empty"></span>', '</div>

				<div class="grid size-2">', $message['time'], '</div>
				<div class="grid size-5">', ($context['display_mode'] != 0 && $context['current_pm'] == $message['id'] ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="*" />' : ''), '<a href="', ($context['display_mode'] == 0 || $context['current_pm'] == $message['id'] ? '' : ($scripturl . '?action=pm;pmid=' . $message['id'] . ';kstart;f=' . $context['folder'] . ';start=' . $context['start'] . ';sort=' . $context['sort_by'] . ($context['sort_direction'] == 'up' ? ';' : ';desc') . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : ''))), '#msg', $message['id'], '">', $message['subject'], '</a>', $message['is_unread'] ? '&nbsp;<img src="' . $settings['lang_images_url'] . '/new.gif" alt="' . $txt['new'] . '" />' : '', '</div>
				<div class="grid size-3">', ($context['from_or_to'] == 'from' ? $message['member']['link'] : (empty($message['recipients']['to']) ? '' : implode(', ', $message['recipients']['to']))), '</div>
				<div class="grid size-1 align-right"><input type="checkbox" name="pms[]" id="deletelisting', $message['id'], '" value="', $message['id'], '"', $message['is_selected'] ? ' checked="checked"' : '', ' onclick="if (document.getElementById(\'deletedisplay', $message['id'], '\')) document.getElementById(\'deletedisplay', $message['id'], '\').checked = this.checked;" class="input_check" /></div>
			</div>';

	}

	echo '

		<div class="catbg flow_hidden smallpadding ">
			<div class="left pagesection">', $txt['pages'], ': ', $context['page_index'], '</div>
			<div class="grid-group grid--last">&nbsp;';

	if ($context['show_delete'])
	{
		if (!empty($context['currently_using_labels']) && $context['folder'] != 'sent')
		{
			echo '
				<select name="pm_action" onchange="if (this.options[this.selectedIndex].value) this.form.submit();" onfocus="loadLabelChoices();">
					<option value="">', $txt['pm_sel_label_title'], ':</option>
					<option value="" disabled="disabled">---------------</option>';

			echo '
									<option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';
			foreach ($context['labels'] as $label)
				if ($label['id'] != $context['current_label_id'])
					echo '
					<option value="add_', $label['id'], '">&nbsp;', $label['name'], '</option>';
			echo '
					<option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
			foreach ($context['labels'] as $label)
				echo '
					<option value="rem_', $label['id'], '">&nbsp;', $label['name'], '</option>';
			echo '
				</select>
				<noscript>
					<input type="submit" value="', $txt['pm_apply'], '" class="button_submit" />
				</noscript>';
		}

		echo '
				<input type="submit" name="del_selected" value="', $txt['quickmod_delete_selected'], '" onclick="if (!confirm(\'', $txt['delete_selected_confirm'], '\')) return false;" class="button_submit button" />';
	}

	echo '
			</div>
		</div>';
}

function template_search()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		function expandCollapseLabels()
		{
			var current = document.getElementById("searchLabelsExpand").style.display != "none";

			document.getElementById("searchLabelsExpand").style.display = current ? "none" : "";
			document.getElementById("expandLabelsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
		}
	// ]]></script>
	<form action="', $scripturl, '?action=pm;sa=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_search_title'], '</h3>
		</div>';

	if (!empty($context['search_errors']))
	{
		echo '
		<div class="errorbox">
			', implode('<br />', $context['search_errors']['messages']), '
		</div>';
	}

	if ($context['simple_search'])
	{
		echo '
		<fieldset id="simple_search">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<div id="search_term_input">
					<strong>', $txt['pm_search_text'], ':</strong>
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" class="input_text" />
					<input type="submit" name="submit" value="', $txt['pm_search_go'], '" class="button_submit" />
				</div>
				<a href="', $scripturl, '?action=pm;sa=search;advanced" onclick="this.href += \';search=\' + escape(document.forms.searchform.search.value);">', $txt['pm_search_advanced'], '</a>
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
					<strong>', $txt['pm_search_text'], ':</strong>
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" class="input_text" />
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
						<option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_all'], '</option>
						<option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_any'], '</option>
					</select>
				</span>
				<dl id="search_options">
					<dt>', $txt['pm_search_user'], ':</dt>
					<dd><input type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" class="input_text" /></dd>
					<dt>', $txt['pm_search_order'], ':</dt>
					<dd>
						<select name="sort">
							<option value="relevance|desc">', $txt['pm_search_orderby_relevant_first'], '</option>
							<option value="id_pm|desc">', $txt['pm_search_orderby_recent_first'], '</option>
							<option value="id_pm|asc">', $txt['pm_search_orderby_old_first'], '</option>
						</select>
					</dd>
					<dt class="options">', $txt['pm_search_options'], ':</dt>
					<dd class="options">
						<label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_search_show_complete'], '</label><br />
						<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_search_subject_only'], '</label>
					</dd>
					<dt class="between">', $txt['pm_search_post_age'], ':</dt>
					<dd>', $txt['pm_search_between'], ' <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="5" class="input_text" />&nbsp;', $txt['pm_search_between_and'], '&nbsp;<input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="5" class="input_text" /> ', $txt['pm_search_between_days'], '</dd>
				</dl>
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>';

		// Do we have some labels setup? If so offer to search by them!
		if ($context['currently_using_labels'])
		{
			echo '
		<fieldset class="labels">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<div class="title_bar">
					<h4 class="titlebg">
						<span class="ie6_header floatleft"><a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><img src="', $settings['images_url'], '/expand.gif" id="expandLabelsIcon" alt="" /></a> <a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><strong>', $txt['pm_search_choose_label'], '</strong></a></span>
					</h4>
				</div>
				<ul id="searchLabelsExpand" class="reset" ', $context['check_all'] ? 'style="display: none;"' : '', '>';

			foreach ($context['search_labels'] as $label)
				echo '
					<li>
						<label for="searchlabel_', $label['id'], '"><input type="checkbox" id="searchlabel_', $label['id'], '" name="searchlabel[', $label['id'], ']" value="', $label['id'], '" ', $label['checked'] ? 'checked="checked"' : '', ' class="input_check" />
						', $label['name'], '</label>
					</li>';

			echo '
				</ul>
				<p>
					<input type="checkbox" name="all" id="check_all" value="" ', $context['check_all'] ? 'checked="checked"' : '', ' onclick="invertAll(this, this.form, \'searchlabel\');" class="input_check" /><em> <label for="check_all">', $txt['check_all'], '</label></em>
				</p>
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>';
		}

		echo '
		<div class="righttext padding">
			<input type="submit" name="submit" value="', $txt['pm_search_go'], '" class="button_submit" />
		</div>';
	}

	echo '
		</table>
	</form>';
}

function template_search_results()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// This splits broadly into two types of template... complete results first.
	if (!empty($context['search_params']['show_complete']))
	{
		echo '
		<table border="0" width="100%" align="center" cellpadding="3" cellspacing="1" class="bordercolor">
			<tr class="titlebg">
				<td colspan="3">', $txt['pm_search_results'], '</td>
			</tr>
			<tr class="catbg" height="30">
				<td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
			</tr>
		</table>';
	}
	else
	{
		echo '
		<table border="0" width="100%" align="center" cellpadding="3" cellspacing="1" class="bordercolor">
			<tr class="titlebg">
				<td colspan="3">', $txt['pm_search_results'], '</td>
			</tr>
			<tr class="catbg">
				<td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
			</tr>
			<tr class="titlebg">
				<td width="30%">', $txt['date'], '</td>
				<td width="50%">', $txt['subject'], '</td>
				<td width="20%">', $txt['from'], '</td>
			</tr>';
	}

	$alternate = true;
	// Print each message out...
	foreach ($context['personal_messages'] as $message)
	{
		// We showing it all?
		if (!empty($context['search_params']['show_complete']))
		{
			// !!! This still needs to be made pretty.
			echo '
		<br />
		<table width="100%" align="center" cellpadding="3" cellspacing="1" border="0" class="bordercolor">
			<tr class="titlebg">
				<td align="left">
					<div class="floatleft">
					', $message['counter'], '&nbsp;&nbsp;<a href="', $message['href'], '">', $message['subject'], '</a>
					</div>
					<div class="floatright">
						', $txt['search_on'], ': ', $message['time'], '
					</div>
				</td>
			</tr>
			<tr class="catbg">
				<td>', $txt['from'], ': ', $message['member']['link'], ', ', $txt['to'], ': ';

			// Show the recipients.
			// !!! This doesn't deal with the sent item searching quite right for bcc.
			if (!empty($message['recipients']['to']))
				echo implode(', ', $message['recipients']['to']);
			// Otherwise, we're just going to say "some people"...
			elseif ($context['folder'] != 'sent')
				echo '(', $txt['pm_undisclosed_recipients'], ')';

			echo '
				</td>
			</tr>
			<tr class="windowbg2" valign="top">
				<td>', $message['body'], '</td>
			</tr>
			<tr class="windowbg">
				<td align="right" class="middletext">';

			if ($context['can_send_pm'])
			{
				$quote_button = create_button('quote.gif', 'reply_quote', 'reply_quote', 'align="middle"');
				$reply_button = create_button('im_reply.gif', 'reply', 'reply', 'align="middle"');

				// You can only reply if they are not a guest...
				if (!$message['member']['is_guest'])
					echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=', $context['folder'] == 'sent' ? '' : $message['member']['id'], '">', $quote_button , '</a>', $context['menu_separator'], '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '">', $reply_button , '</a> ', $context['menu_separator'];
				// This is for "forwarding" - even if the member is gone.
				else
					echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">', $quote_button , '</a>', $context['menu_separator'];
			}

			echo '
				</td>
			</tr>
		</table>';
		}
		// Otherwise just a simple list!
		else
		{
			// !!! No context at all of the search?
			echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '" valign="top">
				<td>', $message['time'], '</td>
				<td>', $message['link'], '</td>
				<td>', $message['member']['link'], '</td>
			</tr>';
		}

		$alternate = !$alternate;
	}

	// Finish off the page...
	if (!empty($context['search_params']['show_complete']))
	{
		// No results?
		if (empty($context['personal_messages']))
			echo '
		<table width="100%" align="center" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 0 1px 1px 1px;">
			<tr class="windowbg">
				<td align="center">', $txt['pm_search_none_found'], '</td>
			</tr>
		</table>';
		else
			echo '
		<br />';

		echo '
		<table width="100%" align="center" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 0 1px 1px 1px;">
			<tr class="catbg" height="30">
				<td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
			</tr>
		</table>';
	}
	else
	{
		if (empty($context['personal_messages']))
			echo '
			<tr class="windowbg2">
				<td colspan="3" align="center">', $txt['pm_search_none_found'], '</td>
			</tr>';

		echo '
			<tr class="catbg">
				<td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
			</tr>
		</table>';
	}
}

function template_send()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// Show which messages were sent successfully and which failed.
	if (!empty($context['send_log']))
	{
		echo '
			<div class="cat_bar">
				<h3 class="catbg">', $txt['pm_send_report'], '</h3>
			</div>
			<div class="windowbg">
			<span class="topslice"><span></span></span>
				<div class="content">';
				if (!empty($context['send_log']['sent']))
					foreach ($context['send_log']['sent'] as $log_entry)
						echo '<span class="error">', $log_entry, '</span><br />';
				if (!empty($context['send_log']['failed']))
					foreach ($context['send_log']['failed'] as $log_entry)
						echo '<span class="error">', $log_entry, '</span><br />';
				echo '
				</div>
			<span class="botslice"><span></span></span>
			</div>
			<br />';
	}

	// Show the preview of the personal message.
	if (isset($context['preview_message']))
	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $context['preview_subject'], '</h3>
		</div>
		<div class="windowbg">
		<span class="topslice"><span></span></span>
			<div class="content">
				', $context['preview_message'], '
			</div>
		<span class="botslice"><span></span></span>
		</div>
		<br />';

	// Main message editing box.
	echo '
		<div class="cat_bar">
			<h3 class="catbg">
					<span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/im_newmsg.gif" alt="', $txt['new_message'], '" title="', $txt['new_message'], '" />&nbsp;', $txt['new_message'], '</span>
			</h3>
		</div>';

	echo '
	<form action="', $scripturl, '?action=pm;sa=send2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);smc_saveEntities(\'postmodify\', [\'subject\', \'message\']);">
		<div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">';

	// If there were errors for sending the PM, show them.
	if (!empty($context['post_error']['messages']))
	{
		echo '
				<div class="errorbox">
					<strong>', $txt['error_while_submitting'], '</strong>
					<ul>';

		foreach ($context['post_error']['messages'] as $error)
			echo '
						<li class="error">', $error, '</li>';

		echo '
					</ul>
				</div>';
	}

	echo '
				<dl id="post_header">';

	// To and bcc. Include a button to search for members.
	echo '
					<dt>
						<span', (isset($context['post_error']['no_to']) || isset($context['post_error']['bad_to']) ? ' class="error"' : ''), '>', $txt['pm_to'], ':</span>
					</dt>';

	// Autosuggest will be added by the JavaScript later on.
	echo '
					<dd>
						<input type="text" name="to" id="to_control" value="', $context['to_value'], '" tabindex="', $context['tabindex']++, '" size="40" style="width: 130px;" class="input_text" />';

	// A link to add BCC, only visible with JavaScript enabled.
	echo '
						<span class="smalltext" id="bcc_link_container" style="display: none;"></span>';

	// A div that'll contain the items found by the autosuggest.
	echo '
						<div id="to_item_list_container"></div>';

	echo '
					</dd>';

	// This BCC row will be hidden by default if JavaScript is enabled.
	echo '
					<dt id="bcc_div">
						<span', (isset($context['post_error']['no_to']) || isset($context['post_error']['bad_bcc']) ? ' class="error"' : ''), '>', $txt['pm_bcc'], ':</span>
					</dt>
					<dd id="bcc_div2">
						<input type="text" name="bcc" id="bcc_control" value="', $context['bcc_value'], '" tabindex="', $context['tabindex']++, '" size="40" style="width: 130px;" class="input_text" />
						<div id="bcc_item_list_container"></div>
					</dd>';

	// The subject of the PM.
	echo '
					<dt>
						<span', (isset($context['post_error']['no_subject']) ? ' class="error"' : ''), '>', $txt['subject'], ':</span>
					</dt>
					<dd>
						<input type="text" name="subject" value="', $context['subject'], '" tabindex="', $context['tabindex']++, '" size="40" maxlength="50" />
					</dd>
				</dl>';

	// Showing BBC?
	if ($context['show_bbc'])
	{
		echo '
				<div id="bbcBox_message"></div>';
	}

	// What about smileys?
	if (!empty($context['smileys']['postform']) || !empty($context['smileys']['popup']))
		echo '
				<div id="smileyBox_message"></div>';

	// Show BBC buttons, smileys and textbox.
	echo '
				', template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message');

	// Require an image to be typed to save spamming?
	if ($context['require_verification'])
	{
		echo '
				<div class="post_verification">
					<strong>', $txt['pm_visual_verification_label'], ':</strong>
					', template_control_verification($context['visual_verification_id'], 'all'), '
				</div>';
	}

	// Send, Preview, spellcheck buttons.
	echo '
				<p><label for="outbox"><input type="checkbox" name="outbox" id="outbox" value="1" tabindex="', $context['tabindex']++, '"', $context['copy_to_outbox'] ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_save_outbox'], '</label></p>
				<p id="shortcuts" class="smalltext">
					', $context['browser']['is_firefox'] ? $txt['shortcuts_firefox'] : $txt['shortcuts'], '
				</p>
				<p id="post_confirm_strip" class="righttext">
					', template_control_richedit_buttons($context['post_box_name']), '
				</p>
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
				<input type="hidden" name="replied_to" value="', !empty($context['quoted_message']['id']) ? $context['quoted_message']['id'] : 0, '" />
				<input type="hidden" name="pm_head" value="', !empty($context['quoted_message']['pm_head']) ? $context['quoted_message']['pm_head'] : 0, '" />
				<input type="hidden" name="f" value="', isset($context['folder']) ? $context['folder'] : '', '" />
				<input type="hidden" name="l" value="', isset($context['current_label_id']) ? $context['current_label_id'] : -1, '" />
			</div>
			<span class="lowerframe"><span></span></span>
		</div>
	</form>';

	// Show the message you're replying to.
	if ($context['reply'])
		echo '
	<br />
	<br />
	<div class="cat_bar">
		<h3 class="catbg">', $txt['subject'], ': ', $context['quoted_message']['subject'], '</h3>
	</div>
	<div class="title_bar">
		<h3 class="titlebg">
			<span class="floatleft">', $txt['from'], ': ', $context['quoted_message']['member']['name'], '</span>
			<span class="floatright">', $txt['on'], ': ', $context['quoted_message']['time'], '</span>
		</h3>
	</div>
	<div class="grid size-12">
		<span class="topslice"><span></span></span>
		<div class="content">
			', $context['quoted_message']['body'], '
		</div>
		<span class="botslice"><span></span></span>
	</div>';

	echo '
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/PersonalMessage.js?fin20"></script>
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
		<script type="text/javascript"><!-- // --><![CDATA[
			var oPersonalMessageSend = new smf_PersonalMessageSend({
				sSelf: \'oPersonalMessageSend\',
				sSessionId: \'', $context['session_id'], '\',
				sSessionVar: \'', $context['session_var'], '\',
				sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
				sToControlId: \'to_control\',
				aToRecipients: [';
	foreach ($context['recipients']['to'] as $i => $member)
		echo '
					{
						sItemId: ', JavaScriptEscape($member['id']), ',
						sItemName: ', JavaScriptEscape($member['name']), '
					}', $i == count($context['recipients']['to']) - 1 ? '' : ',';

	echo '
				],
				aBccRecipients: [';
	foreach ($context['recipients']['bcc'] as $i => $member)
		echo '
					{
						sItemId: ', JavaScriptEscape($member['id']), ',
						sItemName: ', JavaScriptEscape($member['name']), '
					}', $i == count($context['recipients']['bcc']) - 1 ? '' : ',';

	echo '
				],
				sBccControlId: \'bcc_control\',
				sBccDivId: \'bcc_div\',
				sBccDivId2: \'bcc_div2\',
				sBccLinkId: \'bcc_link\',
				sBccLinkContainerId: \'bcc_link_container\',
				bBccShowByDefault: ', empty($context['recipients']['bcc']) && empty($context['bcc_value']) ? 'false' : 'true', ',
				sShowBccLinkTemplate: ', JavaScriptEscape('
					<a href="#" id="bcc_link">' . $txt['make_bcc'] . '</a> <a href="' . $scripturl . '?action=helpadmin;help=pm_bcc" onclick="return reqWin(this.href);">(?)</a>'
				), '
			});
		';

	echo '
		// ]]></script>';
}

// This template asks the user whether they wish to empty out their folder/messages.
function template_ask_delete()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">', ($context['delete_all'] ? $txt['delete_message'] : $txt['delete_all']), '</h3>
		</div>
		<div class="windowbg">
		<span class="topslice"><span></span></span>
			<div class="content">
				<p>', $txt['delete_all_confirm'], '</p><br />
				<strong><a href="', $scripturl, '?action=pm;sa=removeall2;f=', $context['folder'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';', $context['session_var'], '=', $context['session_id'], '">', $txt['yes'], '</a> - <a href="javascript:history.go(-1);">', $txt['no'], '</a></strong>
			</div>
		<span class="botslice"><span></span></span>
		</div>';
}

// This template asks the user what messages they want to prune.
function template_prune()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=pm;sa=prune" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['pm_prune_warning'], '\');">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_prune'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<p>', $txt['pm_prune_desc1'], ' <input type="text" name="age" size="3" value="14" class="input_text" /> ', $txt['pm_prune_desc2'], '</p>
				<div class="righttext">
					<input type="submit" value="', $txt['delete'], '" class="button_submit" />
				</div>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';
}

// Here we allow the user to setup labels, remove labels and change rules for labels (i.e, do quite a bit)
function template_labels()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=pm;sa=manlabels" method="post" accept-charset="', $context['character_set'], '">
		<div class="title_bar">
			<h3 class="titlebg">', $txt['pm_manage_labels'], '</h3>
		</div>
		<div class="description">
			', $txt['pm_labels_desc'], '
		</div>
		<table width="100%" class="table_grid">
		<thead>
			<tr class="catbg">
				<th class="smalltext" colspan="2">
					<div class="floatright centertext" style="width: 4%;"><input type="checkbox" class="input_check" onclick="invertAll(this, this.form);" /></div>
					', $txt['pm_label_name'], '
				</th>
			</tr>
		</thead>
		<tbody>';
	if (count($context['labels']) < 2)
		echo '
			<tr class="windowbg2">
				<td colspan="2" align="center">', $txt['pm_labels_no_exist'], '</td>
			</tr>';
	else
	{
		$alternate = true;
		foreach ($context['labels'] as $label)
		{
			if ($label['id'] == -1)
				continue;

				echo '
			<tr class="', $alternate ? 'windowbg2' : 'windowbg', '">
				<td>
					<input type="text" name="label_name[', $label['id'], ']" value="', $label['name'], '" size="30" maxlength="30" class="input_text" />
				</td>
				<td width="4%" align="center"><input type="checkbox" class="input_check" name="delete_label[', $label['id'], ']" /></td>
			</tr>';

			$alternate = !$alternate;
		}
	}
	echo '
		</tbody>
		</table>';

	if (!count($context['labels']) < 2)
		echo '
		<div class="padding righttext">
			<input type="submit" name="save" value="', $txt['save'], '" class="button_submit" />
			<input type="submit" name="delete" value="', $txt['quickmod_delete_selected'], '" onclick="return confirm(\'', $txt['pm_labels_delete'], '\');" class="button_submit" />
		</div>';

	echo '
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>
	<form action="', $scripturl, '?action=pm;sa=manlabels" method="post" accept-charset="', $context['character_set'], '" style="margin-top: 1ex;">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_label_add_new'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">
					<dt>
						<strong><label for="add_label">', $txt['pm_label_name'], '</label>:</strong>
					</dt>
					<dd>
						<input type="text" id="add_label" name="label" value="" size="30" maxlength="30" class="input_text" />
					</dd>
				</dl>
				<div class="righttext">
					<input type="submit" name="add" value="', $txt['pm_label_add_new'], '" class="button_submit" />
				</div>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';
}

// Template for reporting a personal message.
function template_report_message()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=pm;sa=report;l=', $context['current_label_id'], '" method="post" accept-charset="', $context['character_set'], '">
		<input type="hidden" name="pmsg" value="', $context['pm_id'], '" />
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_report_title'], '</h3>
		</div>
		<div class="description">
			', $txt['pm_report_desc'], '
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">';

	// If there is more than one admin on the forum, allow the user to choose the one they want to direct to.
	// !!! Why?
	if ($context['admin_count'] > 1)
	{
		echo '
					<dt>
						<strong>', $txt['pm_report_admins'], ':</strong>
					</dt>
					<dd>
						<select name="ID_ADMIN">
							<option value="0">', $txt['pm_report_all_admins'], '</option>';
		foreach ($context['admins'] as $id => $name)
			echo '
							<option value="', $id, '">', $name, '</option>';
		echo '
						</select>
					</dd>';
	}

	echo '
					<dt>
						<strong>', $txt['pm_report_reason'], ':</strong>
					</dt>
					<dd>
						<textarea name="reason" rows="4" cols="70" style="' . ($context['browser']['is_ie8'] ? 'width: 635px; max-width: 80%; min-width: 80%' : 'width: 80%') . ';"></textarea>
					</dd>
				</dl>
				<input type="submit" name="report" value="', $txt['pm_report_message'], '" class="button_submit" />
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';
}

// Little template just to say "Yep, it's been submitted"
function template_report_message_complete()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_report_title'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<p>', $txt['pm_report_done'], '</p>
				<a href="', $scripturl, '?action=pm;l=', $context['current_label_id'], '">', $txt['pm_report_return'], '</a>
			</div>
			<span class="botslice"><span></span></span>
		</div>';
}

// Manage rules.
function template_rules()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=pm;sa=manrules" method="post" accept-charset="', $context['character_set'], '" name="manRules">
		<div class="title_bar">
			<h3 class="titlebg">', $txt['pm_manage_rules'], '</h3>
		</div>
		<div class="description">
			', $txt['pm_manage_rules_desc'], '
		</div>
		<table width="100%" class="table_grid">
		<thead>
			<tr class="catbg">
				<th class="smalltext">
					', $txt['pm_rule_title'], '
				</th>
				<th width="4%" align="center">';

	if (!empty($context['rules']))
		echo '
					<input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" />';

	echo '
				</th>
			</tr>
		</thead>
		<tbody>';

	if (empty($context['rules']))
		echo '
			<tr class="windowbg2">
				<td colspan="2" align="center">
					', $txt['pm_rules_none'], '
				</td>
			</tr>';

	$alternate = false;
	foreach ($context['rules'] as $rule)
	{
		echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
				<td>
					<a href="', $scripturl, '?action=pm;sa=manrules;add;rid=', $rule['id'], '">', $rule['name'], '</a>
				</td>
				<td width="4%" align="center">
					<input type="checkbox" name="delrule[', $rule['id'], ']" class="input_check" />
				</td>
			</tr>';
		$alternate = !$alternate;
	}

	echo '
		</tbody>
		</table>
		<div class="righttext">
			[<a href="', $scripturl, '?action=pm;sa=manrules;add;rid=0">', $txt['pm_add_rule'], '</a>]';

	if (!empty($context['rules']))
		echo '
			[<a href="', $scripturl, '?action=pm;sa=manrules;apply;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['pm_js_apply_rules_confirm'], '\');">', $txt['pm_apply_rules'], '</a>]';

	if (!empty($context['rules']))
		echo '
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			<input type="submit" name="delselected" value="', $txt['pm_delete_selected_rule'], '" onclick="return confirm(\'', $txt['pm_js_delete_rule_confirm'], '\');" class="button_submit" />';

	echo '
			</div>
	</form>';

}

// Template for adding/editing a rule.
function template_add_rule()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
			var criteriaNum = 0;
			var actionNum = 0;
			var groups = new Array()
			var labels = new Array()';

	foreach ($context['groups'] as $id => $title)
		echo '
			groups[', $id, '] = "', addslashes($title), '";';

	foreach ($context['labels'] as $label)
		if ($label['id'] != -1)
			echo '
			labels[', ($label['id'] + 1), '] = "', addslashes($label['name']), '";';

	echo '
			function addCriteriaOption()
			{
				if (criteriaNum == 0)
				{
					for (var i = 0; i < document.forms.addrule.elements.length; i++)
						if (document.forms.addrule.elements[i].id.substr(0, 8) == "ruletype")
							criteriaNum++;
				}
				criteriaNum++

				setOuterHTML(document.getElementById("criteriaAddHere"), \'<br /><select name="ruletype[\' + criteriaNum + \']" id="ruletype\' + criteriaNum + \'" onchange="updateRuleDef(\' + criteriaNum + \'); rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_criteria_pick']), ':<\' + \'/option><option value="mid">', addslashes($txt['pm_rule_mid']), '<\' + \'/option><option value="gid">', addslashes($txt['pm_rule_gid']), '<\' + \'/option><option value="sub">', addslashes($txt['pm_rule_sub']), '<\' + \'/option><option value="msg">', addslashes($txt['pm_rule_msg']), '<\' + \'/option><option value="bud">', addslashes($txt['pm_rule_bud']), '<\' + \'/option><\' + \'/select>&nbsp;<span id="defdiv\' + criteriaNum + \'" style="display: none;"><input type="text" name="ruledef[\' + criteriaNum + \']" id="ruledef\' + criteriaNum + \'" onkeyup="rebuildRuleDesc();" value="" class="input_text" /><\' + \'/span><span id="defseldiv\' + criteriaNum + \'" style="display: none;"><select name="ruledefgroup[\' + criteriaNum + \']" id="ruledefgroup\' + criteriaNum + \'" onchange="rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_group']), '<\' + \'/option>';

	foreach ($context['groups'] as $id => $group)
		echo '<option value="', $id, '">', strtr($group, array("'" => "\'")), '<\' + \'/option>';

	echo '<\' + \'/select><\' + \'/span><span id="criteriaAddHere"><\' + \'/span>\');
			}

			function addActionOption()
			{
				if (actionNum == 0)
				{
					for (var i = 0; i < document.forms.addrule.elements.length; i++)
						if (document.forms.addrule.elements[i].id.substr(0, 7) == "acttype")
							actionNum++;
				}
				actionNum++

				setOuterHTML(document.getElementById("actionAddHere"), \'<br /><select name="acttype[\' + actionNum + \']" id="acttype\' + actionNum + \'" onchange="updateActionDef(\' + actionNum + \'); rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_action']), ':<\' + \'/option><option value="lab">', addslashes($txt['pm_rule_label']), '<\' + \'/option><option value="del">', addslashes($txt['pm_rule_delete']), '<\' + \'/option><\' + \'/select>&nbsp;<span id="labdiv\' + actionNum + \'" style="display: none;"><select name="labdef[\' + actionNum + \']" id="labdef\' + actionNum + \'" onchange="rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_label']), '<\' + \'/option>';

	foreach ($context['labels'] as $label)
		if ($label['id'] != -1)
			echo '<option value="', ($label['id'] + 1), '">', addslashes($label['name']), '<\' + \'/option>';

	echo '<\' + \'/select><\' + \'/span><span id="actionAddHere"><\' + \'/span>\');
			}

			function updateRuleDef(optNum)
			{
				if (document.getElementById("ruletype" + optNum).value == "gid")
				{
					document.getElementById("defdiv" + optNum).style.display = "none";
					document.getElementById("defseldiv" + optNum).style.display = "";
				}
				else if (document.getElementById("ruletype" + optNum).value == "bud" || document.getElementById("ruletype" + optNum).value == "")
				{
					document.getElementById("defdiv" + optNum).style.display = "none";
					document.getElementById("defseldiv" + optNum).style.display = "none";
				}
				else
				{
					document.getElementById("defdiv" + optNum).style.display = "";
					document.getElementById("defseldiv" + optNum).style.display = "none";
				}
			}

			function updateActionDef(optNum)
			{
				if (document.getElementById("acttype" + optNum).value == "lab")
				{
					document.getElementById("labdiv" + optNum).style.display = "";
				}
				else
				{
					document.getElementById("labdiv" + optNum).style.display = "none";
				}
			}

			// Rebuild the rule description!
			function rebuildRuleDesc()
			{
				// Start with nothing.
				text = "";
				joinText = "";
				actionText = "";
				hadBuddy = false;
				foundCriteria = false;
				foundAction = false;

				for (var i = 0; i < document.forms.addrule.elements.length; i++)
				{
					if (document.forms.addrule.elements[i].id.substr(0, 8) == "ruletype")
					{
						if (foundCriteria)
							joinText = document.getElementById("logic").value == \'and\' ? ', JavaScriptEscape(' ' . $txt['pm_readable_and'] . ' '), ' : ', JavaScriptEscape(' ' . $txt['pm_readable_or'] . ' '), ';
						else
							joinText = \'\';
						foundCriteria = true;

						curNum = document.forms.addrule.elements[i].id.match(/\d+/);
						curVal = document.forms.addrule.elements[i].value;
						if (curVal == "gid")
							curDef = document.getElementById("ruledefgroup" + curNum).value.php_htmlspecialchars();
						else if (curVal != "bud")
							curDef = document.getElementById("ruledef" + curNum).value.php_htmlspecialchars();
						else
							curDef = "";

						// What type of test is this?
						if (curVal == "mid" && curDef)
							text += joinText + ', JavaScriptEscape($txt['pm_readable_member']), '.replace("{MEMBER}", curDef);
						else if (curVal == "gid" && curDef && groups[curDef])
							text += joinText + ', JavaScriptEscape($txt['pm_readable_group']), '.replace("{GROUP}", groups[curDef]);
						else if (curVal == "sub" && curDef)
							text += joinText + ', JavaScriptEscape($txt['pm_readable_subject']), '.replace("{SUBJECT}", curDef);
						else if (curVal == "msg" && curDef)
							text += joinText + ', JavaScriptEscape($txt['pm_readable_body']), '.replace("{BODY}", curDef);
						else if (curVal == "bud" && !hadBuddy)
						{
							text += joinText + ', JavaScriptEscape($txt['pm_readable_buddy']), ';
							hadBuddy = true;
						}
					}
					if (document.forms.addrule.elements[i].id.substr(0, 7) == "acttype")
					{
						if (foundAction)
							joinText = ', JavaScriptEscape(' ' . $txt['pm_readable_and'] . ' '), ';
						else
							joinText = "";
						foundAction = true;

						curNum = document.forms.addrule.elements[i].id.match(/\d+/);
						curVal = document.forms.addrule.elements[i].value;
						if (curVal == "lab")
							curDef = document.getElementById("labdef" + curNum).value.php_htmlspecialchars();
						else
							curDef = "";

						// Now pick the actions.
						if (curVal == "lab" && curDef && labels[curDef])
							actionText += joinText + ', JavaScriptEscape($txt['pm_readable_label']), '.replace("{LABEL}", labels[curDef]);
						else if (curVal == "del")
							actionText += joinText + ', JavaScriptEscape($txt['pm_readable_delete']), ';
					}
				}

				// If still nothing make it default!
				if (text == "" || !foundCriteria)
					text = "', $txt['pm_rule_not_defined'], '";
				else
				{
					if (actionText != "")
						text += ', JavaScriptEscape(' ' . $txt['pm_readable_then'] . ' '), ' + actionText;
					text = ', JavaScriptEscape($txt['pm_readable_start']), ' + text + ', JavaScriptEscape($txt['pm_readable_end']), ';
				}

				// Set the actual HTML!
				setInnerHTML(document.getElementById("ruletext"), text);
			}
	// ]]></script>';

	echo '
	<form action="', $scripturl, '?action=pm;sa=manrules;save;rid=', $context['rid'], '" method="post" accept-charset="', $context['character_set'], '" name="addrule" id="addrule">
		<div class="cat_bar">
			<h3 class="catbg">', $context['rid'] == 0 ? $txt['pm_add_rule'] : $txt['pm_edit_rule'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">
					<dt>
						<strong>', $txt['pm_rule_name'], ':</strong><br />
						<span class="smalltext">', $txt['pm_rule_name_desc'], '</span>
					</dt>
					<dd>
						<input type="text" name="rule_name" value="', empty($context['rule']['name']) ? $txt['pm_rule_name_default'] : $context['rule']['name'], '" class="input_text" style="width: 100%" />
					</dd>
				</dl>
				<fieldset>
					<legend>', $txt['pm_rule_criteria'], '</legend>';

	// Add a dummy criteria to allow expansion for none js users.
	$context['rule']['criteria'][] = array('t' => '', 'v' => '');

	// For each criteria print it out.
	$isFirst = true;
	foreach ($context['rule']['criteria'] as $k => $criteria)
	{
		if (!$isFirst && $criteria['t'] == '')
			echo '<div id="removeonjs1">';
		else
			echo '<br />';

		echo '
					<select name="ruletype[', $k, ']" id="ruletype', $k, '" onchange="updateRuleDef(', $k, '); rebuildRuleDesc();">
						<option value="">', $txt['pm_rule_criteria_pick'], ':</option>
						<option value="mid" ', $criteria['t'] == 'mid' ? 'selected="selected"' : '', '>', $txt['pm_rule_mid'], '</option>
						<option value="gid" ', $criteria['t'] == 'gid' ? 'selected="selected"' : '', '>', $txt['pm_rule_gid'], '</option>
						<option value="sub" ', $criteria['t'] == 'sub' ? 'selected="selected"' : '', '>', $txt['pm_rule_sub'], '</option>
						<option value="msg" ', $criteria['t'] == 'msg' ? 'selected="selected"' : '', '>', $txt['pm_rule_msg'], '</option>
						<option value="bud" ', $criteria['t'] == 'bud' ? 'selected="selected"' : '', '>', $txt['pm_rule_bud'], '</option>
					</select>
					<span id="defdiv', $k, '" ', !in_array($criteria['t'], array('gid', 'bud')) ? '' : 'style="display: none;"', '>
						<input type="text" name="ruledef[', $k, ']" id="ruledef', $k, '" onkeyup="rebuildRuleDesc();" value="', in_array($criteria['t'], array('mid', 'sub', 'msg')) ? $criteria['v'] : '', '" class="input_text" />
					</span>
					<span id="defseldiv', $k, '" ', $criteria['t'] == 'gid' ? '' : 'style="display: none;"', '>
						<select name="ruledefgroup[', $k, ']" id="ruledefgroup', $k, '" onchange="rebuildRuleDesc();">
							<option value="">', $txt['pm_rule_sel_group'], '</option>';

		foreach ($context['groups'] as $id => $group)
			echo '
							<option value="', $id, '" ', $criteria['t'] == 'gid' && $criteria['v'] == $id ? 'selected="selected"' : '', '>', $group, '</option>';
		echo '
						</select>
					</span>';

		// If this is the dummy we add a means to hide for non js users.
		if ($isFirst)
			$isFirst = false;
		elseif ($criteria['t'] == '')
			echo '</div>';
	}

	echo '
					<span id="criteriaAddHere"></span><br />
					<a href="#" onclick="addCriteriaOption(); return false;" id="addonjs1" style="display: none;">(', $txt['pm_rule_criteria_add'], ')</a>
					<br /><br />
					', $txt['pm_rule_logic'], ':
					<select name="rule_logic" id="logic" onchange="rebuildRuleDesc();">
						<option value="and" ', $context['rule']['logic'] == 'and' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_and'], '</option>
						<option value="or" ', $context['rule']['logic'] == 'or' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_or'], '</option>
					</select>
				</fieldset>
				<fieldset>
					<legend>', $txt['pm_rule_actions'], '</legend>';

	// As with criteria - add a dummy action for "expansion".
	$context['rule']['actions'][] = array('t' => '', 'v' => '');

	// Print each action.
	$isFirst = true;
	foreach ($context['rule']['actions'] as $k => $action)
	{
		if (!$isFirst && $action['t'] == '')
			echo '<div id="removeonjs2">';
		else
			echo '<br />';

		echo '
					<select name="acttype[', $k, ']" id="acttype', $k, '" onchange="updateActionDef(', $k, '); rebuildRuleDesc();">
						<option value="">', $txt['pm_rule_sel_action'] , ':</option>
						<option value="lab" ', $action['t'] == 'lab' ? 'selected="selected"' : '', '>', $txt['pm_rule_label'] , '</option>
						<option value="del" ', $action['t'] == 'del' ? 'selected="selected"' : '', '>', $txt['pm_rule_delete'] , '</option>
					</select>
					<span id="labdiv', $k, '">
						<select name="labdef[', $k, ']" id="labdef', $k, '" onchange="rebuildRuleDesc();">
							<option value="">', $txt['pm_rule_sel_label'], '</option>';
		foreach ($context['labels'] as $label)
			if ($label['id'] != -1)
				echo '
							<option value="', ($label['id'] + 1), '" ', $action['t'] == 'lab' && $action['v'] == $label['id'] ? 'selected="selected"' : '', '>', $label['name'], '</option>';

		echo '
						</select>
					</span>';

		if ($isFirst)
			$isFirst = false;
		elseif ($action['t'] == '')
			echo '
				</div>';
	}

	echo '
					<span id="actionAddHere"></span><br />
					<a href="#" onclick="addActionOption(); return false;" id="addonjs2" style="display: none;">(', $txt['pm_rule_add_action'], ')</a>
				</fieldset>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_rule_description'], '</h3>
		</div>
		<div class="information">
			<div id="ruletext" class="smalltext">', $txt['pm_rule_js_disabled'], '</div>
		</div>
		<div class="righttext">
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			<input type="submit" name="save" value="', $txt['pm_rule_save'], '" class="button_submit" />
		</div>
	</form>';

	// Now setup all the bits!
		echo '
	<script type="text/javascript"><!-- // --><![CDATA[';

	foreach ($context['rule']['criteria'] as $k => $c)
		echo '
			updateRuleDef(', $k, ');';

	foreach ($context['rule']['actions'] as $k => $c)
		echo '
			updateActionDef(', $k, ');';

	echo '
			rebuildRuleDesc();';

	// If this isn't a new rule and we have JS enabled remove the JS compatibility stuff.
	if ($context['rid'])
		echo '
			document.getElementById("removeonjs1").style.display = "none";
			document.getElementById("removeonjs2").style.display = "none";';

	echo '
			document.getElementById("addonjs1").style.display = "";
			document.getElementById("addonjs2").style.display = "";';

	echo '
		// ]]></script>';
}

?>