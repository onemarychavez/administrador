<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->group('home', function($routes){
    $routes->get('','Home::index');
    $routes->get('estadistica','Home::getEstadisticas');
});
$routes->group('/',function($routes){
        $routes->get('','LoginController::index');
        $routes->post('','LoginController::login');
});

$routes->group('empresa',function($routes){
    $routes->get('','EmpresaController::index');
    $routes->get('list','EmpresaController::list');
    $routes->get('(:num)','EmpresaController::listById/$1');
    $routes->post('','EmpresaController::create');
    $routes->put('(:num)','EmpresaController::update/$1');
    $routes->delete('(:num)','EmpresaController::delete/$1');
});

$routes->group('usuario',function($routes){
    $routes->get('','UsuariosController::index');
    $routes->get('list','UsuariosController::list');
    $routes->get('(:num)','UsuariosController::listById/$1');
    $routes->post('','UsuariosController::create');
    $routes->put('(:num)','UsuariosController::update/$1');
    $routes->delete('(:num)','UsuariosController::delete/$1');
});

$routes->group('sucursal',function($routes){
    $routes->get('','SucursalController::index');
    $routes->get('list','SucursalController::list');
    $routes->get('(:num)','SucursalController::listById/$1');
    $routes->get('empresa/(:num)','SucursalController::listByEmpresa/$1');
    $routes->post('','SucursalController::create');
    $routes->put('(:num)','SucursalController::update/$1');
    $routes->delete('(:num)','SucursalController::delete/$1');
});

$routes->group('repartidor',function($routes){
    $routes->get('','RepartidorController::index');
    $routes->get('list','RepartidorController::list');
    $routes->get('(:num)','RepartidorController::listById/$1');
    $routes->post('','RepartidorController::create');
    $routes->put('(:num)','RepartidorController::update/$1');
    $routes->delete('(:num)','RepartidorController::delete/$1');
}); 

$routes->group('categoria',function($routes){
    $routes->get('','CategoriaController::list');
 
});
$routes->group('departamento',function($routes){
    $routes->get('list','DepartamentoController::list');
 
});
$routes->group('municipio',function($routes){
    $routes->get('list','MunicipiosController::list');
 
});

$routes->group('menu',function($routes){
    $routes->get('','MenuController::index');
    $routes->get('(:num)','MenuController::getById/$1');
    $routes->get('list','MenuController::list');
    $routes->get('list/(:num)/(:num)','MenuController::listFilter/$1/$2');
    $routes->post('','MenuController::create');
    $routes->delete('(:num)','MenuController::delete/$1');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
