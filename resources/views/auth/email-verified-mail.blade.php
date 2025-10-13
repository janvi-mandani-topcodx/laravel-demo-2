<div>
     <h3 class="">{{$mailMessage}}</h3>
     <h5 class="">{{$subject}}</h5>
     <a href="{{ route('verify.email', ['id' => $user->id]) }}">Verify Email Address</a>
</div>

