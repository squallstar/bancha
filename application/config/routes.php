<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| BANCHA INTERNAL ROUTING
| -------------------------------------------------------------------------
*/

$admin_path = rtrim(ADMIN_PUB_PATH, '/');

//Internal routing systems for administration
$route['^' . $admin_path .'$'] = "admin/auth/login";
$route['^' . $admin_path .'/pages$'] = "admin/contents";
$route['^' . $admin_path .'/pages/(.+)$'] = "admin/contents/$1";
$route['^' . ADMIN_PUB_PATH . '(.+)$'] = "admin/$1";

/*
| -------------------------------------------------------------------------
| FRONT END ROUTING
| -------------------------------------------------------------------------
*/

//The default routing method used for the website
$route['404_override'] = 'website/router';

//The action called as the homepage
$route['default_controller'] = "website/home";

//This route let you switch between the website themes
$route['^go-([a-zA-Z_-]+)$'] = "website/change_theme/$1";

//Change language route
$route['^change-language/([a-z]+)$'] = "website/change_language/$1";

//The route that generates images with presets
$route['^attach/cache/([A-Za-z0-9_]+)/([A-Za-z0-9_]+)/([0-9]+)/([a-z0-9_-]+)/([A-Za-z0-9_-]+)\.([A-z]{3,4})'] = "website/image_router/$1/$2/$3/$4/$5/$6";


/* End of file routes.php */
/* Location: ./application/config/routes.php */