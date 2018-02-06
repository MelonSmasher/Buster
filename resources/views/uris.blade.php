@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        URIs
                        <a class="pull-right" href="{{route('new.uri')}}">
                            Add URI
                        </a>
                    </div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-hover table-condensed">
                            <thead>
                            <th>
                                Path
                            </th>
                            <th>
                                <p class="pull-right"></p>
                            </th>
                            </thead>
                            @foreach($uris as $uri)
                                <tr>
                                    <td>
                                        {{$uri->path}}
                                    </td>
                                    <td class="">
                                        <a class="btn btn-danger btn-sm pull-right"
                                           href="{{route('delete.uri', [$uri->id])}}"><span aria-hidden="true"
                                                                                            class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="panel-footer center-div">
                        {{ $uris->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
