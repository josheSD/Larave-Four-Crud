<?php

namespace App\Http\Controllers;

use App\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empleados = Empleado::paginate(1);
        return view('empleados.index',['empleados' => $empleados]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos = [
            'Nombre' => 'required|string|max:100',
            'ApellidoPaterno' => 'required|string|max:100',
            'ApellidoMaterno' => 'required|string|max:100',
            'Correo' => 'required|email',
            'Foto' => 'required|max:1000|mimes:jpeg,png,jpg'
        ];
        $Mensaje = ["required" => "El :attribute es requerido"];
        $this->validate($request,$campos,$Mensaje);

        $datosEmpleado = request()->except('_token');

        if($request->hasFile('Foto')){

            $datosEmpleado['Foto'] = $request->file('Foto')
                                        ->store('uploads','public');

        }

        Empleado::insert($datosEmpleado);

        return redirect('empleados')->with('Mensaje','Empleado agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit',compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campos = [
            'Nombre' => 'required|string|max:100',
            'ApellidoPaterno' => 'required|string|max:100',
            'ApellidoMaterno' => 'required|string|max:100',
            'Correo' => 'required|email'
        ];
        if($request->hasFile('Foto')){
            $campos+= ['Foto' => 'required|max:1000|mimes:jpeg,png,jpg'];
        }
        $Mensaje = ["required" => "El :attribute es requerido"];
        $this->validate($request,$campos,$Mensaje);

        $datosEmpleado = request()->except(['_token','_method']);

        if($request->hasFile('Foto')){
            $empleado = Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto);
            $datosEmpleado['Foto'] = $request->file('Foto')
                            ->store('uploads','public');
        }

        Empleado::where('id','=',$id)->update($datosEmpleado);

        //$empleado = Empleado::findOrFail($id);
        //return view('empleados.edit',compact('empleado'));
        return redirect('empleados')->with('Mensaje','Empleado modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        if(Storage::delete('public/'.$empleado->Foto)){
            Empleado::destroy($id);
        }
        return redirect('empleados')->with('Mensaje','Empleado eliminado');

    }
}
