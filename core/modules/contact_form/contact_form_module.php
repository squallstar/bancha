<?php
/**
 * Contact form Module
 *
 * Usage:
 * $form = $this->load->module('contact_form');
 * echo $form->render();
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Contact_form_Module extends Bancha_Module
{
	/**
	 * @var string Form action
	 */
	protected $action	= 'email';

	/**
	 * @var string From > Email
	 */
	protected $from = 'support@getbancha.com';

	/**
	 * @var string From name > Email
	 */
	protected $from_name = 'Bancha Module';

	/**
	 * @var string To > Email
	 */
	protected $to	= '';

	/**
	 * @var string Email subject
	 */
	protected $subject = 'Contact form request';

	/**
	 * Sends (or renders) the form
	 */
	public function render()
	{
		$B =& get_instance();

		$act = $B->input->post('_contactform');
		if ($act)
		{
			$data = $B->input->post();

			switch ($this->action)
			{
				case 'email':
					$B->load->library('email', array('mailtype' => 'html'));
					$B->load->library('parser');

					$B->email->from($this->from, 'Bancha contact form');
					$B->email->to($this->to);
					$B->email->subject($this->subject);

					$msg = parent::render('template_email');

					$parsed_msg = $B->parser->parse_string($msg, array(
						'firstname'	=> $B->input->post('firstname'),
						'lastname'	=> $B->input->post('lastname'),
						'email'		=> $B->input->post('email'),
						'message'	=> $B->input->post('message')
					), TRUE);

					$B->email->message($parsed_msg);	

					$done = $B->email->send();
					break;
				default:
					show_error('Contact form Module action not set.');
			}
			if ($done)
			{
				return parent::render('view_success');
			}
		}
		return parent::render();
	}

}