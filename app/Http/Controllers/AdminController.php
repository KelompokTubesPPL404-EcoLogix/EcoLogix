<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the emissions management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function emissions()
    {
        return view('admin.emissions');
    }

    /**
     * Show the carbon credits purchase page.
     *
     * @return \Illuminate\Http\Response
     */
    public function credits()
    {
        return view('admin.credits');
    }

    /**
     * Show the notification center page.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        return view('admin.notifications');
    }

    /**
     * Show the reports generation page.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports()
    {
        return view('admin.reports');
    }
}