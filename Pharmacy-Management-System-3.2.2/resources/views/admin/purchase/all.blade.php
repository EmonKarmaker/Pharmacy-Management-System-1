@extends('layouts.admin.dashboard')
@section('contents')
@if(Session::has('success'))
<script>
Swal.fire({
    position: 'center',
    icon: 'success',
    text: '{{Session::get('success')}}',
    showConfirmButton: true,
    timer: '5000',
})
</script>
@endif
@if(Session::has('error'))
<script>
Swal.fire({
    position: 'center',
    icon: 'error',
    text: '{{Session::get('error')}}',
    showConfirmButton: true,
    timer: '5000',
})

</script>

@endif


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="card-title"><i class="fas fa-hamburger"></i><b>All Purchase Information</b></h4>
                        </div>
                        <div class="col-md-4 text-right">
                        </div>
                    </div>
                </div>


                <div class="card-body">

                <div class="table-responsive">
                    <table id="alltableinfo" class="table table-bordered table-striped table-hover dt-responsive">
                    <thead class="text-white" style="background-color:orange;">
                        <tr>
                            <th>#</th>
                            <th>Challan Number</th>
                            <th>Bill Number</th>
                            <th>Purchase Date</th>
                            <th>Purchase Type</th>
                            <th>Supplier</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Photo</th>
                            <th>Manage</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $c=1;
                        @endphp
                        @foreach($PurchaseInfo as $data)
                        <tr>
                            <td>{{$c++}}</td>
                            <td>{{$data->challan_number}}</td>
                            <td>{{$data->bill_number}}</td>
                            <td>{{$data->purchase_date}}</td>
                            <td>{{$data->purchase_type}}</td>
                            <td>{{$data->supplier->name ?? 'N\A'}}</td>
                            <td>{{$data->product->product_name ?? 'N\A'}}</td>
                            <td>{{$data->product_quantity}}</td>
                            <td>{{ number_format($data->product_price_per_unit ,2)}} Tk</td>
                            <td>{{ number_format($data->product_total_price ,2)}} Tk</td>
                            <td>
                                @if($data->purchase_info_photo!='')
                                    <img src="{{asset('uploads/purchase-info/'.$data->purchase_info_photo)}}" alt="Purchase Info photo" class="img-fluid" height="65px" width="65px">
                                @else
                                    <!-- <img src="{{asset('contents/admin/assets')}}/img/avatar.png" alt="User photo" class="img-fluid" height="65px" width="65px"> -->
                                @endif
                            </td>
                            <td class="d-flex">
                                
                                
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

</div>
@endsection



