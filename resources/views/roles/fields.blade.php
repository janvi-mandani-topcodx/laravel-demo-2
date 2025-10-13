<div  class="form-group my-2">
    <label class="form-label fw-bold" for="name">Role name</label>
    <input type="text" id="name" class="form-control" value="{{old('name')}}" name="name" placeholder="Enter Role name"/>
    <span class="text-danger">@error('name') {{$message}}  @enderror</span>
</div>


<div class="form-group mb-4 ">
    <label class="form-label fw-bold">Permissions</label>
    @foreach($permissionDetails as $permission)
        <div class="form-check ms-4">
            <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission['id'] }}" id="{{ $permission['name'] }}">
            <label class="form-check-label" for="{{ $permission['name'] }}">{{ $permission['name'] }}</label>
        </div>
    @endforeach
</div>




<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Submit</button>
