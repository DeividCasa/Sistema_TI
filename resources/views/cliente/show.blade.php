@extends('layouts.catalogo')

@section('titulo', $uniforme->nombre)

@section('contenido')

@include('cliente.componentes.producto-detalle-tallas', [
  'producto'      => $uniforme,
  'formAction'    => route('cliente.carrito.agregar'),
  'hiddenName'    => 'uniforme_id',
  'breadcrumbCat' => 'Uniformes escolares',
  'breadcrumbUrl' => route('cliente.catalogo.index', ['categoria' => 'uniforme']),
])

@endsection
