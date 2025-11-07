@csrf
<div class="row mb-4">
    <div class="col">
        <div  class="form-group">
            <label class="form-label fw-bold " for="firstName">First Name</label>
            <input type="text" id="firstName" class="form-control"  value="{{old('first_name')}}"  name="first_name" placeholder="Enter Your First Name"/>
            <span style="color: darkred" class="first-name-error"></span>
        </div>
    </div>
</div>

<div  class="form-group mb-4">
    <label class="form-label fw-bold" for="lastName">Last Name</label>
    <input type="text" id="lastName" class="form-control"  value="{{old('last_name')}}"  name="last_name" placeholder="Enter Your Last Name"/>
    <span style="color: darkred" class="last-name-error"></span>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" for="address">Address</label>
    <input type="text" id="address" class="form-control"  value="{{old('address')}}"  name="address" placeholder="Enter Your Address"/>
    <span style="color: darkred" class="address-error"></span>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" for="state">State</label>
    <input type="text" id="state" class="form-control"  value="{{old('state')}}"  name="state" placeholder="Enter Your State"/>
    <span style="color: darkred" class="state-error"></span>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" for="country">Country</label>
    <input type="text" id="country" class="form-control"  value="{{old('country')}}"  name="country" placeholder="Enter Your Country"/>
    <span style="color: darkred" class="country-error"></span>
</div>

<div class="form-group mb-4">
    <label class="form-label fw-bold" for="delivery">Delivery</label>
    <textarea id="delivery" class="form-control"  name="delivery" placeholder="Enter Delivery">{{old('delivery')}}</textarea>
    <span style="color: darkred" class="delivery-error"></span>
</div>
@if(auth()->user()->hasPermissionTo('create_order'))
<div  class="form-group mb-4">
    <button type="button" class="btn btn-success" id="checkoutButton">Checkout</button>
</div>
@endif
