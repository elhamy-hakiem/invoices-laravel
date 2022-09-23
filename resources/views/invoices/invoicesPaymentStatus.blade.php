@extends('layouts.master')
@section('title')
قائمة الفواتير
@stop
{{-- Start CSS Files  --}}
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
{{-- End CSS Files  --}}

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection

{{-- Start Invoices data  --}}
@section('content')

        {{-- start Payment Invoice  --}}
        @if (session()->has('error_invoice'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "حدث خطأ في تحديث حالة دفع الفاتورة ",
                        type: "danger"
                    })
                }
            </script>
        @endif
        {{-- End Payment Invoice  --}}

        {{-- start Payment Invoice  --}}
        @if (session()->has('payment_invoice'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "تم تحديث حالة دفع الفاتورة بنجاح",
                        type: "success"
                    })
                }
            </script>
        @endif
        {{-- End Payment Invoice  --}}

        {{-- start Archive Invoice  --}}
        @if (session()->has('archive_invoice'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "تم ارشفة الفاتورة بنجاح",
                        type: "success"
                    })
                }
            </script>
        @endif
        {{-- End Archive Invoice  --}}

        {{-- start Delete Invoice  --}}
        @if (session()->has('delete_invoice'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "تم حذف الفاتورة بنجاح",
                        type: "success"
                    })
                }
            </script>
        @endif
        {{-- End Delete Invoice  --}}

        <!-- row -->
        <div class="row">
                <!--div-->
                <div class="col-xl-12">
                    <div class="card mg-b-20">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table key-buttons text-md-nowrap text-center">
                                    {{-- Start Table Header  --}}
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">رقم الفاتورة</th>
                                            <th class="border-bottom-0">حالة الفاتورة</th>
                                            @foreach ($invoices as $invoice )
                                                @if ($invoice ->payment_date != null)
                                                    <th class="border-bottom-0">تاريخ الدفع</th>
                                                @endif
                                            @endforeach
                                            <th class="border-bottom-0">العمليات</th>
                                        </tr>
                                    </thead>
                                    {{-- End Table Header  --}}

                                    {{-- Start Table Body  --}}
                                    <tbody>
                                        @foreach ($invoices as $invoice )
                                            <tr>
                                                <td>{{$invoice ->id}}</td>
                                                <td>{{$invoice ->invoice_number}}</td>
                                                <td>
                                                    @if ($invoice ->Status == 1)
                                                        <span style="font-size: 13px !important;" class="badge badge-pill badge-success">مدفوعة</span>
                                                    @elseif ($invoice ->Status == 0)
                                                        <span style="font-size: 13px !important;" class="badge badge-pill badge-danger">غير مدفوعة</span>
                                                    @endif
                                                </td>
                                                @if ($invoice ->payment_date != null)
                                                    <td>{{$invoice ->payment_date}}</td>
                                                @endif
                                                <td>
                                                    {{-- start View button  --}}
                                                    <a class="btn btn-sm btn-success" title="عرض" href="{{ url('invoiceDetails/' . $invoice ->id) }}">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                    {{-- End View button  --}}

                                                    {{-- start update button  --}}
                                                    <a class="btn btn-sm btn-info" title="تعديل" href="{{ url('invoices/edit/' . $invoice ->id) }}">
                                                        <i class="las la-pen"></i>
                                                    </a>
                                                    {{-- End update button  --}}

                                                    {{-- start payment button  --}}
                                                    <a class="modal-effect btn btn-sm btn-success" href="#"
                                                        data-effect="effect-scale" data-target="#payment_invoice"
                                                        data-invoice_id ="{{ $invoice ->id }}"
                                                        data-invoice_payment ="{{ $invoice ->Status }}"
                                                        data-toggle="modal"  title="حالة الدفع">
                                                        <i class="las la-money-bill"></i>
                                                    </a>
                                                    {{-- End payment button --}}

                                                    {{-- start Delete button  --}}
                                                    <a class="modal-effect btn btn-sm btn-danger" href="#"
                                                        data-effect="effect-scale" data-target="#delete_invoice"
                                                        data-invoice_id ="{{ $invoice->id }}"
                                                        data-toggle="modal"  title="حذف">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                    {{-- End Delete button --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    {{-- End Table Body  --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/div-->
        </div>
        <!-- row closed -->
    </div>
        <!-- Container closed -->
</div>
    <!-- main-content closed -->

    <!-- ارشيف الفاتورة -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('invoices/destroy') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        اختار الاجراء الذي تريد تفيذه
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">

                        <div class="row mg-t-10">
                            <div class="col-lg-6">
                                <label class="rdiobox"><input checked name="deleteOption" type="radio" value="1"> <span>ارشفة الفاتورة</span></label>
                            </div>
                            <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                                <label class="rdiobox"><input name="deleteOption" type="radio" value="2"> <span>حذف نهائي</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- تغيير حالة دفع الفاتورة -->
    <div class="modal fade" id="payment_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تغيير حالة دفع الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('invoices/changePayment') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        اختار الاجراء الذي تريد تفيذه
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">

                        <div class="row mg-t-10">
                            <div class="col-lg-6">
                                <label class="rdiobox">
                                <input id="paid" name="paymentStatus" type="radio" value="1"> <span>الفاتورة مدفوعة</span></label>
                            </div>
                            <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                                <label class="rdiobox">
                                <input id="unPaid" name="paymentStatus" type="radio" value="0"> <span>الفاتورة غير مدفوعة </span></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
{{-- End Invoices data  --}}

{{-- Start Javascript Files  --}}
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<script>
    $('#payment_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var invoice_payment = button.data('invoice_payment')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
        if(invoice_payment == 0)
        {
            modal.find('.modal-body #unPaid').attr('checked', 'checked');
        }
        else if (invoice_payment == 1)
        {
            modal.find('.modal-body #paid').attr('checked', 'checked');
        }
    })
</script>
<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>

@endsection
