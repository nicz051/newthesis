@extends('layouts.index')

@section('content')
    

    <div class="container" style="margin-top: 50px">   
        <div class="row" style="height: 525px; overflow-y: auto; overflow-x: hidden;">
            <div class="col-md-12">

            
            <table id= "notif" class="table table-sucess" >
                @foreach($notifications as $notification)
                    <tr  class="table-success">
                        <td  style="text-align: right;"><img src="/images/CHE.png" style="width: 40px; height: 40px;"> Account Number  <strong>{{$notification->accountnum}}</strong></td>
                        @if($notification->status == 1)
                            <td><strong>Reconnection    </strong>was Successful</td>
                        @elseif($notification->status == 0)
                            <td><strong>Disconnection</strong>was Successful</td>
                        @endif
                        <td  style="text-align: left;">{{$notification->created}}</td>
                    </tr>
                @endforeach
    
        







@endsection