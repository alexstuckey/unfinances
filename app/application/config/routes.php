<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home/homepage';
$route['404_override'] = 'home/show404';
$route['translate_uri_dashes'] = FALSE;

$route['onboarding/welcome'] = 'onboarding/welcome';
$route['onboarding/submit']['POST'] = 'onboarding/submit';

$route['home'] = 'home/homepage';

$route['admin'] = 'admin/emails';
$route['admin/emails'] = 'admin/emails';
$route['admin/emails/edit'] = 'admin/emailsEdit';
$route['admin/settings'] = 'admin/settings';
$route['admin/settings/addAdmin'] = 'admin/settingsAddAdminOrTreasurer/admin';
$route['admin/settings/addTreasurer'] = 'admin/settingsAddAdminOrTreasurer/treasurer';
$route['admin/cost_centres'] = 'admin/cost_centres';
$route['admin/cost_centres/add'] = 'admin/addCostCentre';
$route['admin/cost_centres/changeManager'] = 'admin/changeCostCentreManager';

$route['settings'] = 'user/settings';


$route['expenses/my'] = 'expenses/my';
$route['expenses/review'] = 'expenses/review';
$route['expenses/claim/new'] = 'claim/newClaim';
$route['expenses/claim/(:num)'] = 'claim/showClaim/$1/web';
$route['api/expenses/claim/(:num)'] = 'claim/showClaim/$1/json';
$route['api/expenses/saveClaim/(:num)'] = 'claim/saveClaimByJSON/$1';
$route['api/expenses/submitClaim/(:num)'] = 'claim/submitClaimByJSON/$1';
$route['api/expenses/commentClaim/(:num)'] = 'claim/commentClaimByJSON/$1';

$route['api/user/(:any)'] = 'claim/getUser/$1';

$route['file/upload']['POST'] = 'file/do_upload';


$route['(.+)'] = 'home/show404';
