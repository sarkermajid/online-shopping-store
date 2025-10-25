@extends('admin.master')


@push('styles')
    {{-- Image Upload CSS --}}
    <style>
        .image_container {
            height: 120px;
            width: 200px;
            border-radius: 6px;
            overflow: hidden;
            margin: 10px;
        }

        .image_container img {
            height: 100%;
            width: auto;
            object-fit: cover;
        }

        .image_container span {
            top: -6px;
            right: 8px;
            color: red;
            font-size: 28px;
            font-weight: normal;
            cursor: pointer;
        }

        .category-image {
            min-width: 200px;
            min-width: 100px;
            max-width: 400px;
            max-height: 280px;
        }

        .select2-selection{
            background: rgba(52, 82, 225,0.2) !important;
            border: inherit !important;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable{
            background: #3452E1 !important;
        }

    </style>
@endpush

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Edit Blog</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('blog.update', ['id'=>$blog->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">title</label>
                            @error('title')
                            <h6 class="modal-header justify-content-start"
                            style="font-weight: 800; color: #FFFFFF; background-color: red; padding-top: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                            {{ $message }}</h6>
                            @enderror
                            <input type="text" value="{{ $blog->title }}" name="title" class="form-control" id="title">
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">Categories</label>
                                    @error('blog_category_id')
                                    <h6 class="modal-header justify-content-start"
                                        style="font-weight: 800; color: #FFFFFF; background-color: red; padding-top: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                        {{ $message }}</h6>
                                    @enderror
                                    <select name="blog_category_id" class="@error('blog_category_id')is-invalid @enderror select2 form-control ">
                                        <option value="">-Select-</option>
                                        @foreach ($blogCategories as $category)
                                        <option
                                            @if (old('blog_category_id') == $category->id) selected @elseif($category->id == $blog->blogCategory->id) selected @endif
                                            value="{{ $category->id }}">{{ $category->name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slug">Description</label>
                            @error('description')
                            <h6 class="modal-header justify-content-start"
                            style="font-weight: 800; color: #FFFFFF; background-color: red; padding-top: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                            {{ $message }}</h6>
                            @enderror
                            <textarea name="description" id="summernote" class="form-control">{{ $blog->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image</label>
                                    <div class="mb-3">
                                        <td class="text-dark "><img src="{{ asset('admin/blog-image/'.$blog->image) }} "style="border-radius: 10px;" alt="" width="300" height=""/></td>
                                    </div>
                                    @error('image')
                                    <h6 class="modal-header justify-content-start"
                                        style="font-weight: 800; color: #FFFFFF; background-color: red; padding-top: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                        {{ $message }}</h6>
                                    @enderror
                                    <div class="card w-100 border shadow">
                                        <div class="card-header d-flex justify-content-start">
                                            <input type="file" name="image" id="image" accept="image/*"
                                                   class="d-none " onchange="showImage(this)">
                                            <button class="btn btn-sm btn-primary" type="button"
                                                    onclick="document.getElementById('image').click()">Select
                                                Image</button>
                                        </div>
                                        <div
                                            onclick="document.getElementById('image').click()"
                                            class="card-body  d-flex flex-wrap mx-auto my-2 justify-content-center"
                                            id="image-container">
                                            <img style="width:200px" class="mx-auto" id="thumbnil" src="{{ asset('admin/assets/images/upload.svg') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="d-block">Status</label>
                            <label for="Active" class="form-label"><input type="radio" name="status" value="1" id="Active" {{ $blog->status == 1 ? 'checked' : '' }}  class="label radio">Active
                            </label>
                            &nbsp;
                            &nbsp;
                            <label for="Deactive" class="form-label"><input type="radio" name="status" value="0" id="Deactive" {{ $blog->status == 0 ? 'checked' : '' }}  class="label radio">Deactive
                            </label>
                        </div>
                        <div>
                            <input type="submit" class="btn btn-primary" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    // image upload js code
    function showImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageType = /image.*/;
            if (!file.type.match(imageType)) {
                continue;
            }
            var img = document.getElementById('thumbnil');
            img.file = file;
            var reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    }

    // select2
     $('.select2').select2();
     $('[data-toggle="popover"]').popover();
</script>
@endpush
