<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeoGebraController extends Controller
{
    public function student()
    {
        return view('student.geogebra');
    }
    public function teacher()
    {
        return view('teacher.geogebra');
    }
}