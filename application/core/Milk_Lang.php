<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Milk_Lang extends CI_Lang {

	private $_CI;

	public $current_language = '';
	public $languages = FALSE;
    public $gettext_language;
    public $gettext_path;

    function __construct() {
        parent::__construct();
        $this->gettext_path = APPPATH.'language/locale';
    }

	/**
     * Loads the languages from the Milk config file
     */
    private function _load_languages() {
        $this->languages = $this->_CI->config->item('languages');
        
        $select = array();
        foreach ($this->languages as $lang => $val)
        {
        	$select[$lang] = $val['description'];
        }
        $this->_CI->config->set_item('languages_select', $select);
    }

    /**
     * Sets the website language to a new one (es. it, en)
     * @param string $lang
     * @param bool $load_gettext
     */
    function set_lang($lang, $load_gettext = true) {
		if (!$this->languages) {
			$this->_load_languages();
		}

        //Checks if a language exists
		if (in_array($lang, array_keys($this->languages))) {
		
			$this->gettext_language = $this->languages[$lang]['locale'];
			$this->current_language = $lang;
		} else {
			//Loads the first defined language
			$this->set_default($load_gettext);
		}
		if ($load_gettext) {
			$this->load_gettext();
		}

		//Update CI config
		$this->_CI->config->set_item('language', $this->languages[$this->current_language]['name']);
    }

    /**
     * Sets the website language to the default one
     * @param $load_gettext
     */
    function set_default($load_gettext = true) {
 	    if (!$this->languages) {
			$this->_load_languages();
		}
    	$keys = array_keys($this->languages);
    	$this->current_language = $keys[0];

		$this->gettext_language = $this->languages[$this->current_language]['locale'];

    	if ($load_gettext) {
			$this->load_gettext();
		}
    }

    function check($folder='website') {
    	$this->gettext_path = $this->gettext_path . '/' . trim($folder, '/');
    	$this->_CI = &get_instance();
    	if (!$this->languages) {
			$this->_load_languages();
		}

		if (!isset($this->_CI->session))
		{
			$current_lang = FALSE;
		} else {
			$current_lang = $this->_CI->session->userdata('current_language');
		}
		
    	
    	if (!$current_lang) {
    		//Browser language
			$current_lang = strtolower(substr($this->_CI->input->server('HTTP_ACCEPT_LANGUAGE'),0,2));
			$this->set_lang($current_lang);
			$this->set_cookie();
    	} else {
    		$this->set_lang($current_lang);
    	}
    }

    /**
     * Store the current language in session
     */
    function set_cookie() {
    	if (isset($this->_CI->session))
		{
			$this->_CI->session->set_userdata('current_language', $this->current_language);
		}
    }

    /**
     * Loads the .mo files of the current language
     */
    function load_gettext() {

    	putenv('LC_ALL='.$this->gettext_language);
		setlocale(LC_ALL, $this->gettext_language);

		// Specify location of translation tables
		bindtextdomain(FRNAME, $this->gettext_path);
		bind_textdomain_codeset(FRNAME, 'UTF-8');

		// Choose domain
		textdomain(FRNAME);

    }

    function _trans( $original, $aParams = false ) {
        if ( isset($aParams['plural']) && isset($aParams['count']) ) {
            $sTranslate = ngettext($original, $aParams['plural'], $aParams['count']);
            $sTranslate = $this->replace_dynamically($sTranslate, $aParams);
        }
        else{
            $sTranslate = gettext( $original );
            if (is_array($aParams) && count($aParams) ) $sTranslate = $this->replace_dynamically($sTranslate, $aParams);
        }
        return $sTranslate;
    }

    function replace_dynamically($sString) {
        $aTrad = array();
        for ( $i=1, $iMax = func_num_args(); $i<$iMax; $i++) {
            $arg = func_get_arg($i);
            if (is_array($arg)) {
                foreach ($arg as $key => $sValue) {
                    $aTrad['%'.$key] = $sValue;
                }
            }
            else {
                $aTrad['%'.$key] = $arg;
            }
        }
        return strtr($sString, $aTrad);
    }


}

?>