@extends('frontend.master')

@section('body')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row product_data">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="{{ asset('admin/product-image/' . $product->image) }}" alt="">
                        </div>
                        {{-- <div class="product__details__pic__slider owl-carousel">
                                <img data-imgbigurl="{{ asset('admin/product-image/'.$product->image) }}"
                                    src="{{ asset('admin/product-image/'.$product->image) }}" alt="">
                                <img data-imgbigurl="{{ asset('admin/product-image/'.$product->image) }}"
                                    src="{{ asset('admin/product-image/'.$product->image) }}" alt="">
                                <img data-imgbigurl="{{ asset('admin/product-image/'.$product->image) }}"
                                    src="{{ asset('admin/product-image/'.$product->image) }}" alt="">
                                <img data-imgbigurl="{{ asset('admin/product-image/'.$product->image) }}"
                                    src="{{ asset('admin/product-image/'.$product->image) }}" alt="">
                            </div> --}}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ $product->name }}</h3>
                        {{-- <div class="product__details__rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i>
                                <span>(18 reviews)</span>
                            </div> --}}
                        <div class="product__details__price">
                            @php
                                $acceptedBargain = \App\Models\Bargain::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)
                                    ->where('status', 1)
                                    ->first();
                            @endphp
                            @if ($acceptedBargain)
                                <span class="fw-bold">
                                    {{ number_format($acceptedBargain->offered_price, 2) }}
                                </span>
                            @else
                                @if ($product->discount_amount)
                                    <span class="text-muted" style="text-decoration: line-through">
                                        Regular Price: {{ number_format($product->price, 2) }}
                                    </span>
                                    <br>
                                    <span class="fw-bold  ms-2">
                                        Discount Price: {{ number_format($product->discount_amount, 2) }}
                                    </span>
                                @else
                                    <span class="fw-bold">
                                        {{ number_format($product->price, 2) }}
                                    </span>
                                @endif
                            @endif
                            {{ generalSettings('currency') }}
                        </div>
                        <p>{{ $product->short_description }}</p>
                        @if ($product->qty > 0)
                            <input type="hidden" class="product_id" value="{{ $product->id }}">
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty ">
                                        <span class="dec qtybtn">-</span>
                                        <input type="text" class="qty_input" value="1">
                                        <span class="inc qtybtn">+</span>
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="primary-btn addToCart">ADD TO CART</a>
                            <a href="#" data-id="{{ $product->id }}" class="heart-icon addToWishlist"><span
                                    class="icon_heart_alt"></span></a>
                            <br>
                            @php
                                $userOfferCount = \App\Models\Bargain::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)
                                    ->count();

                                $acceptedOffer = \App\Models\Bargain::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)
                                    ->where('status', 1)
                                    ->exists();
                            @endphp
                            @if (auth()->user() && $product->min_price !== null)
                                @if ($userOfferCount >= 3 || $acceptedOffer)
                                    <button class="btn btn-secondary mt-2" disabled>💬 Bargain Locked</button>
                                @else
                                    <button class="primary-btn mt-2 border-0" data-bs-toggle="modal"
                                        data-bs-target="#bargainModal">
                                        Bargain System
                                    </button>
                                @endif
                            @endif
                            <!-- Bargain Modal -->
                            <div class="modal fade" id="bargainModal" tabindex="-1" aria-labelledby="bargainModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content p-3 rounded-3 shadow">
                                        <div class="modal-header border-0">
                                            <h3 class="modal-title fw-bold" id="bargainModalLabel">Bargain System</h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body d-flex justify-content-between">

                                            <!-- Left: Offer form -->
                                            <div class="w-50 pe-3 border-end">
                                                <label for="offerPrice" class="form-label">Enter your offer price</label>
                                                <input type="number" id="offerPrice" class="form-control mb-3"
                                                    placeholder="offer price">

                                                <button id="submitOfferBtn" class="primary-btn border-0">Submit
                                                    Offer</button>

                                                <p id="offerResponse" class="mt-3 fw-bold text-center"></p>
                                            </div>

                                            <div class="w-45 ps-3 ml-3">
                                                <h6 class="fw-bold mb-3">📋 Bargain Rules:</h6>
                                                <ul class="small" style="padding-top: 0px; margin-top:0px">
                                                    <li>1. You can make a maximum of <strong>3 offers</strong> per product.
                                                    </li>
                                                    <li>2. If your offer is accepted, you can purchase the product at your
                                                        offered price.</li>
                                                    <li>3. If all your chances are used, you can only buy it at the original
                                                        price.</li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <ul>
                            <li><b>Availability</b> <span>{{ $product->qty > 0 ? 'In Stock' : 'Out of Stock' }}</span></li>
                            @if ($product->weight)
                                <li><b>Weight</b> <span>{{ $product->weight }}</span></li>
                            @endif
                            {{-- <li><b>Share on</b>
                                <div class="share">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                </div>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Product Video</a>
                            </li>
                            {{-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                        aria-selected="false">Reviews <span>(1)</span></a>
                                </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="it-blog-item-wrap">
                                    <div class="it-blog-item pt-5">
                                        <div class="it-blog-thumb mb-10 p-relative">
                                            @php
                                                $youtubeUrl = $product->youtube_video_link;
                                                parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $queryParams);
                                                $videoId = $queryParams['v'] ?? null;
                                            @endphp
                                            <iframe width="100%" height="500"
                                                src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0"
                                                allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Product Review</h6>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Description</a>
                            </li>
                            {{-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                        aria-selected="false">Reviews <span>(1)</span></a>
                                </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Product Description</h6>
                                    <p>{!! $product->description !!}</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Product Review</h6>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->

    <!-- Related Product Section Begin -->
    <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title">
                        <h2>Related Products</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($relatedProducts as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg"
                                data-setbg="{{ asset('admin/product-image/' . $product->image) }}">
                                <ul class="product__item__pic__hover">
                                    <li><a href="#" data-id="{{ $product->id }}" class="addToWishlist"><i
                                                class="fa fa-heart"></i></a></li>
                                    <li><a href="{{ route('product.single.view', ['id' => $product->slug]) }}"><i
                                                class="fa fa-eye"></i></a></li>
                                    @if ($product->qty > 0)
                                        <li><a href="#" data-id="{{ $product->id }}" class="directAddToCart"><i
                                                    class="fa fa-shopping-cart "></i></a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><a
                                        href="{{ route('product.single.view', ['id' => $product->slug]) }}">{{ $product->name }}</a>
                                </h6>
                                <h5>{{ $product->discount_amount ? $product->discount_amount : $product->price }}
                                    {{ generalSettings('currency') }} </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Related Product Section End -->

    {{-- <script>
        document.getElementById('submitOfferBtn').addEventListener('click', function() {
            const offer = document.getElementById('offerPrice').value;
            const responseEl = document.getElementById('offerResponse');

            fetch('{{ route('product.bargain', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        offered_price: offer
                    })
                })
                .then(res => res.json())
                .then(data => {
                    responseEl.textContent = data.message;
                    responseEl.classList.remove('text-danger', 'text-success');

                    if (data.status == 1) {
                        responseEl.classList.add('text-success');
                        document.getElementById('submitOfferBtn').disabled = true;
                    } else {
                        responseEl.classList.add('text-danger');
                    }
                })
                .catch(() => {
                    responseEl.textContent = 'Something went wrong.';
                    responseEl.classList.add('text-danger');
                });
        });
    </script> --}}


    <script>
        document.getElementById('submitOfferBtn').addEventListener('click', function() {
            const offer = document.getElementById('offerPrice').value;
            const responseEl = document.getElementById('offerResponse');
            const submitBtn = document.getElementById('submitOfferBtn');

            fetch('{{ route('product.bargain', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        offered_price: offer
                    })
                })
                .then(res => res.json())
                .then(data => {
                    responseEl.textContent = data.message;
                    responseEl.classList.remove('text-danger', 'text-success');

                    if (data.status == 1) {
                        responseEl.classList.add('text-success');
                        submitBtn.disabled = true;
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'bargainModal'));
                            modal.hide();
                            location.reload();
                        }, 1000);
                    } else {
                        responseEl.classList.add('text-danger');
                        if (data.message.includes('used all 3 bargain chances')) {
                            submitBtn.disabled = true;
                            setTimeout(() => location.reload(), 1000);
                        }
                    }
                })
                .catch(() => {
                    responseEl.textContent = 'Something went wrong.';
                    responseEl.classList.add('text-danger');
                });
        });
    </script>


@endsection
