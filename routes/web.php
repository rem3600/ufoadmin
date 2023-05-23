<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    $html = "<h1 style='color:red'>Aliens</h1>";

    return view('welcome', [
        'html' => $html
    ]);
});

Route::get('/date', function () {
    $date = Carbon::now();
    $date->locale('nl_BE'); // Set the locale to Dutch (Belgium)

    echo $date->isoFormat('LL');
    echo $date->isoFormat('LLLL');
    echo "<hr>";
    echo $date->toDateString();
});

Route::get('/datetime', function () {
    $date = Carbon::now();
    $date->locale('nl_BE'); // Set the locale to Dutch (Belgium)

    echo $date->isoFormat('LLLL');
    echo "<hr>";
    echo $date->toDateString();
});

Route::get('specificdate', function () {
    $date = Carbon::createFromDate(2023, 5, 19);
    echo $date->isoFormat('LL');
    echo "<hr>";
    echo $date->toDateString();
});

Route::get('specificdatetime', function () {
    $date = Carbon::now();
    $specificDateTime = $date->addDays(3);
    echo $specificDateTime->isoFormat('LLLL');
    echo "<hr>";
    echo $specificDateTime->toDateString();
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    // not secure... not adding any new routes
});

// default a route login and redirect to admin/login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');


// create export csv routes that are protected by auth middlewar
Route::get('/export/csv', function () {
    // get all aliens and eager load the abilities
    $aliens = App\Models\Alien::with('abilities')->get();
    // dd($aliens);
    // foreach the aliens into a csv file without using a third party package
    $csvEport = "";
    foreach ($aliens as $alien) {
        // $startDate = Carbon::parse('2021-01-01');
        // $endDate = Carbon::parse('2023-12-31');
        // $dateToCheck = Carbon::now();
        if ($alien->created_at != null) {
            $csvEport .= $alien->created_at . "," . $alien->name . "," . $alien->email . "," . $alien->location . "," . implode(",", $alien->abilities->pluck('name')->toArray()) . "\n";
        }
        // else {
        //     echo "The date does not fall within the specified range.\n";
        // }
        // export the alien name, email and location and implode the abilities
        // $csvEport .= $alien->name . "," . $alien->email . "," . $alien->location . "," . implode(",", $alien->abilities->pluck('name')->toArray()) . "\n";
        // $csvEport .= $alien->name . ",".$alien->email.",".$alien->location."\n";
    }
    // return the csv file
    return response($csvEport, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="aliensExport.csv"'
    ]);
})->middleware('auth')->name('export');

// alternative using laracsv package

Route::get('/export/csv2', function () {
    $aliens = App\Models\Alien::with('abilities')->get();
    dd($aliens);
    $startDate = Carbon::parse('2021-01-01');
    $endDate = Carbon::parse('2023-12-31');
    $dateToCheck = Carbon::now();

    if ($dateToCheck->between($startDate, $endDate)) {
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($aliens, ['name', 'email', 'location'])->download();
    } else {
        echo "The date does not fall within the specified range.\n";
    }
})->middleware('auth')->name('export2');

Route::post('/export/csv3', function (Request $request) {

    // dd($request->all());

    $startDate = Carbon::parse($request->startDate);
    $endDate = Carbon::parse($request->endDate);

    $aliens = App\Models\Alien::with('abilities')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

    $csvExporter = new \Laracsv\Export();
    $csvExporter->build($aliens, ['name', 'email', 'location'])->download();

})->middleware('auth')->name('export3');


Route::post('/upload', function(Request $request) {

    $fileName = $request->file('file')->getClientOriginalName();
    $request->file('file')->move(public_path('storage'), $fileName);
    return response()->json(['success' => 'You have successfully upload file.']);
})->middleware('auth')->name('upload');


// second way to protect routes with auth middleware using a group function
/* Route::middleware('auth')->group(function () {
    Route::get('/test', function(){
        return "test";
    });
   
    Route::get('/export/csv', function(){
        return "export aliens";
    });

    Route::get('/export/pdf', function(){
        return "export aliens";
    });
}); */