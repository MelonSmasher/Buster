@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading">API Key</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @foreach($apiKeys as $apiKey)
                            <p>
                            <div class="well well-sm console-text">
                                <span class="text-danger">{{$apiKey->key}}</span>
                            </div>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
