<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamos;
use App\Models\Cliente;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Response;


class PrestamosController extends Controller
{
    public function index()
    {
        $prestamos = Prestamos::all();
        return response()->json($prestamos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_prestamo' => 'required|date',
            'dias_prestamo' => 'required|integer|min:1',
            'estado' => 'required|in:En Prestamo,Devuelto',
        ]);

        $prestamo = Prestamos::create($request->all());

        return response()->json($prestamo, 201);
    }

    public function show($id)
    {
        $prestamo = Prestamos::find($id);
        return response()->json($prestamo);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_prestamo' => 'required|date',
            'dias_prestamo' => 'required|integer|min:1',
            'estado' => 'required|in:En Prestamo,Devuelto',
        ]);

        $prestamo = Prestamos::find($id);
        $prestamo->update($request->all());

        return response()->json($prestamo, 200);
    }

    public function destroy($id)
    {
        $prestamo = Prestamos::find($id);
        $prestamo->delete();

        return response()->json(null, 204);
    }

    public function clientesConLibrosVencidos()
    {
        $clientesConLibrosVencidos = Cliente::whereHas('prestamos', function ($query) {
            $query->where('estado', 'En Prestamo')
                  ->whereRaw('fecha_prestamo + INTERVAL dias_prestamo DAY < NOW()');
        })->get();

        return $clientesConLibrosVencidos;
    }

    public function segmentado_por_semana()
    {
        $prestamosPorSemana = Prestamos::selectRaw('YEARWEEK(fecha_prestamo) as semana, COUNT(*) as cantidad')
        ->groupBy('semana')
        ->orderBy('semana')
        ->get();
    
        $prestamosPorSemana->transform(function ($item) {
            $year = substr($item->semana, 0, 4);
            $week = substr($item->semana, 4);
            $fechaInicioSemana = Carbon::now()->setISODate($year, $week, 1);
            $fechaFinSemana = Carbon::now()->setISODate($year, $week, 7);
            $item->fecha_inicio = $fechaInicioSemana->toDateString();
            $item->fecha_fin = $fechaFinSemana->toDateString();
        
            return $item;
        });
    return $prestamosPorSemana;
    }

    public function segmentado_por_mes()
    {
        $prestamosPorMes = Prestamos::selectRaw('YEAR(fecha_prestamo) as year, MONTH(fecha_prestamo) as month, COUNT(*) as cantidad')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        $prestamosPorMes->transform(function ($item) {
            $nombreMes = Carbon::createFromDate($item->year, $item->month)->monthName;
            $item->nombre_mes = $nombreMes;
    
            return $item;
        });
        return $prestamosPorMes;
    }

    public function generarReportePDF()
    {
        $prestamosPorMes = $this->segmentado_por_mes();
        $html = '<h1>Reporte de Préstamos por Mes</h1>';
        $html .= '<table>';
        $html .= '<thead><tr><th>Año</th><th>Mes</th><th>Cantidad</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($prestamosPorMes as $prestamo) {
            $html .= '<tr>';
            $html .= '<td>' . $prestamo->year . '</td>';
            $html .= '<td>' . $prestamo->nombre_mes . '</td>';
            $html .= '<td>' . $prestamo->cantidad . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $response = new Response($pdfOutput);
        $response->header('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');    
        return $response;
    }

    public function generarReportePDFSemana()
    {
        $prestamosPorMes = $this->segmentado_por_semana();
        $html = '<h1>Reporte de Préstamos por Mes</h1>';
        $html .= '<table>';
        $html .= '<thead><tr><th>semana</th><th>cantidad de prestamos</th><th>fecha de inicio</th><th>fecha final</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($prestamosPorMes as $prestamo) {
            $html .= '<tr>';
            $html .= '<td>' . $prestamo->semana . '</td>';
            $html .= '<td>' . $prestamo->cantidad . '</td>';
            $html .= '<td>' . $prestamo->fecha_inicio . '</td>';
            $html .= '<td>' . $prestamo->fecha_fin . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $response = new Response($pdfOutput);
        $response->header('Access-Control-Allow-Origin', 'http://localhost:8080');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        return $response;
    }
}
