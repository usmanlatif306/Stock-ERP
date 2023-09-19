<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Payment_{{$payment['Ref']}}</title>
   <link rel="stylesheet" href="{{asset('/css/pdf_style.css')}}" media="all" />
</head>

<body>
   <header class="clearfix">
      <div id="logo">
         <img src="{{asset('/images/'.$setting['logo'])}}">
      </div>
      <div id="company">
         <h4 class="delivery">Payment sale return</h4>
      </div>
      <div id="company1">
         <div><strong> Date: </strong>{{$payment['date']}}</div>
         <div><strong> Number: </strong> {{$payment['Ref']}}</div>
      </div>
      <!-- <div id="Title-heading">
         Payment {{$payment['Ref']}}
      </div> -->
      </div>
   </header>
   <main>
      <div id="details" class="clearfix">
         <div id="client">
            <table class="table-sm">
               <thead>
                  <tr>
                     <th class="desc">Customer Info</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>
                        <div><strong>Name:</strong> {{$payment['client_name']}}</div>
                        <div><strong>Phone:</strong> {{$payment['client_phone']}}</div>
                        <div><strong>Adress:</strong> {{$payment['client_adr']}}</div>
                        <div><strong>Email:</strong> {{$payment['client_email']}}</div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div id="invoice">
            <table class="table-sm">
               <thead>
                  <tr>
                     <th class="desc">Company Info</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td>
                        <div id="comp">{{$setting['CompanyName']}}</div>
                        <div><strong>Adress:</strong> {{$setting['CompanyAdress']}}</div>
                        <div><strong>Phone:</strong> {{$setting['CompanyPhone']}}</div>
                        <div><strong>Email:</strong> {{$setting['email']}}</div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      <div id="details_inv">
         <table class="table-sm">
            <thead>
               <tr>
                  <th>Return</th>
                  <th>Paid By</th>
                  <th>Amount</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>{{$payment['return_Ref']}}</td>
                  <td>{{$payment['Reglement']}}</td>
                  <td>{{$symbol}} {{$payment['montant']}} </td>
               </tr>
            </tbody>
         </table>
      </div>

      <div id="thanks">Thank you!</div>
      <div id="signature">
         <div class="row">
            <div class="column">
               <hr>
               <h2>Prepared By</h2>
            </div>
            <div class="column">
               <hr>
               <h2>Verified By</h2>
            </div>
            <div class="column">
               <hr>
               <h2>Received By</h2>
            </div>

         </div>
         <div class="row">
            <div class="column2">
               <hr>
            </div>
         </div>
         <div class="row">
            <div class="column1">
               <h4 style="margin: 0;padding: 0;">HEAD OFFICE</h4>
               <p> 121Habitat, Shadman II<br>
                  Ghaus-ul-Azam Road, Lahore-54000,Pakistan <br>
                  Ph: 92-42-37531712, Fax: 92-42-3753171</p>

            </div>
            <div class="column1">
               <h4 style="margin: 0;padding: 0;">KARACHI OFFICE</h4>
               <p>
                  Caesar's Tower, 503, 5th floor St-10 <br>
                  Opp. Aisha Bawani School, Shahra-E-Faisal Karachi <br>
                  Ph: 92-21-2788866, Fax: 92-21-278886</p>
            </div>
            <div class="column1">
               <h4 style="margin: 0;padding: 0;">RAWALPINDI OFFICE</h4>
               <p>
                  House No 15A, Valley Road <br>
                  Westrige, The Mall Rawalpindi <br>
                  Ph: 92-51-5462812, Fax: 92-51-5461851</p>
            </div>
         </div>
      </div>
   </main>
</body>

</html>