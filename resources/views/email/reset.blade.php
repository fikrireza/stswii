<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <p>
      Hai, {{ $data[0]['name'] }}.
    </p>

    <p>
      Akun anda telah direset.
      <br>Silahkan klik link berikut untuk aktifasi akun kamu :
      <br>
      <br>
      Email : <b>{{ $data[0]['email'] }}</b>
      <br>
      Password : <b>12345678</b>
      <br>
      <br>
      <a href="{{ URL::to('/') }}">
        {{ URL::to('/') }}
      </a>
    </p>


  </body>
</html>
