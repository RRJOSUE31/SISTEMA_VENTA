@extends('adminlte::page')

@section('content_header')
    
@stop

@section('content')
 @livewire('reportes.reportes-seniat',['isopen' => $isopen]) 
@stop

@section('css')

@stop

@section('js')

@stop