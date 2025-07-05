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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'Auth';
$route['login'] = 'Auth/login';
$route['logout'] = 'Auth/logout';
$route['dashboard'] = 'Dashboard';
$route['change-password'] = 'Auth/change_password';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// User Management Routes (Admin only)
$route['admin/users'] = 'Admin/Users';
$route['admin/users/create'] = 'Admin/Users/create';
$route['admin/users/edit/(:num)'] = 'Admin/Users/edit/$1';
$route['admin/users/delete/(:num)'] = 'Admin/Users/delete/$1';
$route['admin/users/toggle/(:num)'] = 'Admin/Users/toggle_status/$1';
$route['admin/users/reset-password/(:num)'] = 'Admin/Users/reset_password/$1';

// Periode Akademik Routes (Admin only)
$route['admin/periode'] = 'Admin/Periode_akademik';
$route['admin/periode/create'] = 'Admin/Periode_akademik/create';
$route['admin/periode/edit/(:num)'] = 'Admin/Periode_akademik/edit/$1';
$route['admin/periode/activate/(:num)'] = 'Admin/Periode_akademik/activate/$1';
$route['admin/periode/delete/(:num)'] = 'Admin/Periode_akademik/delete/$1';

// Kelas Management Routes
// Admin (monitoring only)
$route['admin/kelas'] = 'Admin/Kelas';

// Guru (full management)
$route['guru/kelas'] = 'Guru/Kelas';
$route['guru/kelas/create'] = 'Guru/Kelas/create';
$route['guru/kelas/edit/(:num)'] = 'Guru/Kelas/edit/$1';
$route['guru/kelas/toggle/(:num)'] = 'Guru/Kelas/toggle_status/$1';
$route['guru/kelas/students/(:num)'] = 'Guru/Kelas/manage_students/$1';
$route['guru/kelas/add-student'] = 'Guru/Kelas/add_student';
$route['guru/kelas/remove-student'] = 'Guru/Kelas/remove_student';

// Siswa (view only)
$route['siswa/kelas'] = 'Siswa/Kelas';
$route['siswa/kelas/detail/(:num)'] = 'Siswa/Kelas/detail/$1';

// Tugas Routes - Guru
$route['guru/tugas'] = 'Guru/Tugas';
$route['guru/tugas/create'] = 'Guru/Tugas/create';
$route['guru/tugas/detail/(:num)'] = 'Guru/Tugas/detail/$1';
$route['guru/tugas/edit/(:num)'] = 'Guru/Tugas/edit/$1';
$route['guru/tugas/grade/(:num)'] = 'Guru/Tugas/grade/$1';
$route['guru/tugas/grade_simple'] = 'Guru/Tugas/grade_simple';

// Tugas Routes - Siswa  
$route['siswa/tugas'] = 'Siswa/Tugas';
$route['siswa/tugas/detail/(:num)'] = 'Siswa/Tugas/detail/$1';
$route['siswa/tugas/submit/(:num)'] = 'Siswa/Tugas/submit/$1';
$route['siswa/tugas/download/(:any)'] = 'Siswa/Tugas/download/$1';