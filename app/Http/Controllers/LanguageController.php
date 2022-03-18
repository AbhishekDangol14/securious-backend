<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index(){
        return response()->json([
            'success' => true,
            'message' => 'Language fetched successfully!',
            'data' => Language::select(['language_name', 'language_identifier'])->get()
        ], 200);

    }
}
