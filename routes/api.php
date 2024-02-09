<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('autores', AutorController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('libros', LibroController::class);
Route::resource('prestamos', PrestamosController::class);
Route::get('/clientesConLibrosVencidos', [PrestamosController::class, 'clientesConLibrosVencidos']);
Route::get('/segmentados_por_mes', [PrestamosController::class, 'segmentado_por_mes']);
Route::get('/segmentados_por_semana', [PrestamosController::class, 'segmentado_por_semana']);
Route::get('/descargar-reporte-pdf-mes', [PrestamosController::class, 'generarReportePDF']);
Route::get('/descargar-reporte-pdf-semana', [PrestamosController::class, 'generarReportePDFSemana']);

