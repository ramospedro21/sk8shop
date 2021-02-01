<div class="col-{{ isset($colsMobile) ? 12 / $colsMobile : 6 }} col-md-{{ 12 / $cols }} text-center mb-5">
    <div class="pt-3">
        <a href="{{ url('/p/' . $product['product']['slug'] ) }}">
            @if(count($product['images']) > 0)
                <img src="{{ $product['images'][0]['url'] }}" alt="" class="w-100">
            @else
                <img src="{{ url('/images/no_product.png') }}" alt="" class="w-100">
            @endif
        </a>

        <p class="mt-4 mb-0 pb-0 px-md-4 h6">
            {{ $product['product']['title'] }}
        </p>

        @if($product['stocks'][0]['promote_price'] === '0.00')
            <p class="font-weight-bold text-secondary h4 pb-0 mb-0">
                R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}
            </p>
            <p>
                <small class="text-muted">até {{ $product['product']['installments'] }} x R$ {{ number_format( ($product['stocks'][0]['price'] / $product['product']['installments']), 2, ',', '.') }}</small>
            </p>
        @else
            <h6 class="font-weight-bold text-danger h6 pb-0 mb-0">
                R$ {{ number_format($product['stocks'][0]['promote_price'], 2, ',', '.') }} 
            </h6>
            <h6 class="font-weight-bold text-primary h4 pb-0 mb-0">
                <strike>R$ {{ number_format($product['stocks'][0]['price'], 2, ',', '.') }}</strike>
            </h6>
            <p>
                <small class="text-muted">até {{ $product['product']['installments'] }} x R$ {{ number_format( ($product['stocks'][0]['promote_price'] / $product['product']['installments']), 2, ',', '.') }}</small>
            </p>
        @endif

    </div>

</div>
