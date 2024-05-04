@extends('admin.layout.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('sub-categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
          <form action="" id="subCategoryForm" name="subCategoryForm" method="post">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{$category->id == $sub_category->category_id ? 'selected':""}}>{{$category->name}}</option>
                            @endforeach
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" value="{{$sub_category->name}}" name="name" id="name" class="form-control" placeholder="Name">	
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text"  name="slug" id="slug" class="form-control" value="{{$sub_category->slug}}" placeholder="Slug">	
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option {{$sub_category->status == 1 ? 'selected':''}} value="1">Active</option>
                                <option {{$sub_category->status == 0 ? 'selected':''}} value="0">Block</option>
                            </select>
                        </div>
                    </div>		
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="showHome">Show On Home</label>
                            <select name="showHome" id="showHome" class="form-control">
                                <option {{$sub_category->showHome == 'Yes' ? 'selected':''}} value="Yes">Yes</option>
                                <option {{$sub_category->showHome == 'No' ? 'selected':''}} value="No">No</option>
                            </select>
                        </div>
                    </div>									
                </div>
            </div>
         				
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Edit</button>
            <a href="{{route('sub-categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </form>			
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')
<script>
$("#name").change(function(){
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
});

$("#subCategoryForm").submit(function(e){
    
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("sub-categories.update",$sub_category->id)}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('sub-categories.index')}}";
                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            }else{
                var errors = response['errors'];
                // console.log(err);
              if(errors['name']){
                $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
              }else{
                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['slug']){
                $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
              }else{
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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

</script>
@endsection