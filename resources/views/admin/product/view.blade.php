@extends('admin.master')

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Product</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="payment-title"><strong>Name :</strong></td>
                                <td class="text-style">{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Category Name :</strong></td>
                                <td class="text-style">{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Brand Name :</strong></td>
                                <td class="text-style">{{ $product->brand->name }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Short Description :</strong></td>
                                <td class="text-style">{{ $product->short_description ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Description :</strong></td>
                                <td class="text-style">{!! $product->description !!}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Quantity :</strong></td>
                                <td class="text-style">{{ $product->qty }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Price :</strong></td>
                                <td class="text-style">{{ $product->price }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Minimum Price :</strong></td>
                                <td class="text-style">{{ $product->min_price ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Weight :</strong></td>
                                <td class="text-style">{{ $product->weight ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Discount Amount:</strong></td>
                                <td class="text-style">{{ $product->discount_amount ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Youtube Video Link:</strong></td>
                                <td class="text-style">{{ $product->youtube_video_link ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Image :</strong></td>
                                <td class="text-dark "><img
                                        src="{{ asset('admin/product-image/' . $product->image) }} "style="border-radius: 10px;"
                                        alt="" width="150" height="150" /></td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Status :</strong></td>
                                <td class="text-style">{{ $product->status == 1 ? 'Active' : 'Inactive' }}</td>
                            </tr>
                            <tr>
                                <td class="payment-title"><strong>Created At :</strong></td>
                                <td class="text-style">{{ $product->created_at->format('m/d/Y') }}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
