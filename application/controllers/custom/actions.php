<?php
if ( ! defined('CUSTOM_ACTION')) exit('This controller should be called only from the website controller');

/**
 * Website Custom Actions Controller
 *
 * Controller per le azioni custom del front-end del sito internet
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Actions
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * Azione dimostrativa
	 */
	function helloworld()
	{
		//Estraggo il menu di default (dovrebbe comunque esserci gia)
		$this->CI->view->set('tree', $this->CI->tree->get_default());

		//Renderizzo il template default
		$this->CI->view->render_template('default');
	}
}