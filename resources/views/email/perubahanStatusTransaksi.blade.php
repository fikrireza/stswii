<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <p>Perubahan status transaksi <b>{{ $data->agent_name }}</b>, <b>{{ $data->product_code }}</b> ke <b>{{ $data->receiver_phone_number }} {{ $data->status }}</b>.</p>
    <br>
    <p>Pesan untuk agent:</p>
    <p>{{ $data->statusRemark }}</p>
    <br>
    <p>Keterangan internal:</p>
    <p>{{ $data->internalRemark }}</p>
  </body>
</html>
