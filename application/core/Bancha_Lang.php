<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bancha_Lang extends CI_Lang {

	private $_CI;

	public $current_language = '';
	public $languages = FALSE;
    public $gettext_language;
    public $gettext_path;

    function __construct()
    {
        parent::__construct();
        $this->gettext_path = APPPATH.'language/locale';
    }

	/**
     * Loads the languages from the Bancha config file
     */
    private function _load_languages()
    {
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
    function set_lang($lang, $load_gettext = true)
    {
		if (!$this->languages)
		{
			$this->_load_languages();
		}

        //Checks if a language exists
		if (isset($this->languages[$lang]))
		{
			$this->gettext_language = $this->languages[$lang]['locale'];
			$this->current_language = $lang;
			if ($load_gettext)
			{
				$this->load_gettext();
			}
		} else {
			//Loads the first defined language
			$this->set_default($load_gettext);
		}

		//Update CI config
		$this->_CI->config->set_item('language', $this->languages[$this->current_language]['name']);
    }

    /**
     * Sets the website language to the default one
     * @param $load_gettext
     */
    function set_default($load_gettext = true)
    {
 	    if (!$this->languages)
 	    {
			$this->_load_languages();
		}
    	$keys = array_keys($this->languages);
    	$this->current_language = $keys[0];

		$this->gettext_language = $this->languages[$this->current_language]['locale'];

    	if ($load_gettext)
    	{
			$this->load_gettext();
		}
    }

    /**
     * Checks the current language
     * @param string $folder The locale folder to check in
     */
    function check($folder='website')
    {
    	$this->gettext_path = $this->gettext_path . '/' . trim($folder, '/');
    	$this->_CI = & get_instance();
    	if (!$this->languages)
    	{
			$this->_load_languages();
		}

		if (!isset($this->_CI->session))
		{
			$current_lang = FALSE;
		} else {
			$current_lang = $this->_CI->session->userdata('current_language');
		}
		
    	if (!$current_lang)
    	{
    		$browser_languages = $this->get_browser_languages();
			foreach ($browser_languages as $current_lang)
			{
				if (isset($this->languages[$current_lang]))
				{
					break;
				}
			}
		}
		$this->set_lang($current_lang);
    	$this->set_cookie();
    }

	/**
	 * Returns browser languages setted into HTTP header Accept-Language
	 * @return array
	 */
	function get_browser_languages()
	{
		//Accept-Language:	it-it,it;q=0.8,en-us;q=0.5,en;q=0.3
		$accepted_languages = $this->_CI->input->server('HTTP_ACCEPT_LANGUAGE');

        if (strpos($accepted_languages, ',') !== FALSE)
        {
            $langs = array();
            foreach (explode(',', $accepted_languages) as $k => $pref)
            {
                $tmp = explode(';q=', $pref, 2);
                $pref = $tmp[0];
                $q = isset($tmp[1]) ? $tmp[1] : '';
                if ($q==='' || $q === NULL)
                {
                    $q = 1;
                }
                
                $l_tmp = explode('-', $pref, 2);
                $lang_code = $l_tmp[0];
                $nation = isset($l_tmp[1]) ? $l_tmp[1] : '';
                $lang_code = strtolower($lang_code);
                if ($lang_code)
                {
                    $nation = strtoupper($nation);
                    if ($nation == '' || $nation == '*')
                    {
                        $langs[$lang_code] = $q;    
                    } else {
                        $langs[$lang_code."_".$nation] = $q;
                        if (!isset($langs[$lang_code]))
                        {
                            $langs[$lang_code] = $q;
                        }
                    }
                }
            }
            arsort($langs);
            return array_keys($langs);
        } else {
            return array(substr($accepted_languages, 0, 2));
        }
		
	}


    /**
     * Store the current language in session
     */
    function set_cookie()
    {
    	if (isset($this->_CI->session))
		{
			$this->_CI->session->set_userdata('current_language', $this->current_language);
		}
    }

    /**
     * Loads the .mo files of the current language
     */
    function load_gettext()
    {
    	putenv('LC_ALL='.$this->gettext_language);
		setlocale(LC_ALL, $this->gettext_language);

		// Specify location of translation tables
		bindtextdomain(FRNAME, $this->gettext_path);
		bind_textdomain_codeset(FRNAME, 'UTF-8');

		// Choose domain
		textdomain(FRNAME);
    }

    /**
     * Translates a string binding some params
     * example: I am %n (where %n will be the param "n")
     * @param string $original The key of the translation
     * @param array $bind_params The params to bind
     */
    function _trans($original, $bind_params = false)
    {
        if (isset($bind_params['plural']) && isset($bind_params['count']))
        {
            $sTranslate = ngettext($original, $bind_params['plural'], $bind_params['count']);
            $sTranslate = $this->replace_dynamically($sTranslate, $bind_params);
        } else {
            $sTranslate = gettext($original);
            if (is_array($bind_params) && count($bind_params))
            {
	            $sTranslate = $this->replace_dynamically($sTranslate, $bind_params);
	        }
        }
        return $sTranslate;
    }

    /**
     * Replace a string with some arguments
     * This function accepts infinite parameters
     * @param string $s_string The string to translate
     */
    function replace_dynamically($s_string)
    {
        $a_trad = array();
        for ( $i=1, $i_max = func_num_args(); $i<$i_max; $i++)
        {
            $arg = func_get_arg($i);
            if (is_array($arg))
            {
                foreach ($arg as $key => $sValue)
                {
                    $a_trad['%'.$key] = $sValue;
                }
            } else {
                $a_trad['%'.$key] = $arg;
            }
        }
        return strtr($s_string, $a_trad);
    }
}

?>