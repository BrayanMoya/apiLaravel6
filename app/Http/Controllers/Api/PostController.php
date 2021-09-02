<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Post as PostRequests;
use Illuminate\Http\Response;


class PostController extends Controller
{
    /** @var Post $post */
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return response()->json($this->post->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequests $request
     */
    public function store(PostRequests $request): JsonResponse
    {
        $post = $this->post->create($request->all());

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequests $request
     * @param Post $post
     */
    public function update(PostRequests $request, Post $post)
    {
        $post->update($request->all());

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
