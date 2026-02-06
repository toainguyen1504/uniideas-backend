<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

/**
 * @tags Comments Management
 */
class CommentController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
    ) {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexCommentRequest $request)
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
