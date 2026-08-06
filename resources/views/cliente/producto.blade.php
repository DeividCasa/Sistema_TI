@extends('layouts.catalogo')

@section('titulo', $plantilla->nombre)

@section('contenido')

@include('cliente.componentes.producto-detalle-tallas', [
  'producto'      => $plantilla,
  'formAction'    => route('cliente.plantillas.agregar'),
  'hiddenName'    => 'plantilla_id',
  'breadcrumbCat' => 'Catálogo',
  'breadcrumbUrl' => session('catalogo_url', route('cliente.catalogo.index')),
])

@endsection
