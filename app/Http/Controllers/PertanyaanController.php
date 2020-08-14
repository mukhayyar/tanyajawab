<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pertanyaan;
use App\Tag;
use App\PertanyaanTag;

class PertanyaanController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pertanyaan = new Pertanyaan;
        $pertanyaan->judul = $request->judul;
        $pertanyaan->isi = $request->isi;
        $pertanyaan->user_id = auth()->user()->id;
        $pertanyaan->save();

        $tag_arr = explode('#', $request->tag);
        unset($tag_arr[0]);
        $tag_arr = array_values($tag_arr);

        $tag_id = array();

        foreach ($tag_arr as $tag_name) {
            $compare_tag = Tag::where('title', $tag_name)->first();
            if ($compare_tag) {
                array_push($tag_id, $compare_tag->id);
            } else {
                $tag = new Tag;
                $tag->title = $tag_name;
                $tag->save();
                array_push($tag_id, $tag->id);
            }
        }

        $pertanyaan->tags()->sync($tag_id);

        return redirect('/home')->with('sukses', 'Pertanyaan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pertanyaan $pertanyaan)
    {
        // dd($pertanyaan);
        return view('detail', compact('pertanyaan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pertanyaan $pertanyaan)
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
    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pertanyaan $pertanyaan)
    {
        $pertanyaan->destroy(0);
        return redirect('/home')->with('sukses', 'Pertanyaan berhasil dihapus');
    }
}
