@extends('layouts.main',[
    'pageTitle' => 'Produtos'
])

@section('content')

    @php
        $isEdit = !empty($product->id);
    @endphp

    <div class="page page-customer page-form">

        <div class="page-header">
            <h1>Produtos <small>{{ $isEdit ? 'Editar produto' : 'Criar produto' }}</small></h1>
        </div>

        <div class="page-body">

            @include('components.alert')

            <form action="{{ url('product') }}" method="post">

                @csrf

                @method($isEdit ? 'PUT' : 'POST')

                <input type="hidden" name="id" value="{{ $product->id }}">

                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$product->name) }}" maxlength="100" required />
                </div>

                <div class="form-group">
                    <label for="category_id">Categorias</label>
                    <select name="category_id" class="form-select">

                        @if (count($categories) > 0)

                            <option value="" {{ $isEdit ?? 'selected' }}>Selecione uma categoria</option>

                            @foreach ($categories as $category)

                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>

                            @endforeach

                        @else

                            <option value="" selected>Nenhuma categoria criada</option>

                        @endif

                    </select>
                </div>

                <div class="form-group">
                    <label>Preço</label>

                    <div class="input-group">

                        <span class="input-group-text">R$</span>
                        <input type="text" name="price" required step="0.01" class="form-control price" value="{{ old('price',$product->price) }}">

                    </div>

                </div>

                <div class="page-control">

                    <a href="{{ url('products') }}" class="btn btn-outline-primary">Voltar</a>

                    <button type="submit" class="btn btn-success">Enviar</button>

                </div>

            </form>

        </div>

    </div>

@endsection
