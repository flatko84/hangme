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


                    <h3>Join game:</h3>

                    <div id="opengames"></div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('gamescript')
<script type="text/javascript">var user_id={{ $user_id }}</script>
<script type="text/javascript" src="{{ asset('js/ajax-home.js') }}"></script>



@endsection
