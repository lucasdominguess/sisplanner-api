<?php

namespace App\Http\Controllers\Users;

use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserExportRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Interfaces\PdfExporterInterface;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

    return $user;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
         $validatedData = $request->validated();

         $user->update($validatedData);

         Log::info('Usuário atualizado com sucesso: ' . $user->name);
         return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);
        Log::info("Usuário $user->name deletado com sucesso");
        return response()->json(['message' => "Usuário $user->name deletado com sucesso"], 200);
    }

    public function export_users(UserExportRequest $request,PdfExporterInterface $pdfExporter)
    {
           $filterValidatedData = $request->validated();
            $ext = $filterValidatedData['ext'] ?? 'xlsx';
            $filename = 'Users';

                  if(strtolower($ext) == 'pdf'){
                        // $export = new InventariosExport($filterValidatedData);
                        $users = User::all();
                       return $pdfExporter->downloadFromView('Export.users', ['users' => $users], "$filename.$ext");
                }
                   $users = User::all();
                return Excel::download($users, "$filename.$ext");
    }
}
