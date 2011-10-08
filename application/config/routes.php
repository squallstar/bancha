<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| BANCHA INTERNAL ROUTING
| -------------------------------------------------------------------------
*/

//Internal routing systems for administration
$route['^admin$'] = "admin/auth/login";
$route['^admin/pages$'] = "admin/contents";
$route['^admin/pages/(.+)$'] = "admin/contents/$1";

//The default routing method used for the website
$route['404_override'] = 'website/router';

/*
| -------------------------------------------------------------------------
| FRONT END ROUTING
| -------------------------------------------------------------------------
*/

//Website homepage
$route['default_controller'] = "website/home";

//This route let you switch between the website themes
$route['^go-([a-zA-Z_-]+)$'] = "website/change_theme/$1";

//Change language route
$route['^change-language/([a-z]+)$'] = "website/change_language/$1";

//The route that generates images with presets
$route['^attach/cache/([A-Za-z0-9_]+)/([A-Za-z0-9_]+)/([0-9]+)/([a-z0-9_]+)/([A-Za-z0-9_-]+)\.([A-z]{3,4})'] = "website/image_router/$1/$2/$3/$4/$5/$6";


/* End of file routes.php */
/* Location: ./application/config/routes.php */