@csrf
<input type="hidden" id="edit-user-id" data-id="{{$user->id}}">
<div class="row mb-4">
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold " for="first-name">First name</label>
            <input type="text" id="first-name" class="form-control"  value="{{$user->first_name}}"  name="first_name" placeholder="Enter First name"/>
        </div>
    </div>
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold" for="last-name">Last name</label>
            <input type="text" id="last-name" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Enter Last name"/>
        </div>
    </div>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="email">Email address</label>
    <input type="email" id="email" class="form-control" value="{{$user->email}}" name="email" placeholder="Enter your email" />
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="password">Password</label>
    <input type="password" id="password" class="form-control"  value="{{old('password')}}" name="password" placeholder="Enter password"/>
</div>
<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="confirm-password">Confirm password</label>
    <input type="password" id="confirm-password" class="form-control"  value="{{old('confirmPassword')}}" name="confirmPassword" placeholder="Enter confirm password"/>
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
</div>
<button type="submit" class="btn btn-primary btn-block mb-4 submit-btn">Update</button>
