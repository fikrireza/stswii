<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <p>
      Berikut ini list paloma deposit balance.
    </p>

    <p>
      <table style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">
        <tr style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">
          <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">Partner Code</td>
          <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">Partner Name</td>
          <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">Balance Amount</td>
        </tr>
        @foreach ($data[0]['balanceAmount'] as $key)
          <tr style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">
            <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">{{ $key->partner_pulsa_code }}</td>
            <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">{{ $key->partner_pulsa_name }}</td>
            <td style="border: 1px solid black;border-collapse: collapse;font-size: 15px;">{{ number_format($key->balance_amount,'0',',','.') }}</td>
          </tr>
        @endforeach
      </table>
    </p>

  </body>
</html>
