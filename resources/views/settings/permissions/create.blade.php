@extends('layouts.settings.default')
@section('settings_title',trans('lang.permission_create'))
@section('settings_content')
    @include('adminlte-templates::common.errors')
    <div class="card shadow-sm">
        <div class="card-header">
            <ul class="nav nav-tabs d-flex flex-row align-items-start card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('permissions.index') !!}"><i class="fas fa-list mr-2"></i>{{trans('lang.permission_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{!! route('permissions.create') !!}"><i class="fas fa-plus mr-2"></i>{{trans('lang.permission_create')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('roles.index') !!}"><i class="fas fa-list mr-2"></i>{{trans('lang.role_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('roles.create') !!}"><i class="fas fa-plus mr-2"></i>{{trans('lang.role_create')}}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'permissions.store']) !!}
            <div class="row">
                @include('settings.permissions.fields')
            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
