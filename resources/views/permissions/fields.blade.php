<div  class="form-group my-2">
    <label class="form-label fw-bold" for="name">Permission Name</label>
    <input type="text" id="name" class="form-control" value="{{old('name')}}" name="name" placeholder="Enter Permission Name"/>
    <span class="text-danger">@error('name') {{$message}}  @enderror</span>
</div>
<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Submit</button>
