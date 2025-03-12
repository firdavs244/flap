@extends('layout')
@section('quest', 'active')
@section('slot')
    <br />
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h2>Savol</h2>
                        @php
                            $j = 0;
                        @endphp
                        @if($solved == null)
                            @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                            @elseif(isset($err))
                                <div class="alert alert-danger" role="alert">
                                    {{ $err }}
                                </div>
                            @else
                                @foreach($answers as $answer)
                                    @if($answer->user_id == \Illuminate\Support\Facades\Auth::id())
                                        @php $j = 1 @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($j == 1)
                                @else
                                <form action="{{ route('questions.show.do',[$story->id, $question->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="background-color: rgb(33, 136, 56)!important; border: none">Bajarish</button>
                                </form>
                               @endif
                            @endif
                        @endif
                            @unset($j)
                    </div>
                    <div class="card-body">
                        @if($question->photo != 'default.png' and isset($question->photo))
                            <a href="{{ asset('storage/' . $question->photo) }}">
                                <img src="{{ asset('storage/' . $question->photo) }}" alt="">
                            </a>
                        @endif
                        <ul class="list-group">
                            <h4 style="line-height: 1.5rem">{!! nl2br(e($question->question)) !!}</h4>
                            <br>
                                <strong>Savolni bajarayotganlar:</strong>
                            <li class="list-group-item">
                                @foreach($answers as $answer)
                                    @if($answer->answer === null)
                                        <span>{{$answer->user->name}} | {{\Carbon\Carbon::parse($answer->created_at)->format('Y d-M H:i:s')}}</span>
                                        <br>
                                    @endif
                                @endforeach
                            </li>
                        </ul>
                            <br>
                            <br>
                            @foreach($answers as $answer)
                                @if($answer->answer!= null)
                            <li class="d-flex justify-content-center mb-4">
                                <img id="block" src="{{ asset('assets/imgs/user.png') }}" alt="avatar"
                                     class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
                                <div style="width: 764px !important;" class="card">
                                    <div class="card-header d-flex justify-content-between p-3" style="background-color: whitesmoke">
                                        <div id="cente" style="width: 75%!important;" class="d-flex align-items-center">
                                            <p style="width: 75%!important;" class="fw-bold mb-0">
                                                {{ $answer->user->name }}
                                                @if($answer->winner == 1)
                                                    <i class="fa-solid fa-trophy text-warning mx-1" style="scale: 150%!important;"></i>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-muted small mb-0 d-block">
                                                {{ \Carbon\Carbon::parse($answer->created_at)->format('Y d-M H:i:s') }}
                                            </p>
                                            @if($answer->correct !== null)
                                            <p class="text-muted small mb-0 d-block">
                                                {{ \Carbon\Carbon::parse($answer->updated_at)->format('Y d-M H:i:s') }}
                                            </p>
                                            @endif
                                            @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']) && $solved == null)
                                                <a onclick="return confirm('Amalni tasdiqlang!!! \nSavol g\'olibi - {{ $answer->user->name }}')" href="{{ route('questions.answer.show.winner', [$story->id, $question->id, $answer->id]) }}" class="text-warning d-inline mr-10" style="border: 0;padding: 0; height: 30px!important;width: 30px!important;"><i style="scale: 160%" class="fa-solid fa-trophy"></i></a>
                                                <a href="{{ route('questions.answer.show.correct', [$story->id, $question->id, $answer->id]) }}" class="text-success d-inline mr-10" style="border: 0;padding: 0; height: 30px!important;width: 30px!important;"><i style="scale: 160%" class="fa-solid fa-check"></i></a>
                                                <a href="{{ route('questions.answer.show.incorrect', [$story->id, $question->id, $answer->id]) }}" class="text-danger d-inline mr-10" style="border: 0;padding: 0; height: 30px!important;width: 30px!important;"><i style="scale: 160%" class="fa-solid fa-xmark"></i></a>
                                            @endif
                                            @if(($answer->user->id == \Illuminate\Support\Facades\Auth::id() and $answer->correct === null)|| \Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                                                <form class="d-inline" action="{{ route('questions.show.answer.delete', [$story->id, $question->id]) }}" onsubmit="return confirm('O\'chirishni xoxlaysizmi?')" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                                    <button type="submit" style="border: 0;padding: 0; height: 20px!important;width: 20px!important;"><i style="scale: 130%" class="fa-solid fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="center" class="card-body @if($answer->correct === 1) alert-success @elseif($answer->correct === 0) alert-danger @endif">
                                        <p class="mb-0">
                                            {!! nl2br(e($answer->answer)) !!}
                                        </p>
                                    </div>
                                </div>
                            </li>
                                @endif
                            @endforeach
                            @foreach($answers as $answer)
                                @if($answer->user_id == \Illuminate\Support\Facades\Auth::id() && $answer->correct === null && $solved == null)
                                    @if(isset($err))
                                    @else
                                        <form action="{{ route('questions.show.answer', [$story->id, $question->id]) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="answer">Javobingizni kiriting:</label>
                                                <textarea class="form-control" id="answer" name="answer" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Javobni yuborish</button>
                                        </form>
                                        @break
                                    @endif
                                @endif
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
