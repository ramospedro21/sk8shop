@extends('layouts.app')
@section('content')
    <div class="container my-md-5"  id="cartPage">
        <div class="row mt-5">
            <div class="col-md-3 d-md-block d-none">
                <h2 class="text-primary font-price">
                    {{ $category['title'] }}
                </h2>
                <h2 class="h6 my-3 mb-5">
                    {{ $category['description'] }}
                </h2>
                <form>
                    @foreach($filters as $filter)
                        <h5 class="font-weight-bold mt-3 text-primary mt-5">{{ $filter['title'] }}</h5>
                        @foreach($filter['values'] as $value)
                            <div class="form-check mt-2 text-left">
                                <label class="form-check-label text-primary">
                                    <filter-option id="{{ $value['id'] }}"></filter-option>
                                    {{$value['title']}}
                                    <label class="form-check-label"></label>
                                </label>
                            </div>
                        @endforeach
                    @endforeach
                </form>
            </div>
            <div class="col-md-9 categories-margin">
                <div class="row">
                    @foreach($products as $product)
                        @include('components.product', ['product' => $product, 'cols' => 3])
                    @endforeach
                </div>
                {{-- <div class="row justify-content-end">
                    <h3 class="h6 mr-4 mt-2">
                        Pagina <b>{{ $products['current_page'] }}</b> de <b>{{ $products['last_page'] }}</b>
                    </h3>
                    @include('components.paginate', ['current_page' => $products['current_page'], 'from' => $products['from'], 'last_page' => $products['last_page']])
                </div> --}}
            </div>
        </div>
    </div>
@endsection
