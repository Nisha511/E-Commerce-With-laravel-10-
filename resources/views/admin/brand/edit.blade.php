@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Brand</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('brands.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" id="brandForm" name="brandForm" method="post">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{$brand->name}}" name="name" id="name" class="form-control" placeholder="Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" value="{{$brand->slug}}" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p></p>
                            </div>
                        </div>		
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{$brand->status == 1 ? 'selected':''}} value="1">Active</option>
                                    <option {{$brand->status == 0 ? 'selected':''}} value="0">Block</option>
                                </select>
                            </div>
                        </div>								
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Edit</button>
                <a href="{{route('brands.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')
<script>
$("#brandForm").submit(function(e){
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("brands.update",$brand->id)}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('brands.index')}}";
                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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

            }
        },
        error:function(jqXHR, exceptions){
            console.log("Something went wrong");
        }
    })
})

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
})

</script>
@endsection