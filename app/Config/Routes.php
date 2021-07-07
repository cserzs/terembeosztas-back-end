<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');
$routes->get('view/(:num)', 'Home::showclass/$1');
$routes->add('login', 'Home::login');

$routes->group('api', ['filter' => 'api-auth'], function($routes) {
	$routes->get('rooms', 'Api::rooms');
	$routes->get('classes', 'Api::classes');
	$routes->get('assignment/day/(:num)', 'Api::assignmentOfDay/$1');
	$routes->get('catalogofday/(:num)', 'Api::catalogOfDay/$1');
	$routes->get('roombindings/checkduplicate/(:num)', 'Api::checkduplicate/$1');
	$routes->post('roombindings', 'Api::saveAssignment');
	$routes->put('roombindings/(:num)/(:num)/(:num)/(:num)/(:num)', 'Api::updateAssignment/$1/$2/$3/$4/$5');
	$routes->delete('roombindings/(:num)/(:num)/(:num)/(:num)/(:num)', 'Api::deleteAssignment/$1/$2/$3/$4/$5');
	$routes->delete('dailyroombindings/(:num)', 'Api::deleteOneDayAssignment/$1');
});

$routes->group('admin', ['filter' => 'admin-auth'], function($routes) {
	$routes->get('index', 'Admin::index');
	$routes->get('assignment/edit', 'Admin::editassignment');
    //  vue.js valtozat
	$routes->get('catalog/edit', 'Admin::editCatalog');
    //  angularjs valtozat
	$routes->get('assignment/pdf', 'Admin::pdfview');	
	$routes->get('rooms/empty/pdf', 'Admin::emptyroomspdf');
	$routes->get('logout', 'Admin::logout');

	//$routes->get('assignment/pdfA3', 'Admin::index');	
	//$routes->get('rooms/empty', 'Admin::index');
});

$routes->group('rooms', ['filter' => 'admin-auth'], function($routes) {
	$routes->get('index', 'Rooms::index');
	$routes->add('new', 'Rooms::create');
	$routes->get('edit/(:num)', 'Rooms::edit/$1');
	$routes->post('edit', 'Rooms::edit');
	$routes->get('delete/(:num)', 'Rooms::delete/$1	');
});

$routes->group('schoolclass', ['filter' => 'admin-auth'], function($routes) {
	$routes->get('index', 'Schoolclass::index');
	$routes->add('new', 'Schoolclass::create');
	$routes->get('edit/(:num)', 'Schoolclass::edit/$1');
	$routes->post('edit', 'Schoolclass::edit');
	$routes->get('delete/(:num)', 'Schoolclass::delete/$1	');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
