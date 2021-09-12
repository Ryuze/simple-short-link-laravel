<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'real_link' => 'required|url|max:255'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            do{
                $random = Str::random(5);
            } while (Link::where('gen_link', $random)->first() != null);

            $link = Link::create([
                'gen_link' => $random,
                'real_link' => $request->real_link
            ]);

            $response = [
                'message' => 'Link created!',
                'data' => $link
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $q) {
            return response()->json([
                'message' => 'Failed ' . $q->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Open the real link from database
     */
    public function openLink($id)
    {
        if (Link::where('gen_link', $id)->first() != null) {
            $link = Link::where('gen_link', $id)->first();
            
            return redirect()->away($link->real_link);
        }

        return redirect()->route('homepage')->with('message', 'URL incorrect or already removed.');
    }
}
