@extends('layouts.master')
@section('title','CREATE Invoice')
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
<!-- Internal Arabic Font td css-->
<link href="{{ asset('assets/plugins/datatable/css/arabic-font.css') }}" rel="stylesheet">
<!-- Internal input-elements due_date css -->
<link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Sheets</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/CREATE Invoice</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices_list.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        {{--row 1--}}
                        <div class="row">
                            <div class="col">
                                <label for="invoice_number" class="control-label">Invoice Number</label>
                                <input type="string" class="form-control" id="invoice_number" name="invoice_number"
                                    title="Enter Invoice Number" placeholder="Invoice Number" required>
                            </div>

                            <div class="col">
                                <label for="customer_name" class="control-label">Customer Name</label>
                                <input class="form-control arabic-text" id="customer_name" name="customer_name"
                                    type="string" title="Enter Customer Name" placeholder="Customer Name" required>
                            </div>

                            <div class="col">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" placeholder="YYYY-MM-DD" required>
                            </div>
                        </div>

                        {{--row 2 --}}
                        <div class="row mt-3">
                            <div class="col">
                                <label for="section" class="control-label">Section</label>
                                <select id="section" class="form-control arabic-text">
                                    <option selected >--Select Section--</option>
                                    @foreach ($sections as $section)
                                        <option class="arabic-text" value="{{$section->id}}"> {{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label for="product" class="control-label">Product</label>
                                <select id="product"  class="form-control arabic-text">
                                    <option class="arabic-text"> </option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col text-center">
                                <button type="button" id="add_to_table" class="btn btn-info">
                                    <i class="fas fa-plus"></i> Add to Table
                                </button>
                            </div>
                        </div>
                        
                        <table id="sectionProductTable" class="table table-bordered text-center mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sections</th>
                                    <th>Products</th>
                                    <th>Price</th>
                                    <th>Units</th>
                                    <th>Total Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>                                               
                        
                        {{--row 3 --}}
                        <div class="row mt-4">
                            <div class="col">
                                <label for="invoice_value" class="control-label">Invoice value</label>
                                <input type="decimal" id="invoice_value" name="invoice_value" value=0.00 class="form-control" title="Enter Invoice value"  readonly>
                            </div>

                            <div class="col">
                                <label for="discount" class="control-label">% Discount Percentage</label>
                                <input type="decimal" id="discount" value=0.00 class="form-control" title="Enter Discount Percentage"  required>
                            </div>

                            <div class="col">
                                <label for="vat" class="control-label">% VAT Percentage</label>
                                <input type="decimal" id="vat" value=0.00 class="form-control" title="Enter VAT Percentage" required>
                            </div>
                        </div>

                        {{--row 4 --}}
                        <div class="row mt-2">
                            <div class="col">
                                <label for="total_value" class="control-label">Total Invoice Value</label>
                                <input type="decimal" class="form-control" id="total_value" name="total_value" readonly>
                            </div>

                            <div class="col">
                                <label for="discount_value" class="control-label">Discount Value</label>
                                <input type="decimal" class="form-control" id="discount_value" name="discount_value" readonly>
                            </div>

                            <div class="col">
                                <label for="vat_value" class="control-label">Vat Value</label>
                                <input type="decimal" class="form-control" id="vat_value" name="vat_value" readonly>
                            </div>
                        </div>

                        <div class="col mt-4 text-center">
                            <div for="notes" class="control-label text-primary"><b>NOTES</b></div>
                            <textarea class="form-control arabic-text" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="col-sm-12 col-md-12 mt-4">
                            <div for="attaches" class="text-center text-primary"><b>ATTACHMENTS</b></div>
                            <input type="file" name="attaches[]" id="attaches" class="dropify" accept=".pdf,.jpg,.png,.image/jpeg,.image/png" data-height="200" multiple>
                            <div class="text-success"> format Attachments>> pdf, jpeg , jpg , png *</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" id="save" class="btn btn-primary">SAVE</button>
                        </div>
                    </form>
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

<!-- Internal input-elements due_date js -->
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/flatpickr/flatpickr-init.js') }}"></script>

<script>
    $(document).ready(function() {
        let rowCount = 0;
        let productsData = {};

        // ✅ تحميل المنتجات بناءً على القسم المختار
        $('#section').on('change', function() {
            const sectionId = $(this).val();
            if (sectionId) {
                $.ajax({
                    url: "{{ url('get_products') }}/" + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#product').empty().append('<option selected disabled>-- Select Product --</option>');
                        productsData = {};
                        $.each(data, function(index, product) {
                            productsData[product.id] = {
                                value: product.value,
                                quantity: product.quantity
                            };
                            $('#product').append('<option value="' + product.id + '">' + product.product_name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error("❌ Error loading products:", xhr.responseText);
                    }
                });
            } else {
                $('#product').empty();
            }
        });

        // ✅ عند الضغط على زر الإضافة
        $('#add_to_table').on('click', function() {
            const sectionId = $('#section').val();
            const sectionText = $('#section option:selected').text();
            const productId = $('#product').val();
            const productText = $('#product option:selected').text();

            if (!sectionId || !productId) {
                alert('⚠️ Please select both Section and Product before adding.');
                return;
            }

                // 🔍 تحقق من أن المنتج لم يُضاف سابقًا
            let productExists = false;
            $('#sectionProductTable tbody input[name="products[]"]').each(function() {
                if ($(this).val() == productId) {
                    productExists = true;
                    return false; // ← أوقف الحلقة فورًا
                }
            });

            if (productExists) {
                alert('⚠️ This product is already added to the invoice .');
                return;
            }

            const productInfo = productsData[productId];
            const price = parseFloat(productInfo.value) || 0;
            const maxQuantity = parseInt(productInfo.quantity) || 1;
            rowCount++;

            const row = `
                <tr data-row="${rowCount}">
                    <td>${rowCount}</td>
                    <td>
                        <span class="arabic-text">${sectionText}</span>
                        <input type="hidden" value="${sectionId}">
                    </td>
                    <td>
                        <span class="arabic-text">${productText}</span>
                        <input type="hidden" name="products[]" value="${productId}">
                    </td>
                    <td class="price-text">${price.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control units-input" name="units[]" value="1" min="1" max="${maxQuantity}" style="width: 90px; margin:auto;">
                    </td>
                    <td class="total-price-text">0.00</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger delete-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#sectionProductTable tbody').append(row);
            updateRowTotal($('#sectionProductTable tbody tr:last'));
            updateInvoiceValue();

            $('#section').val('');
            $('#product').empty().append('<option value=""> </option>');
        });

        // ✅ تحديث total price عند تغيير الوحدات
        $(document).on('input', '.units-input', function() {
            const row = $(this).closest('tr');
            updateRowTotal(row);
            updateInvoiceValue();
        });

        // ✅ حساب total price لكل صف
        function updateRowTotal(row) {
            const price = parseFloat(row.find('.price-text').text()) || 0;
            const units = parseInt(row.find('.units-input').val()) || 1;
            const totalPrice = price * units;
            row.find('.total-price-text').text(totalPrice.toFixed(2));
            row.attr('data-total', totalPrice);
        }

        // ✅ حذف صف
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            renumberRows();
            updateInvoiceValue();
        });

        // ✅ إعادة ترقيم الصفوف (#)
        function renumberRows() {
            let index = 1;
            $('#sectionProductTable tbody tr').each(function() {
                $(this).find('td:first').text(index);
                index++;
            });
            rowCount = index - 1;
        }

        // ✅ تحديث إجمالي الفاتورة
        function updateInvoiceValue() {
            let total = 0;
            $('#sectionProductTable tbody tr').each(function() {
                const totalPrice = parseFloat($(this).attr('data-total')) || 0;
                total += totalPrice;
            });
            $('#invoice_value').val(total.toFixed(2));
            $('#total_value').val(total.toFixed(2)); // تحديث الحقل النهائي أيضًا
            calculateTotals();
        }

        // ✅ حساب الخصم والقيمة المضافة والإجمالي
        function calculateTotals() {
            const invoiceValue = parseFloat($('#invoice_value').val()) || 0;
            const discountPercent = parseFloat($('#discount').val()) || 0;
            const vatPercent = parseFloat($('#vat').val()) || 0;

            const discountValue = (invoiceValue * discountPercent) / 100;
            $('#discount_value').val(discountValue.toFixed(2));

            const afterDiscount = invoiceValue - discountValue;

            const vatValue = (afterDiscount * vatPercent) / 100;
            $('#vat_value').val(vatValue.toFixed(2));

            const totalValue = afterDiscount + vatValue;
            $('#total_value').val(totalValue.toFixed(2));
        }

        // ✅ إعادة الحساب عند تغيير الخصم أو الضريبة
        $('#discount, #vat').on('input', function() {
            calculateTotals();
        });

        // ✅ تأكيد حفظ الفاتورة قبل الإرسال
        $('#save').on('click', function(event) {
            event.preventDefault(); // منع الإرسال مؤقتًا

            // استخدم confirm لإظهار رسالة تأكيد
            const confirmed = confirm('⚠️ Are you sure you want to SAVE the Invoice ?');

            if (confirmed) {
                // إذا اختار المستخدم "Yes" → أرسل الفورم
                $(this).closest('form').submit();
            } else {
                // إذا اختار "Cancel" → لا تفعل شيئًا
                return false;
            }
        });
    });
</script>
@endsection