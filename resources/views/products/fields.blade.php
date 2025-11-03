
<div  class="form-group mb-4">
    <label class="form-label fw-bold " for="title">Title</label>
    <input type="text" id="title" class="form-control"  value="{{old('title')}}"  name="title" placeholder="Enter title"/>
    <span class="text-danger">@error('title') {{$message}}  @enderror</span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="description">Description</label>
    <input type="text" id="description" class="form-control" value="{{old('description')}}" name="description" placeholder="Enter your description" />
    <span class="text-danger">@error('description') {{$message}}  @enderror</span>
</div>

<div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status">
    <label class="form-check-label" for="status">Status</label>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" for="customFile">Image</label>
    <input type="file" class="form-control" id="customFile" name="image[]" multiple/>
    <div id="imagePreview"></div>
</div>



