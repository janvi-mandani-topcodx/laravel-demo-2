<div  class="form-group my-2">
    <label class="form-label fw-bold" for="name">Permission name</label>
    <input type="text" id="name" class="form-control" value="{{$permission->name}}" name="name" placeholder="Enter Permission name"/>
    <span class="text-danger">@error('name') {{$message}}  @enderror</span>
</div>
<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Submit</button>
