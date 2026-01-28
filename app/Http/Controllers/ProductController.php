<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        return inertia('Product/ProductShow');
    }
}
