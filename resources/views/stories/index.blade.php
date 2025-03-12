@extends('layout')
@section('quest', 'active')
@section('slot')
    <section class="mt-50 mb-50">
        <div class="container custom">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-header mb-50">
                        <div  class="d-flex justify-content-between align-items-center">
                            <h1 class="font-xxl text-brand">Savollar Guruhi</h1>
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin') || \Illuminate\Support\Facades\Auth::user()->hasRole('Teacher'))
                            <a class="btn btn-primary" href="{{ route('stories.create') }}">
                                Yangi Guruh Yaratish
                            </a>
                            @endif
                        </div>
{{--                        <div class="entry-meta meta-1 font-xs mt-15 mb-15">--}}
{{--                            <span class="post-by">32 Sub Categories</span>--}}
{{--                            <span class="post-on has-dot">1020k Article</span>--}}
{{--                            <span class="time-reading has-dot">480 Authors</span>--}}
{{--                            <span class="hit-count  has-dot">29M Views</span>--}}
{{--                        </div>--}}
                        <div class="sidebar-widget widget_search">
                        </div>
                    </div>
                    <div class="loop-grid">
                        <div class="row">
                            @foreach($stories as $story)
                            <div class="col-lg-6">
                                <article class="wow fadeIn animated hover-up mb-30">
                                    <div class="post-thumb img-hover-scale">
                                        <a href="{{ route('questions.index', $story->id) }}">
                                            @if($story->photo == 'default.png')
                                                <img style="height: 356.6px!important;" src="{{ asset('assets/imgs/default.png') }}" width="100%" alt="">
                                            @else
                                                <img style="height: 356.6px!important;" src="{{ asset('storage/' . $story->photo) }}" width="100%" alt="">
                                            @endif
                                        </a>
                                        <div class="entry-meta">
                                            <a class="entry-meta meta-2" href="">{{ $story->user->name }}</a>
                                        </div>
                                    </div>
                                    <div class="entry-content-2">
                                        <h3 style="overflow: hidden!important;height: 3.8rem!important;" class="post-title mb-15">
                                            <a style="" href="{{ route('questions.index', $story->id) }}">{{ $story->title }}</a>
                                        </h3>
                                        <div style="overflow: hidden;!important; height: 4.7rem!important;">
                                            <p class="post-exerpt mb-30">{{ $story->body }}</p>
                                        </div>
                                        <div class="entry-meta meta-1 font-xs color-grey mt-10 pb-10">
                                            <div class="align-items-center">
                                                <span class="post-on"> <i class="fi-rs-clock"></i>{{ \Carbon\Carbon::parse($story->created_at)->format('Y d-M H:i:s') }}
                                                    @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                                                    <a style="scale: 130%!important; margin-right: 10px" href="{{ route('stories.edit', $story->id) }}"><i style="scale: 130%" class="fa-regular fa-pen-to-square"></i></a>
                                                    <form onsubmit="return confirm('Amalni tasdiqlang!')" style="display: inline-block;" action="{{ route('stories.destroy', $story->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button style="background: none; border: none; cursor: pointer; color: #dc3545;" type="submit"><i style="scale: 130%" class="fa-regular fa-trash-alt"></i></button>
                                                    </form>
                                                    @endif
                                                </span>
                                            </div>
                                            <a style="scale: 130%" href="{{ route('questions.index', $story->id) }}" class="text-brand">Kirish <i class="fi-rs-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        {{ $stories->links() }}
    </div>
@endsection
