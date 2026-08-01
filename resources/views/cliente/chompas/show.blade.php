@extends('layouts.catalogo')

@section('titulo', $chompa->nombre)

@section('contenido')

@include('cliente.componentes.producto-detalle-tallas', [
  'producto'      => $chompa,
  'formAction'    => route('cliente.chompas.agregar'),
  'hiddenName'    => 'chompa_id',
  'breadcrumbCat' => 'Chompas',
  'breadcrumbUrl' => route('cliente.catalogo.index', ['categoria' => 'chompa']),
])

@endsection
