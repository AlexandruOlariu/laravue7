<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*Route::get('/pag1',function (){
    return view('pag1');
});*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => 'api'], function () {
    Route::Resource('flowers', 'FlowersController');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('api/documentation', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5swagger.api');
});

Route::resource('/message','MessagesController')->only([
     'store','show'
]);

Route::post('/send','MessagesController@sendemail');

Route::get('/bpmn', function () {
    return redirect()->route('index');
})->name('bpmn');;

Route::get('pullrequest', 'PullRequestController@index')->name('index');
Route::get('pullrequest/create', 'PullRequestController@getCreatePullRequest')->name('create');
Route::post('pullrequest/create', 'PullRequestController@postCreatePullRequest')->name('create');


Route::get('pullrequest/CompleteazaDateDeBazaFurnizor/{id}', 'PullRequestController@getCompleteazaDateDeBazaFurnizorPullRequest')->name('CompleteazaDateDeBazaFurnizor');
Route::post('pullrequest/CompleteazaDateDeBazaFurnizor', 'PullRequestController@postCompleteazaDateDeBazaFurnizorPullRequest')->name('CompleteazaDateDeBazaFurnizor');

Route::get('pullrequest/CreeazaContFurnizor/{id}', 'PullRequestController@getCreeazaContFurnizorPullRequest')->name('CreeazaContFurnizor');
Route::post('pullrequest/CreeazaContFurnizor', 'PullRequestController@postCreeazaContFurnizorPullRequest')->name('CreeazaContFurnizor');

Route::get('pullrequest/VerificareFurnizor/{id}', 'PullRequestController@getVerificareFurnizorPullRequest')->name('VerificareFurnizor');
Route::post('pullrequest/VerificareFurnizor', 'PullRequestController@postVerificareFurnizorPullRequest')->name('VerificareFurnizor');

Route::get('pullrequest/IntraInCont/{id}', 'PullRequestController@getIntraInContPullRequest')->name('IntraInCont');
Route::post('pullrequest/IntraInCont', 'PullRequestController@postIntraInContPullRequest')->name('IntraInCont');

Route::group(['middleware' => 'web'], function () {
    Route::get('bar', function () {
        return csrf_token(); // works
    });
});

Route::get('login/okta', 'Auth\LoginController@redirectToProvider')->name('login-okta');
Route::get('login/okta/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('pullrequest/showDiag/{id}','PullRequestController@showDiag')->name('showDiag');
