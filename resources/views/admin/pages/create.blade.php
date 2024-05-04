@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Page</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('page.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="POST" name="pagesForm" id="pagesForm">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                <p class="text-danger"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                <p class="text-danger"></p>
                            </div>
                        </div>	
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                            </div>								
                        </div>                                    
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary">Create</button>
                <a href="{{route('page.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('js')
<script>
    $("#pagesForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url : '{{route("page.store")}}',
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                if(response['status'] == true){
                window.location.href="{{route('page.index')}}";
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
        })
    });

    $("#name").on("input", function(){
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
</script>
@endsection