@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="post" name="productForm" id="productForm" enctype="multipart/form-data">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label><span class="text-danger">*</span>
                                        <input type="text" name="title" value="{{$product->name}}" id="title" class="form-control" placeholder="Title">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" value="{{$product->slug}}" readonly id="slug" class="form-control" placeholder="Slug">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{$product->description}}</textarea>
                                    </div>
                                </div>  
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="short_description">Short Description</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">{{$product->short_description}}</textarea>
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="shipping_returns">Shipping and Return</label>
                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Shipping and Return">{{$product->shipping_returns}}</textarea>
                                    </div>
                                </div>                                             
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>	
                            <input type="hidden" id="image_id" name="image_id[]">							
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">    
                                    <br>Drop files here or click to upload.<br><br>                                            
                                </div>
                            </div>
                        </div>
                        @if (!$product->product_images->isEmpty())
                        @foreach ($product->product_images as $product_image)
                            <div class="mb-3">
                                <img src="{{ asset('uploads/products/'.$product_image->image) }}" alt="" width="200" height="150">
                            </div>
                        @endforeach
                    @endif                                                                      
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label><span class="text-danger">*</span>
                                        <input type="text" value="{{$product->price}}" name="price" id="price" class="form-control" placeholder="Price">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" value="{{$product->compare_price}}" id="compare_price" class="form-control" placeholder="Compare Price">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>	
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label><span class="text-danger">*</span>
                                        <input type="text" value="{{$product->sku}}" name="sku" id="sku" class="form-control" placeholder="sku">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" value="{{$product->barcode}}" id="barcode" class="form-control" placeholder="Barcode">	
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" 
                                            @if ($product->track_qty == "Yes") checked @endif value="Yes"><label for="track_qty" class="custom-control-label">Track Quantity</label><span class="text-danger">*</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" value="{{$product->qty}}" class="form-control" placeholder="Qty">
                                        <p class="error"></p>	
                                    </div>
                                </div>                                         
                            </div>
                        </div>	                                                                      
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{$product->status == '1' ? 'selected' : ''}} value="1">Active</option>
                                    <option {{$product->status == '0' ? 'selected' : ''}} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label><span class="text-danger">*</span>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                    <option @if ($product->category_id == $category->id) selected @endif value="{{$category->id}}">{{$category->name}}</option>    
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Select Sub Category</option>
                                    @foreach($subCategories as $subCategory)
                                    @if($subCategory->category_id == $product->category_id)
                                        <option value="{{ $subCategory->id }}" 
                                            {{ $subCategory->id == $product->sub_category_id ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option {{$product->brand_id == $brand->id ? 'selected' : ''}} value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option {{$product->is_featured == 'No' ? 'selected' : ''}} value="No">No</option>
                                    <option {{$product->is_featured == 'Yes' ? 'selected' : ''}} value="Yes">Yes</option>                                                
                                </select>
                            </div>
                        </div>
                    </div>                                 
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Edit</button>
                <a href="{{route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('js')
<script>
$("#productForm").submit(function(e){
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("products.update",$product->id)}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('products.index')}}";
                $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#price").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#sku").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#qty").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            }else{
                var errors = response['errors'];
                // console.log(err);
              if(errors['title']){
                $("#title").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['title']);
              }else{
                $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['slug']){
                $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
              }else{
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['price']){
                $("#price").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['price']);
              }else{
                $("#price").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['sku']){
                $("#sku").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['sku']);
              }else{
                $("#sku").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['qty']){
                $("#qty").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['qty']);
              }else{
                $("#qty").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['category']){
                $("#category").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['category']);
              }else{
                $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }

            }
        },
        error:function(jqXHR, exceptions){
            console.log("Something went wrong");
        }
    })
})

$("#title").change(function(){
    var ele = $(this);
    $.ajax({
        url : '{{route("getSlug")}}',
        type : 'get',
        data : {title : ele.val()},
        dataType : 'json',
        success:function(response){
            if(response['status']==true){
                $("#slug").val(response['slug']);
            }
        }
    });
})

Dropzone.autoDiscover = false;    
const dropzone = $("#image").dropzone({ 
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url:  "{{route('temp-images.create')}}",
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,wqimage/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, success: function(file, response){
        $("#image_id").val(function (index, existingValue) {
            return existingValue + (existingValue ? ',' : '') + response.image_id;
        });
       
       
    }
});

$("#category").on("change",function(){
    // alert($(this).val());
    $cat_val = $(this).val();
    $.ajax({
        url :  "/admin/products/get_sub_category",
        type :'get',
        dataType : 'json',
        data :{
            category_id : $cat_val
        },
        success : function(res){
            // console.log(res);
            var subCategorySelect = $("#sub_category");
            subCategorySelect.empty(); 
            subCategorySelect.append("<option>Select Sub Category</option>");
            for (var i = 0; i < res.length; i++) {
                subCategorySelect.append("<option value='" + res[i].id + "'>" + res[i].name + "</option>");
            }
        }
        
    })
})
</script>
@endsection