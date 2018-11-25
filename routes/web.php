<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return view("main")->render();
});
$router->get('/beavor', function(\Illuminate\Http\Request $request) use($router){
    $data = (new \Beavor\Helpers\DataExtractor)->getValue($request->json);
    $classes = (new \Beavor\Actions\BuildClass)->buildRootClass($data, "Class", "BeavorDemo");
    $classesResponse = array_map(function(\Nette\PhpGenerator\ClassType $classType){
        return [
          'text' => $classType->getNamespace()->getName() . "\\" . $classType->getName(),
          'content' => (string) $classType,
        ];
    }, $classes);
    return new \Illuminate\Http\JsonResponse($classesResponse);
});