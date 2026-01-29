<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return inertia('Admin/Product/AdminProductIndex');
    }

    public function edit()
    {
        return inertia('Admin/Product/AdminProductEdit');
    }

    public function create()
    {
        return inertia('Admin/Product/AdminProductCreate');
    }
}
