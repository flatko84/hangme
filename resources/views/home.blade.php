@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Games played - {{ $games }} <br>
                    Games won - {{ $won }} <br>
                    Games lost - {{ $lost }} <br>
                    @if ( $saved>0 )
                    <input type=button value="Continue game" onClick="window.location='/game'">
                    @else

                    <br>
                    <input type=button value="Start new game" onClick="window.location='/game'">
                    
                    @if (isset($open_games))
                    <h3>Join game:</h3>
                    @foreach ($open_games as $open_game)
                    <a href = "{{ $open_game['url'] }}">
                      {{ $open_game['name'] }}
                    </a>
                    <br>


                    @endforeach
                  @else
                  <h3>No games to join</h3>
                  @endif
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
