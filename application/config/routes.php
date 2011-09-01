<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| MILK INTERNAL ROUTING
| -------------------------------------------------------------------------
*/

//Routing interni per l'amministrazione (Milk)
$route['^admin$'] = "admin/auth/login";
$route['^admin/pages$'] = "admin/contents";
$route['^admin/pages/(.+)$'] = "admin/contents/$1";

//Router da utilizzare come default
$route['404_override'] = 'website/router';

/*
| -------------------------------------------------------------------------
| FRONT END ROUTING
| -------------------------------------------------------------------------
*/

//Homepage del sito
$route['default_controller'] = "website/home";

//Route per switch theme
$route['^go-([a-z]+)$'] = "website/change_theme/$1";

//Route per cambio lingua
$route['^change-language/([a-z]+)$'] = "website/change_language/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */