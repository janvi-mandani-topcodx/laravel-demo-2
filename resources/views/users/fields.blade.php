<div class="row mb-4">
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold " for="firstName">First Name</label>
            <input type="text" id="firstName" class="form-control"  value="{{old('first_name')}}"  name="first_name" placeholder="Enter First Name"/>
            <span class="text-danger">@error('first_name') {{$message}}  @enderror</span>
        </div>
    </div>
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold" for="lastName">Last Name</label>
            <input type="text" id="lastName" class="form-control" value="{{old('last_name')}}" name="last_name" placeholder="Enter Last Name"/>
            <span class="text-danger">@error('last_name') {{$message}}  @enderror</span>
        </div>
    </div>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="email">Email Address</label>
    <input type="email" id="email" class="form-control" value="{{old('email')}}" name="email" placeholder="Enter Your Email" />
    <span class="text-danger">@error('email') {{$message}}  @enderror</span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="password">Password</label>
    <input type="password" id="password" class="form-control"  value="{{old('password')}}" name="password" placeholder="Enter Password"/>
    <span class="text-danger">@error('password') {{$message}}  @enderror</span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="confirmPassword">Confirm Password</label>
    <input type="password" id="confirmPassword" class="form-control"  value="{{old('confirm_password')}}" name="confirm_password" placeholder="Enter Confirm Password"/>
    <span class="text-danger">@error('confirm_password') {{$message}}  @enderror</span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="phoneNumber">Mobile Number</label>
    <input type="text" id="phoneNumber" class="form-control"  value="{{old('phone_number')}}" name="phone_number" placeholder="Enter Phone Number"/>
    <span class="text-danger">@error('phone_number') {{$message}}  @enderror</span>
</div>


<div class="form-group mb-4 ">
    <label class="form-label fw-bold">Hobbies</label>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" name="hobbies[]" value="singing" id="singing" {{ in_array('singing', old('hobbies', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="singing">
            Singing
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="dancing" name="hobbies[]" id="dancing" {{ in_array('dancing', old('hobbies', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="dancing">
            Dancing
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="acting" name="hobbies[]" id="acting" {{ in_array('acting', old('hobbies', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="acting">
            Acting
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="cooking" name="hobbies[]" id="cooking" {{ in_array('cooking', old('hobbies', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="cooking">
            Cooking
        </label>
    </div>
    <span class="text-danger">@error('hobbies') {{$message}}  @enderror</span>
</div>

<div class="form-group mb-4">

    <div class="form-check ms-4">
        <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
        <label class="form-check-label" for="male">Male</label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
        <label class="form-check-label" for="female">Female</label>
    </div>
    <span class="text-danger">@error('gender') {{$message}}  @enderror</span>
</div>

<div>
    <label class="form-label fw-bold " >Role</label>
    <select name="roles" id="roles" class="border my-3 form-control">
        @foreach($roles as $role)
            <option value="{{$role->id}}">{{$role->name}}</option>
        @endforeach
    </select>
</div>


<div class="form-group mb-4">
    <label class="form-label fw-bold" for="customFile">Image</label>
    <input type="file" class="form-control" id="customFile" name="image[]" multiple/>
    <div id="imagePreview"></div>
</div>



<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Submit</button>
