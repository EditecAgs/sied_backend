<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function getInstitutions()
    {
        return Institution::all();
    }

    public function createInstitution(Request $request) {}

    public function updateInstitution(Request $request, $id) {}

    public function deleteInstitution($id) {}
}
