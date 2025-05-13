<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
    /**
     * Show the perusahaan dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('staff.dashboard');
    }

    /**
     * Show the create perusahaan form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('perusahaan.create');
    }

    /**
     * Show the detail of perusahaan.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($kode_perusahaan)
    {
        return view('perusahaan.show');
    }

    /**
     * Show the edit perusahaan form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_perusahaan)
    {
        return view('perusahaan.edit');
    }

    /**
     * Store a newly created perusahaan.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('perusahaan.index');
    }

    /**
     * Update the specified perusahaan.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_perusahaan)
    {
        return redirect()->route('perusahaan.index');
    }

    /**
     * Remove the specified perusahaan.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_perusahaan)
    {
        return redirect()->route('perusahaan.index');
    }
}