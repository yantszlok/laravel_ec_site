@extends('user.user_master')
@section('user')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<div class="row" style="padding: 20px">
<div class="col-md-6">




<form method="post" action="{{ route('profile.store')}}" enctype="multipart/form-data">

  @csrf

  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">User Name</label>
    <input type="text" name="name" class="form-control" value="{{ $editData->name}}">
  </div>
  <div class="mb-3">
  <label for="exampleInputEmail1" class="form-label">User Email</label>
    <input type="email" name="email" class="form-control" value="{{ $editData->email}}">
  </div>

  <div class="mb-3">
  <label for="formFile" class="form-label">Profile Image</label>
  <input class="form-control" name="profile_photo_path" type="file" id="image">
</div>

<div class="mb-3">
  <img id="showImage" src="{{ (!empty($editData->profile_photo_path))? url('upload/user_images/'.$editData->profile_photo_path):url('upload/no_image.jpg') }}" style="width : 100px; height: 100px;">
</div>

  <button type="submit" class="btn btn-primary">Update</button>
</form>
</div> <!-- //end col-md-6 -->
</div><!-- // end row -->
<script type="text/javascript">
  $(document).ready(function(){
    $('#image').change(function(e){
      var reader = new FileReader();
      reader.onload = function(e){
        $('#showImage').attr('src',e.target.result);
      }
      reader.readAsDataURL(e.target.files['0']);
    });
  });
</script>

@endsection