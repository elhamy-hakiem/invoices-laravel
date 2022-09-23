@extends('layouts.master')
@section('title')
تفاصيل الفاتورة
@stop
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/تفاصيل الفاتورة</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
                {{-- start alert message delete --}}
                @if (session()->has('delete'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('delete') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                {{-- End   alert   message delete --}}



                {{-- start alert message Error   --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- End   alert   message Error --}}

				<!-- row -->
				<div class="row row-sm">
					{{-- Start Invoice Details  --}}
					<div class="col-lg-8">
						<div class="card mg-b-20">
							<div class="card-body">
								<div class="pl-0">
									<div class="main-profile-overview" id="print">
										{{--Start invoice header  --}}
										<div class="row">
											<div class="col-lg-4">
												<h5 class="main-profile-name"># {{$invoice ->invoice_number}}</h5>
												<p class="main-profile-name-text"><i class="las la-user"></i> {{$invoiceData ->user}}</p>
											</div>
                                            <div class="col-lg-4">
												<h5 class="main-profile-name">القسم</h5>
                                                <p class="main-profile-name-text">{{$sectionName}}</p>
											</div>
                                            <div class="col-lg-4">
												<h5 class="main-profile-name">المنتج</h5>
                                                <p class="main-profile-name-text">{{$productName}}</p>
											</div>
										</div>
										{{--End invoice header  --}}
										<hr class="mg-y-20">

										{{--Start invoice Content  --}}
										<h6><i class="las la-edit"></i> الملاحظات</h6>
										<div class="main-profile-bio pb-2">
                                            {{$invoiceData ->note}}
										</div><!-- main-profile-bio -->
										<div class="row">
											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Amount_collection}}</h5>
												<h6 class="text-small text-muted mb-0">مبلغ التحصيل</h6>
											</div>

											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Amount_Commission}}</h5>
												<h6 class="text-small text-muted mb-0">مبلغ العمولة</h6>
											</div>
											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Discount}}</h5>
												<h6 class="text-small text-muted mb-0">الخصم</h6>
											</div>
										</div>

                                        <div class="row mt-3">
											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Rate_VAT}}</h5>
												<h6 class="text-small text-muted mb-0">نسبة ضريبة القيمة المضافة</h6>
											</div>
											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Value_VAT}}</h5>
												<h6 class="text-small text-muted mb-0">قيمة ضريبة القيمة المضافة</h6>
											</div>
											<div class="col-md-4 col mb20">
												<h5>{{$invoiceData ->Total}}</h5>
												<h6 class="text-small text-muted mb-0">الاجمالي شامل الضريبة</h6>
											</div>
										</div>
										{{--Start invoice Content  --}}
										<hr class="mg-y-30">

										<div class="row mt-3">
											<div class="col-md-3 col mb20">
												<h5>{{$invoiceData ->invoice_Date}}</h5>
												<h6 class="text-small text-muted mb-0">تاريخ الفاتورة</h6>
											</div>
											<div class="col-md-3 col mb20">
												<h5>{{$invoiceData ->Due_date}}</h5>
												<h6 class="text-small text-muted mb-0">تاريخ الاستحقاق</h6>
											</div>
											<div class="col-md-3 col mb20">
													@if ($invoiceData ->Status == 1)
														<h5 class="text-success"> <span style="margin-right: -6px" class=" pulse"></span>مدفوعة</h5>
													@elseif ($invoiceData ->Status == 0)
														<h5 class="text-danger"> <span style="margin-right: -6px" class=" pulse-danger"></span>غير مدفوعة</h5>
													@endif
												<h6 class="text-small text-muted mb-0">حالة الفاتورة</h6>
											</div>
                                            @if ($invoiceData ->payment_date != null)
                                                <div class="col-md-3 col mb20">
                                                    <h5>{{$invoiceData ->payment_date}}</h5>
                                                    <h6 class="text-small text-muted mb-0">تاريخ الدفع</h6>
                                                </div>
                                            @endif
										</div>

									</div><!-- main-profile-overview -->
								</div>
							</div>
                            <div class="card-footer text-right">
								<a href="{{ url('invoices/edit/'.$invoice->id) }}" class="btn btn-primary waves-effect waves-light">تعديل الفاتورة</a>
                                <button class="btn btn-danger " id="print_Button" onclick="printDiv()"> <i
                                    class="mdi mdi-printer ml-1"></i>طباعة
                                </button>
							</div>
						</div>
					</div>
					{{-- End Invoice Details  --}}

					{{-- Start Attachement Data  --}}
					<div class="col-lg-4 text-center">
						<div class="card mg-b-20">
							<div class="card-body">
								<div class="pl-0">
									<div class="main-profile-overview">
										<div class="main-img-user profile-user" style="width: 100%; height:100%;">
                                            <p>
												{{-- Start Check File Extension  --}}
												<?php $fileData = '';?>
                                                @if (!empty($invoiceAttach->file_name))
													<?php
														$fileData = $invoiceAttach->file_name;
														$fileArray = explode('.',$fileData);
														$fileExtension = Str::lower(end($fileArray));
													?>
													{{-- Start Pdf File Show  --}}
                                                    @if ($fileExtension == 'pdf')
                                                        <div class="row mt-3">
                                                            <div class="col-md-6 col mb20">
                                                                <h6>اسم الملف</h6>
                                                                <span class="text-small text-muted mb-0">{{$invoiceAttach->file_name}}</span>
                                                            </div>

                                                            <div class="col-md-6 col mb20">
                                                                <h6>تاريخ الاضافة</h6>
                                                                <span class="text-small text-muted mb-0">{{$invoiceAttach->created_at}}</span>
                                                            </div>
                                                        </div>
														<hr class="mg-t-20">

														<a class="btn btn-outline-success btn-sm"
															href="{{url('invoiceDetails/open_file/'.$invoice->invoice_number.'/'.$invoiceAttach->id)}}"
															role="button"><i class="fas fa-eye"></i>&nbsp;
															عرض
														</a>

														<a class="btn btn-outline-info btn-sm mr-2"
															href="{{url('Attachments/'.$invoice->invoice_number.'/'.$invoiceAttach->file_name)}}" role="button">
															<i class="fas fa-download"></i>&nbsp;
															تحميل
														</a>
													{{-- End Pdf File Show  --}}
													@else
														<img style="border-radius: 10%" alt="" src="{{url('Attachments/'.$invoice->invoice_number.'/'.$invoiceAttach->file_name)}}">
														<hr class="mg-t-20">
                                                    @endif
														<button class="btn btn-outline-danger btn-sm mr-2"
															data-toggle="modal"
															data-file_name="{{ $invoiceAttach->file_name }}"
															data-invoice_number="{{ $invoice->invoice_number }}"
															data-id_file="{{ $invoiceAttach->id }}"
															data-target="#delete_file">حذف</button>
												@else
														<div class="alert-danger">لايوجد مرفقات لعرضها</div>
                                                @endif
												{{-- End Check File Extension  --}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start delete Attachment -->
                    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?php echo url('/invoiceDetails/delete_file'); ?>" method="post">

                                    {{ csrf_field() }}
                                    <div class="modal-body">
                                        <p class="text-center">
                                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                                        </p>

                                        <input type="hidden" name="id_file" id="id_file" value="">
                                        <input type="hidden" name="file_name" id="file_name" value="">
                                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                                        <button type="submit" class="btn btn-danger">تاكيد</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End delete Attachment -->

					{{-- End Attachement Data  --}}

				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>

<script>
    $('#delete_file').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id_file = button.data('id_file')
        var file_name = button.data('file_name')
        var invoice_number = button.data('invoice_number')
        var modal = $(this)
        modal.find('.modal-body #id_file').val(id_file);
        modal.find('.modal-body #file_name').val(file_name);
        modal.find('.modal-body #invoice_number').val(invoice_number);
    })
</script>

<script type="text/javascript">
    function printDiv() {
        var printContents = document.getElementById('print').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

@endsection

