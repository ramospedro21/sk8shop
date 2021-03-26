<div class="col-{{ isset($colsMobile) ? 12 / $colsMobile : 6 }} col-md-{{ 12 / $cols }} text-center mb-5">
    <div class="pt-3">
        <a href="{{ url('/p/' . $product['slug'] ) }}">
            @if(count($product['images']) > 0)
                <img src="{{ $product['images'][0]['url'] }}" alt="" class="w-100">
            @else
                <img src="{{ url('/images/no_product.png') }}" alt="" class="w-100">
            @endif
        </a>


        <a href="{{ url('/p/' . $product['slug'] ) }}">

            <p class="mt-4 mb-0 pb-0 px-md-4 h6">
                {{ $product['title'] }}
            </p>

        </a>

        @if($product['stocks'][0]['promote_price'] === '0.00')

            <a href="{{ url('/p/' . $product['slug'] ) }}" class="mt-2">
                <p class="text-primary h5 pb-0 mb-0">
                    R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}
                </p>
                <p>
                    <small class="text-muted">até {{ $product['installments'] }} x R$ {{ number_format( ($product['stocks'][0]['price'] / $product['installments']), 2, ',', '.') }}</small>
                </p>
            </a>
        @else

            <a href="{{ url('/p/' . $product['slug'] ) }}" class="mt-2">
                <h6 class="text-muted h5 pb-0 mb-0">
                    <strike>R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}</strike>
                    <span class="text-primary ml-1">
                        R$ {{ number_format($product['stocks'][0]['promote_price'], 2, ',', '.') }}
                    </span>
                </h6>
            </a>

            <a href="{{ url('/p/' . $product['slug'] ) }}">
                <p>
                    <small class="text-muted">até {{ $product['installments'] }} x R$ {{ number_format( ($product['stocks'][0]['promote_price'] / $product['installments']), 2, ',', '.') }}</small>
                </p>
            </a>
        @endif

    </div>

</div>
