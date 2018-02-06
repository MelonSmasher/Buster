@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Reverse Proxies
                        <a class="pull-right" href="{{route('new.proxy')}}">
                            Add Proxy
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
                                Name
                            </th>
                            <th>
                                IP Address
                            </th>
                            <th>
                                <p class="pull-right"></p>
                            </th>
                            </thead>
                            @foreach($proxies as $proxy)
                                <tr>
                                    <td>
                                        {{$proxy->label}}
                                    </td>
                                    <td>
                                        {{$proxy->ip}}
                                    </td>
                                    <td class="">
                                        <a class="btn btn-danger btn-sm pull-right"
                                           href="{{route('delete.proxy', [$proxy->id])}}"><span aria-hidden="true"
                                                                                            class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="panel-footer center-div">
                        {{ $proxies->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
