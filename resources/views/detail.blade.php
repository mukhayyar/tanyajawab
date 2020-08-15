@extends('layouts.master')

@push('style')
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow" style="color: black">
        <div class="card-body">
            <h5 class="card-title">{{ $pertanyaan->judul }}</h5>
            <div class="d-flex justify-content-between">
                <h6 class="card-subtitle mb-2 text-muted">{{ $pertanyaan->user->name }} |
                    <small>{{ $pertanyaan->created_at }}</small></h6>
                <div class="d-flex justify-content-end">
                    <form action="/upvote_pertanyaan/{{ $pertanyaan->id }}" method="POST" class="form-inline">
                        @csrf
                        @method('put')
                        @if(Auth::user()->id === $pertanyaan->user_id)
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Kamu tidak diperbolehkan like pertanyaan kamu">
                            <button disabled type="submit" class="btn btn-primary btn-sm"><i
                                class="fas fa-thumbs-up"></i>
                            ({{ $pertanyaan->up }})</button>
                        </span>                            
                        @else
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-thumbs-up"></i>
                                ({{ $pertanyaan->up }})</button>
                        @endif
                    </form>
                    <form action="/downvote_pertanyaan/{{ $pertanyaan->id }}" method="POST" class="form-inline">
                        @csrf
                        @method('put')
                        @if(Auth::user()->reputation < 15 || Auth::user()->id === $pertanyaan->user_id)
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Reputasi kamu masih kurang atau ini adalah pertanyaan kamu">
                            <button disabled style="margin-left: 1em" type="submit" class="btn btn-primary btn-sm"><i
                                class="fas fa-thumbs-down"></i>
                            ({{ $pertanyaan->down }})</button>
                        </span>                            
                        @else
                            <input type="hidden" id="id" name="id" value="{{ Auth::user()->id }}">
                            <button style="margin-left: 1em" type="submit" class="btn btn-primary btn-sm"><i
                                    class="fas fa-thumbs-down"></i>
                                ({{ $pertanyaan->down }})</button>
                        @endif
                    </form>
                </div>
            </div>
            <hr>
            <p class="card-text">{!!$pertanyaan->isi!!}</p>
            @foreach($pertanyaan->tags as $tag)
                <span class="badge badge-pill badge-primary">{{ $tag->title }}</span>
            @endforeach
            <hr>
            @foreach($pertanyaan->komentar->take(3) as $komentar)
                <p class="card-text">{!!$komentar->isi!!} <small>{{ $komentar->user->name }} |
                        {{ $komentar->created_at }}</small></p>
            @endforeach
            <a style="color: gray" href="/detail/{{ $pertanyaan->id }}/komentar">Tambah Komentar</a>
        </div>
    </div><br>

    <h5 style="color: black"> {{ $pertanyaan->jumlahJawaban() }} Jawaban</h5>
    @foreach($pertanyaan->jawaban as $jawaban)
        <div class="card" style="color: black">
            <div class="card shadow" style="color: black">
                <div class="card-body">
                    @if($pertanyaan->user_id === Auth::user()->id && $jawaban->user_id !== Auth::user()->id && $pertanyaan->jawaban_tepat_id === null)
                        <form action="/detail/{{ $pertanyaan->id }}/jawaban_benar" method="POST">
                            @csrf
                            <label for="">Tandai benar </label>
                            <input type="checkbox" name="jawaban_tepat" id="check" value="{{ $jawaban->id }}">
                            <input type="hidden" id="id" name="id" value="{{ $jawaban->user_id }}">
                            <input type="submit" value="Submit">
                        </form>
                    @endif
                    @if($jawaban->id === $pertanyaan->jawaban_tepat_id)
                        <p>Jawaban Terbaik <i class="fas fa-check" style="color: green"></i></p>
                    @endif
                    <div class="d-flex justify-content-between">
                        <h6 class="card-subtitle mb-2 text-muted">{{ $jawaban->user->name }} |
                            <small>{{ $jawaban->created_at }}</small></h6>
                        <div class="d-flex justify-content-end">
                            <form action="/upvote_jawaban/{{ $jawaban->id }}" method="POST" class="form-inline">
                                @csrf
                                @method('put')
                                @if(Auth::user()->id === $jawaban->user_id)
                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Kamu tidak diperbolehkan like jawaban kamu">
                                    <button disabled type="submit" class="btn btn-primary btn-sm"><i
                                        class="fas fa-thumbs-up"></i>
                                    ({{ $jawaban->up }})</button>
                                </span>                                    
                                @else
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                            class="fas fa-thumbs-up"></i>
                                        ({{ $jawaban->up }})</button>
                                @endif
                            </form>
                            <form action="/downvote_jawaban/{{ $jawaban->id }}" method="POST" class="form-inline">
                                @csrf
                                @method('put')
                                @if(Auth::user()->reputation < 15 || Auth::user()->id === $jawaban->user_id)
                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Reputasi kamu masih kurang atau ini adalah jawaban kamu">
                                    <button disabled style="margin-left: 1em" type="submit"
                                    class="btn btn-primary btn-sm"><i class="fas fa-thumbs-down"></i>
                                    ({{ $jawaban->down }})</button>
                                </span>                                    
                                @else
                                    <input type="hidden" id="id" name="id" value="{{ Auth::user()->id }}">
                                    <button style="margin-left: 1em" type="submit" class="btn btn-primary btn-sm"><i
                                            class="fas fa-thumbs-down"></i>
                                        ({{ $jawaban->down }})</button>
                                @endif
                            </form>
                        </div>
                    </div>
                    <hr>
                    <p class="card-text">{!!$jawaban->isi!!}</p>
                    <hr>
                    @foreach($jawaban->komentar->take(3) as $komentar_jawaban)
                        <p class="card-text">{!!$komentar_jawaban->isi!!} <small>{{ $komentar_jawaban->user->name }}
                                | {{ $komentar_jawaban->created_at }}</small></p>
                    @endforeach
                    <a style="color: gray" href="/detail/{{ $pertanyaan->id }}/komentar/{{ $jawaban->id }}">Tambah
                        Komentar</a>
                </div>
            </div>
        </div><br>
    @endforeach

    <div>
        <form action="/detail/{{ $pertanyaan->id }}/jawaban" method="POST">
            @csrf
            <div class="form-group">
                <label for="isi">Isi/deskripsi Jawaban</label>
                <textarea name="isi" id="isi"
                    class="form-control my-editor">{{-- {!! old('isi', $isi) !!} --}}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
        </form>
    </div><br>
</div>
@endsection

@push('script')
    <script src="{{ asset('js/tinymce4.js') }}"></script>
@endpush
