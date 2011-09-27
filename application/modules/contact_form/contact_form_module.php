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
 * @copyright	Copyright (c) 2011, Squallstar
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
		$CI = & get_instance();

		$act = $CI->input->post('_contactform');
		if ($act)
		{
			$data = $CI->input->post();

			switch ($this->action)
			{
				case 'email':
					$CI->load->library('email', array('mailtype' => 'html'));
					$CI->load->library('parser');

					$CI->email->from($this->from, 'Bancha contact form');
					$CI->email->to($this->to);
					$CI->email->subject($this->subject);

					$msg = parent::render('template_email');

					$parsed_msg = $CI->parser->parse_string($msg, array(
						'firstname'	=> $CI->input->post('firstname'),
						'lastname'	=> $CI->input->post('lastname'),
						'email'		=> $CI->input->post('email'),
						'message'	=> $CI->input->post('message')
					), TRUE);

					$CI->email->message($parsed_msg);	

					$done = $CI->email->send();
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