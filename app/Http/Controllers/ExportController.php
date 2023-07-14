<?php

namespace App\Http\Controllers;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function users(){
        $users = User::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Correo electrónico');
        $sheet->setCellValue('C1', 'Fecha de creación');

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                $user->name,
                $user->email,
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }
        $sheet->fromArray($data, null, 'A2');
        


        $writer = new Xlsx($spreadsheet);
        $writer->save('user_list.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="user_list.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
