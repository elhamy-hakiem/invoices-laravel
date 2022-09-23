@extends('layouts.master')
@section('title')
    الفواتير المؤرشفة
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
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  الفواتير المؤرشفة</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection

{{-- Start Invoices data  --}}
@section('content')
        {{-- start Restore Error  --}}
        @if (session()->has('restore_error'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "حدث خطأ في استرجاع الفاتورة ",
                        type: "danger"
                    })
                }
            </script>
        @endif
        {{-- End Restore Error  --}}

        {{-- start Restore Invoice  --}}
        @if (session()->has('restore_invoice'))
            <script>
                window.onload = function() {
                    notif({
                        msg: "تم استرجاع الفاتورة بنجاح",
                        type: "success"
                    })
                }
            </script>
        @endif
        {{-- End Restore Invoice  --}}

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

                                                    {{-- start Restore button  --}}
                                                    <a class="modal-effect btn btn-sm btn-primary" href="#"
                                                    data-effect="effect-scale" data-target="#restore_invoice"
                                                    data-invoice_id ="{{ $invoice->id }}"
                                                    data-toggle="modal"  title="الغاء الارشفة">
                                                    <i class="las la-window-restore"></i>
                                                    </a>
                                                    {{-- End Restore button --}}

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

        <!-- استرجاع الفاتورة -->
    <div class="modal fade" id="restore_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">الغاء ارشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('invoicesArchived/update') }}" method="post">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        هل انت متاكد من عملية الغاء الارشفة ؟
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- حذف الفاتورة -->
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
                <form action="{{ url('invoicesArchived/destroy') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        هل انت متاكد من عملية الحذف ؟
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
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
    $('#restore_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
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
