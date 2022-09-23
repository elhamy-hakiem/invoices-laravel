@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    تعديل الفاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تعديل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- Start Content --}}
@section('content')

    {{-- start Session add Alert  --}}
    @if (session()->has('update'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('update') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{-- End Session add Alert  --}}

    {{-- start alert message Error --}}
    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{-- End   alert   message Error --}}



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
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('invoices/update') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{-- 1 --}}
                        <input type="hidden" name="invoiceId" id="id" value="{{ $invoice ->id }}">
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="inputName" name="invoice_number"
                                    title="يرجي ادخال رقم الفاتورة"  value="{{ $invoice ->invoice_number}}">
                            </div>

                            <div class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker" name="invoice_Date"
                                    type="text" value="{{ $invoiceDetails ->invoice_Date }}" >
                            </div>

                            <div class="col">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker" name="Due_date"
                                    type="text" value="{{ $invoiceDetails ->Due_date }}" >
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">القسم</label>
                                <select id="Section" name="section_id" class="form-control SlectBox"
                                    value="{{ $invoiceDetails ->section_id }}"
                                    data-productId ="{{ $invoiceDetails ->product }}">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" @if ( $invoiceDetails ->section_id  == $section->id ) {{ 'selected' }} @endif> {{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">المنتج</label>
                                <select id="product" name="product" class="form-control">
                                </select>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="inputName" name="Amount_collection" value="{{ $invoiceDetails ->Amount_collection }}"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row">

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                    name="Amount_Commission" title="يرجي ادخال مبلغ العمولة "
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value="{{ $invoiceDetails ->Amount_Commission }}">
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="Discount" name="Discount"  value="{{ $invoiceDetails ->Discount }}"
                                    title="يرجي ادخال مبلغ الخصم "
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    value=0 >
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="Rate_VAT" id="Rate_VAT" class="form-control" onchange="myFunction()" value="{{ $invoiceDetails ->Rate_VAT }}">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value="5%" @if ( $invoiceDetails ->Rate_VAT  == "5%") {{ 'selected' }} @endif>5%</option>
                                    <option value="10%" @if ( $invoiceDetails ->Rate_VAT == "10%") {{ 'selected' }} @endif>10%</option>
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="Value_VAT" name="Value_VAT" readonly value="{{ $invoiceDetails ->Value_VAT }}">
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="Total" name="Total" readonly value="{{ $invoiceDetails ->Total }}">
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea" name="note" rows="3">
                                    {{$invoiceDetails ->note}}
                                </textarea>
                            </div>
                        </div><br>

                        <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                        <h5 class="card-title">المرفقات</h5>
                        <div class="col-sm-12 col-md-12" style="border: 1px solid #e1e5ef; padding-bottom: 13px;">
                            {{-- Start Check File Extension  --}}
                                <?php $fileData = '';?>
                                @if (!empty($invoiceAttachments->file_name))
                                    <?php
                                        $fileData = $invoiceAttachments->file_name;
                                        $fileArray = explode('.',$fileData);
                                        $fileExtension = Str::lower(end($fileArray));
                                    ?>
                                    {{-- Start Pdf File Show  --}}
                                    @if ($fileExtension == 'pdf')
                                        <div class="row mt-3">
                                            <div class="col-md-6 col mb20">
                                                <h6>اسم الملف</h6>
                                                <span class="text-small text-muted mb-0">{{$invoiceAttachments->file_name}}</span>
                                            </div>

                                            <div class="col-md-6 col mb20">
                                                <h6>تاريخ الاضافة</h6>
                                                <span class="text-small text-muted mb-0">{{$invoiceAttachments->created_at}}</span>
                                            </div>
                                        </div>
                                        <hr class="mg-t-20">

                                        <a class="btn btn-outline-success btn-sm"
                                            href="{{url('invoiceDetails/open_file/'.$invoice->invoice_number.'/'.$invoiceAttachments->id)}}"
                                            role="button"><i class="fas fa-eye"></i>&nbsp;
                                            عرض
                                        </a>
                                    {{-- End Pdf File Show  --}}
                                    @else
                                        <img style="border-radius: 10% ; height: 400px; width: 400;" alt="" src="{{url('Attachments/'.$invoice->invoice_number.'/'.$invoiceAttachments->file_name)}}">
                                        <hr class="mg-t-20">
                                    @endif
                                @else
                                        <div class="alert-danger">لايوجد مرفقات لعرضها</div>
                                @endif
                            {{-- End Check File Extension  --}}
                        </div><br>

                        <div class="col-sm-12 col-md-12">
                            <input type="file" name="pic" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png"
                                data-height="70"/>
                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">تعديل البيانات</button>
                        </div>
                    </form>
                    
            </div>
        </div>
    </div>
</div>

</div>

<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Select2 js-->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Fileuploads js-->
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<!--Internal Fancy uploader js-->
<script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
<!--Internal  Form-elements js-->
<script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
<script src="{{ URL::asset('assets/js/select2.js') }}"></script>
<!--Internal Sumoselect js-->
<script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>

{{-- Start Ajax Code to get section products  --}}
<script>
    $(document).ready(function() {
        var Section = document.getElementById('Section');
        var SectionId = Section.value;
        var productId = Section.dataset.productid;
        var selectValue ='';
        if (SectionId) {
            $.ajax({
                url: "{{ URL::to('section') }}/" + SectionId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[name="product"]').empty();
                    $.each(data, function(key, value) {
                        if(key == productId)
                        {
                            selectValue = 'selected';
                            $('select[name="product"]').append('<option value="' +
                            key + '" '+selectValue+'>' + value +'</option>');
                        }
                        else
                        {
                            $('select[name="product"]').append('<option value="' +
                            key + '">' + value +'</option>');
                        }
                    });
                },
            });
        } else {
            console.log('AJAX load did not work');
        }
    });


    $(document).ready(function() {
        $('select[name="section_id"]').on('change', function() {
            var SectionId = $(this).val();
            if (SectionId) {
                $.ajax({
                    url: "{{ URL::to('section') }}/" + SectionId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="product"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                key + '">' + value +'</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>
{{-- End Ajax Code to get section products  --}}

<script>
    function myFunction ()
    {
        var Amount_Commission  = parseFloat(document.getElementById("Amount_Commission").value);
        var Discount           = parseFloat(document.getElementById("Discount").value);
        var Rate_VAT           = parseFloat(document.getElementById("Rate_VAT").value);
        var Value_VAT          = parseFloat(document.getElementById("Value_VAT").value);

        if(typeof Amount_Commission === 'undefined' || !Amount_Commission)
        {
            alert('يرجي ادخال مبلغ العمولة ');
        }
        else
        {
            var Amount_Commission2 = Amount_Commission - Discount;
            var intResults         = Amount_Commission2 * Rate_VAT / 100 ;
            var intResults2        = parseFloat(intResults + Amount_Commission2);

            sumq                   = parseFloat(intResults).toFixed(2);
            sumt                   = parseFloat(intResults2).toFixed(2);

            document.getElementById("Value_VAT").value = sumq;
            document.getElementById("Total").value = sumt;
        }
    }
</script>

@endsection
