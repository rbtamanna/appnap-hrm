@extends('backend.layouts.master')
@section('page_action')
    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a class="link-fx" href="{{ url('branch') }}">Branches</a></li>
            <li class="breadcrumb-item">Update</li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="content">
        <div class="block block-rounded block-content col-sm-6">
        @include('backend.layouts.error_msg')
            <div class="block-header">
                <h3 class="block-title">Update Branch</h3>
            </div>
            <!-- jQuery Validation (.js-validation class is initialized in js/pages/be_forms_validation.min.js which was auto compiled from _js/pages/be_forms_validation.js) -->
            <form class="js-validation" action="{{ route('branch.update', $data->id) }}" method="POST" onsubmit="return verify_inputs()">
                @csrf
                @method('patch')
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <div class="row items-push">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label for="val-title">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}">
                                    <span id="error_name" style="font-size:13px; color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="val-description">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" rows="3" value="{{ $data->address }}">
                                </div>
                            </div>
                        </div>

                        <!-- Update -->
                        <div class="row items-push">
                            <div class="col-lg-5 offset-lg-5">
                                <button type="submit" class="btn btn-alt-primary" id="submit">Update</button>
                            </div>
                        </div>
                        <!-- END Update -->
                    </div>
                </div>
            </form>
            <!-- jQuery Validation -->
        </div>
    </div>
@endsection

@section('js_after')
    <script src="{{ asset('backend/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('backend/js/pages/be_forms_validation.min.js') }}"></script>

    <script>
        function verify_inputs(e){
            let name = $('#name').val();
            var current_name = " <?php echo $data->name; ?>"
            let flag = 0;
            $.ajax({
                type: 'PATCH',
                async:false,
                url: '{{ route("updatedata") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    current_name: current_name
                },
                context: this,
                success: function(response) {
                    if (!response.success) {
                        document.getElementById('error_name').innerHTML = response.name_msg;
                        flag = 0;
                    }
                    else{
                        flag = 1;
                    }
                },
            });
            if(!flag)
                return false;
            else{
                $('#submit').attr('disabled', true);
                return true;
            }
        }
    </script>
@endsection
