<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\React\IndexReactRequest;
use App\Models\React;
use App\Repositories\React\ReactRepositoryInterface;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

/**
 * @tags Users Management
 */
class ReactController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected ReactRepositoryInterface $reactRepository,
    ){
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexReactRequest $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(React $react)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(React $react)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
