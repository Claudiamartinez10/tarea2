<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{

    // Load index page view
    public function index()
    {
    return view('index');
    }
    
    //Cargar los valores del estudiante para mostralos en ajax
    public function fetchAll()
    {
    $estu = Student::all();
    $output = '';
    if ($estu->count() > 0) {
    $output .= '<table class="table table-striped table-sm text-center align-middle">
    
    <thead>
    <tr>
    <th>ID</th>
    <th>Sueldo</th>
    <th>Nombre Completo</th>
    <th>Genero</th>
    <th>Cargo</th>
    <th>Edad</th>
    <th>Acciones</th>
    </tr>
    </thead>
    <tbody>';
    foreach ($estu as $est) {
    $output .= '<tr>
    <td>' . $est->id . '</td>
    
    <td><img src="storage/images/' . $est->sueldo . '" width="50" class="img-thumbnail rounded-circle"></td>
    
    <td>' . $est->nombre . ' ' . $est->apellido . '</td>
    <td>' . $est->genero . '</td>
    <td>' . $est->cargo . '</td>
    <td>' . $est->edad . '</td>
    <td>
    
    <a href="#" id="' . $est->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editStudentModal"><i class="bi-pencil-square h4"></i></a>
    
    <a href="#" id="' . $est->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
    </td>
    </tr>';
    }
    $output .= '</tbody></table>';
    echo $output;
    } else {
    echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
    
    }
    }
    
    // Insertar a new Student ajax request
    public function store(Request $request)
    {
    $file = $request->file('foto');
    $fileName = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/images', $fileName);
    
    $arrayStudent = ['nombre' =>$request->nombre, 'apellido' =>$request->apellido, 'genero' =>$request->genero, 'edad' =>$request->edad, 'cargo' =>$request->cargo, 'sueldo' =>$request->sueldo];
    Student::create($arrayStudent);
    return response()->json([
    'status' => 200,
    ]);
    }
    // Load edit Student ajax request
    public function edit(Request $request)
    {
    $id = $request->id;
    $std = Student::find($id);
    return response()->json($std);
    }
    
 // handle update an tudent ajax request
    public function update(Request $request)
    {
    $fileName = '';
    $stud = Student::find($request->student_id);
    if ($request->hasFile('sueldo')) {
    
    $file = $request->file('sueldo');
    $fileName = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/images', $fileName);
    if ($stud->foto) {
    Storage::delete('public/images/' . $stud->sueldo);
    }
    } else {
    $fileName = $request->student_sueldo;
    }    
    $arrayStudent = ['nombre' =>$request->nombre, 'apellido' =>$request->apellido, 'genero' =>$request->genero, 'edad' =>$request->edad, 'cargo' =>$request->cargo, 'foto' =>$request->foto];
    $stud->update($arrayStudent);
    return response()->json([
    'status' => 200,
    ]);
    }
    
    // handle delete an Student ajax request
    public function delete(Request $request)
    {
    $id = $request->id;
    $studet = Student::find($id);
    if (Storage::delete('public/images/' . $studet->foto)) {
    Student::destroy($id);
    }
    }
    }
