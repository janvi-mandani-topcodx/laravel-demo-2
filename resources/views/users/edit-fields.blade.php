@csrf
<input type="hidden" id="edit-user-id" data-id="{{$user->id}}">
<div class="row mb-4">
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold " for="firstName">First name</label>
            <input type="text" id="firstName" class="form-control"  value="{{$user->first_name}}"  name="first_name" placeholder="Enter First name"/>
            <span class="text-danger">@error('first_name') {{$message}} @enderror </span>
        </div>
    </div>
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold" for="lastName">Last name</label>
            <input type="text" id="lastName" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Enter Last name"/>
            <span class="text-danger">@error('last_name') {{$message}} @enderror </span>
        </div>
    </div>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="email">Email address</label>
    <input type="email" id="email" class="form-control" value="{{$user->email}}" name="email" placeholder="Enter your email" />
    <span class="text-danger">@error('email') {{$message}} @enderror </span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="password">Password</label>
    <input type="password" id="password" class="form-control"  value="" name="password" placeholder="Enter password"/>
    <span class="text-danger">@error('password') {{$message}} @enderror </span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="confirmPassword">Confirm password</label>
    <input type="password" id="confirmPassword" class="form-control"  value="" name="confirm_password" placeholder="Enter confirm password"/>
    <span class="text-danger">@error('confirm_password') {{$message}} @enderror </span>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="phoneNumber">Mobile number</label>
    <input type="text" id="phoneNumber" class="form-control"  value="{{$user->phone_number}}" name="phone_number" placeholder="Enter phone number"/>
    <span class="text-danger">@error('phone_number') {{$message}} @enderror </span>
</div>


@php
    $userHobbies = old('hobbies', json_decode($user->hobbies));
@endphp
<div class="form-group mb-4 ">
    <label class="form-label fw-bold">Hobbies</label>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" name="hobbies[]" value="singing" id="singing"  {{ in_array('singing', $userHobbies) ? 'checked' : '' }}>
        <label class="form-check-label" for="singing">
            singing
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="dancing" name="hobbies[]" id="dancing"  {{ in_array('dancing', $userHobbies) ? 'checked' : '' }}>
        <label class="form-check-label" for="dancing">
            dancing
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="acting" name="hobbies[]" id="acting"  {{ in_array('acting', $userHobbies) ? 'checked' : '' }}>
        <label class="form-check-label" for="acting">
            acting
        </label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="checkbox" value="cooking" name="hobbies[]" id="cooking"  {{ in_array('cooking', $userHobbies) ? 'checked' : '' }}>
        <label class="form-check-label" for="cooking">
            cooking
        </label>
    </div>
    <span class="text-danger">@error('hobbies') {{$message}} @enderror </span>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" >Gender</label>
    <div class="form-check ms-4">
        <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ $user->gender == 'male' ? 'checked' : '' }}>
        <label class="form-check-label" for="male">Male</label>
    </div>
    <div class="form-check ms-4">
        <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ $user->gender == 'female' ? 'checked' : '' }}>
        <label class="form-check-label" for="female">Female</label>
    </div>
    <span class="text-danger">@error('gender') {{$message}} @enderror </span>
</div>


<div>
    <label class="form-label fw-bold" >Role</label>
    <select name="roles" id="roles" class="border my-3">
        @foreach($roles as $role)
            <option value="{{$role->id}}" {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>{{$role->name}}</option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Update</button>
