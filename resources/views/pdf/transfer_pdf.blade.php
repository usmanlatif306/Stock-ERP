<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{asset('/css/transfer_pdf.css')}}" media="all" />
    <title>DELIVERY CHALLAN </title>

</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{asset('/images/'.$setting['logo'])}}">
        </div>
        <div id="company">
            <h4 class="delivery">DELIVERY CHALLAN (DC)</h4>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">To,</div>
                <h3 class="name">The Administrator</h3>
                <div class="name">{{$transfer['to_warehouse']}}</div>

            </div>
            <div id="invoice">
                <h4>DC Date: <span> {{$transfer['date']}} </span> </h4>
                <h4>DC No: &nbsp;&nbsp;&nbsp;&nbsp;15941 </h4>
                <h4>Purchase No: ________</h4>


            </div>
        </div>
        @php
        $grantTotal = 0;
        @endphp

        @foreach($details as $name=>$products)
        @php
        $itemTotal = 0;
        @endphp
        <h4 style="margin-bottom: 3px;padding: 0;">{{$name}}</h4>
        <table class="table-sm">
            <thead>
                <tr>
                    <th class="">Sized</th>
                    <th class="desc">Expiry</th>
                    <th>LotNo</th>
                    <th>PartNo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                @php
                $grantTotal += $item['quantity'];
                $itemTotal += $item['quantity'];
                @endphp
                <tr>
                    <td class="service">{{$item['code']}}</td>
                    <td class="desc">12-12-2025</td>
                    <td class="unit">1110571</td>
                    <td class="total">1001780</td>
                    <td class="total">{{$item['quantity']}}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="service"></td>
                    <td class="desc"></td>
                    <td class="unit"></td>
                    <td class="total" style="font-weight: bold;">Total</td>
                    <td class="total" style="font-weight: bold;">{{$itemTotal}}</td>
                </tr>
            </tbody>
        </table>
        @endforeach

        <!-- <div class="" style="margin-top: 20px;"> -->
        <hr />
        <hr />
        <!-- </div> -->

        <h3 class="totalgrand">Grand Total: {{$grantTotal}}</h3>
    </main>

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
</body>

</html>