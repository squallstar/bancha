<?php
/**
 * Contact form Module View
 *
 * See the Contact form documentation for an example of usage
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$this->load->helper('form');

echo form_open(NULL, array('id' => 'contact_form'));
echo form_hidden('_contactform', 'true');

echo form_label(_('First name'), 'firstname').br(1);
echo form_input('firstname').br(2)."\n";

echo form_label(_('Last name'), 'lastname').br(1);
echo form_input('lastname').br(2)."\n";

echo form_label(_('Email address'), 'email').br(1);
echo form_input('email').br(2)."\n";

echo form_label(_('Message'), 'message').br(1);
echo form_input('message').br(2)."\n";

echo form_submit('submit', 'Submit');

echo form_close();