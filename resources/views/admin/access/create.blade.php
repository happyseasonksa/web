@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
          <!-- left column -->
          <div class="col-md-12" style="text-align: right;">
            <!-- jquery validation -->
            <div class="card card-primary" style="text-align: right;">
                <div class="card-header">
                    <h3 class="card-title" style="float: right">{{__('sidebar.Add New Access')}}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{route('admin.access.store')}}" role="form" enctype="multipart/form-data" id="add-customer">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                          <label for="name">{{__('Name')}}</label>
                          <input type="text" class="form-control @error('name'){{'is-invalid'}}@enderror" name="name" value="{{ old('name') }}" placeholder="{{__('Full name')}}">
                          @error('name')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="email">{{__('Email')}}</label>
                          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email'){{'is-invalid'}}@enderror" placeholder="{{__('Email')}}">
                          @error('email')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('email') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="phone">{{__('Phone')}}</label>
                          <input type="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone'){{'is-invalid'}}@enderror" placeholder="{{__('Phone')}}">
                          @error('phone')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('phone') }}</span>
                          @enderror
                        </div>
{{--                        <div class="form-group" style="display: none">--}}
{{--                            @php--}}
{{--                            $typesOfAdmins = typeOfAdmins();--}}
{{--                            if (Auth::user()->type === 4 || Auth::user()->type === 5) {--}}
{{--                              unset($typesOfAdmins[4]);--}}
{{--                              unset($typesOfAdmins[5]);--}}
{{--                            }--}}
{{--                            @endphp--}}
{{--                            <label for="type">{{__('Type')}}</label>--}}
{{--                            <select name="type" class="custom-select @error('type'){{'is-invalid'}}@enderror" id="type">--}}
{{--                              @foreach($typesOfAdmins as $key => $type)--}}
{{--                                <option @if(1 == $key) selected @endif value="{{$key}}">{{$type}}</option>--}}
{{--                              @endforeach--}}
{{--                            </select>--}}
{{--                            @error('type')--}}
{{--                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('type') }}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="status">{{__('Status')}}</label>
                            <select name="status" class="custom-select @error('status'){{'is-invalid'}}@enderror" id="status">
                              <option @if(old('status') == 'true') selected @endif value="true">{{__('Active')}}</option>
                              <option @if(old('status') == 'false') selected @endif value="false">{{__('InActive')}}</option>
                            </select>
                            @error('status')
                                <span id="name-error" class="error invalid-feedback">{{ $errors->first('status') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                          <label for="password">{{__('Password')}}</label>
                          <input type="password" name="password" id="password" required autocomplete="new-password" class="form-control @error('password'){{'is-invalid'}}@enderror" placeholder="{{__('Password')}}">
                          @error('password')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password') }}</span>
                          @enderror
                        </div>
                        <div class="form-group">
                          <label for="password_confirmation">{{__('Confirm Password')}}</label>
                          <input type="password" class="form-control @error('password_confirmation'){{'is-invalid'}}@enderror" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('Confirm Password')}}">
                          @error('password_confirmation')
                              <span id="name-error" class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                          @enderror
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="card card-primary" style="text-align: right;">
                            <div class="card-header">
                              <h3 class="card-title" style="float: right;">{{__('Access Management')}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <div class="row">
                              <div class="col-md-6 form-group clearfix access-container" style="display: none;">
                                <label class="col-md-12">{{__('Access')}}</label>
                                <div class="icheck-primary d-inline ml-3">
                                    <span><input type="checkbox" id="access-view" name="access[view]" value="1"  ></span>
                                  <label for="access-view">
                                    {{__('View')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="access-add" name="access[add]" value="1" ></span>
                                  <label for="access-add">{{__('Add')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="access-update" name="access[update]" value="1" ></span>
                                  <label for="access-update">{{__('Update')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="access-delete" name="access[delete]" value="1" ></span>
                                  <label for="access-delete">{{__('Delete')}}
                                  </label>
                                </div>
                              </div>
                              <div class="col-md-6 form-group clearfix customer-container" style="display: none;">
                                <label class="col-md-12">{{__('Customer')}}</label>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="customer-view" name="customer[view]" value="1"></span>
                                  <label for="customer-view">
                                    {{__('View')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="customer-add" name="customer[add]" value="1"></span>
                                  <label for="customer-add">{{__('Add')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="customer-update" name="customer[update]" value="1"></span>
                                  <label for="customer-update">{{__('Update')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="customer-delete" name="customer[delete]" value="1"></span>
                                  <label for="customer-delete">{{__('Delete')}}
                                  </label>
                                </div>
                              </div>
                              <div class="col-md-6 form-group clearfix category-container" style="display: none;">
                                <label class="col-md-12">{{__('Categories')}}</label>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="category-view" name="category[view]" value="1"  ></span>
                                  <label for="category-view">
                                    {{__('View')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="category-add" name="category[add]" value="1" ></span>
                                  <label for="category-add">{{__('Add')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="category-update" name="category[update]" value="1" ></span>
                                  <label for="category-update">{{__('Update')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="category-delete" name="category[delete]" value="1" ></span>
                                  <label for="category-delete">{{__('Delete')}}
                                  </label>
                                </div>
                              </div>
                              <div class="col-md-6 form-group clearfix item-container" style="display: none;">
                                <label class="col-md-12">{{__('Items')}}</label>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="item-view" name="item[view]" value="1" disabled checked></span>
                                  <label for="item-view">
                                    {{__('View')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="item-add" name="item[add]" value="1" checked></span>
                                  <label for="item-add">{{__('Add')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="item-update" name="item[update]" value="1" checked></span>
                                  <label for="item-update">{{__('Update')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="item-delete" name="item[delete]" value="1" checked></span>
                                  <label for="item-delete">{{__('Delete')}}
                                  </label>
                                </div>
                              </div>
                              <div class="col-md-6 form-group clearfix ads-container" style="display: none;">
                                <label class="col-md-12">{{__('Ads')}}</label>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="ads-view" name="ads[view]" value="1" disabled checked></span>
                                  <label for="ads-view">
                                    {{__('View')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="ads-add" name="ads[add]" value="1"></span>
                                  <label for="ads-add">{{__('Add')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="ads-update" name="ads[update]" value="1" checked></span>
                                  <label for="ads-update">{{__('Update')}}
                                  </label>
                                </div>
                                <div class="icheck-primary d-inline ml-3">
                                  <span><input type="checkbox" id="ads-delete" name="ads[delete]" value="1"></span>
                                  <label for="ads-delete">{{__('Delete')}}
                                  </label>
                                </div>
                              </div>
                              <div class="col-md-6 form-group clearfix city-container" style="display: none;">
                                      <label class="col-md-12">{{__('City')}}</label>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="city-view" name="city[view]" value="1"  ></span>
                                          <label for="city-view">
                                              {{__('View')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="city-add" name="city[add]" value="1"></span>
                                          <label for="city-add">{{__('Add')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="city-update" name="city[update]" value="1" ></span>
                                          <label for="city-update">{{__('Update')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="city-delete" name="city[delete]" value="1"></span>
                                          <label for="city-delete">{{__('Delete')}}
                                          </label>
                                      </div>
          </div>
                              <div class="col-md-6 form-group clearfix page-container" style="display: none;">
                                      <label class="col-md-12">{{__('CMS Page')}}</label>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="page-view" name="page[view]" value="1"  ></span>
                                          <label for="page-view">
                                              {{__('View')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="page-add" name="page[add]" value="1"></span>
                                          <label for="page-add">{{__('Add')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="page-update" name="page[update]" value="1" ></span>
                                          <label for="page-update">{{__('Update')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="page-delete" name="page[delete]" value="1"></span>
                                          <label for="page-delete">{{__('Delete')}}
                                          </label>
                                      </div>
                                  </div>
                              <div class="col-md-6 form-group clearfix report-container" style="display: none;">
                                      <label class="col-md-12">{{__('Reports')}}</label>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="report-view" name="report[view]" value="1" disabled checked></span>
                                          <label for="report-view">
                                              {{__('View')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="report-add" name="report[add]" value="1"></span>
                                          <label for="report-add">{{__('Add')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="report-update" name="report[update]" value="1" checked></span>
                                          <label for="report-update">{{__('Update')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="report-delete" name="report[delete]" value="1"></span>
                                          <label for="report-delete">{{__('Delete')}}
                                          </label>
                                      </div>
                                  </div>
                              <div class="col-md-6 form-group clearfix review-container" style="display: none;">
                                      <label class="col-md-12">{{__('Reviews')}}</label>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="review-view" name="review[view]" value="1" disabled checked></span>
                                          <label for="review-view">
                                              {{__('View')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="review-add" name="review[add]" value="1"></span>
                                          <label for="review-add">{{__('Add')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="review-update" name="review[update]" value="1" checked></span>
                                          <label for="review-update">{{__('Update')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="review-delete" name="review[delete]" value="1"></span>
                                          <label for="review-delete">{{__('Delete')}}
                                          </label>
                                      </div>
                                  </div>
                              <div class="col-md-6 form-group clearfix setting-container" style="display: none;">
                                      <label class="col-md-12">{{__('Settings')}}</label>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="setting-view" name="setting[view]" value="1"  ></span>
                                          <label for="setting-view">
                                              {{__('View')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="setting-add" name="setting[add]" value="1"></span>
                                          <label for="setting-add">{{__('Add')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="setting-update" name="setting[update]" value="1" ></span>
                                          <label for="setting-update">{{__('Update')}}
                                          </label>
                                      </div>
                                      <div class="icheck-primary d-inline ml-3">
                                          <span><input type="checkbox" id="setting-delete" name="setting[delete]" value="1"></span>
                                          <label for="setting-delete">{{__('Delete')}}
                                          </label>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('js-body')
<!-- jquery-validation -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var multipleRest = false;
    var assignRest = $("select#assign_restaurants option");
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).closest("div.entry").find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

    $('.card').on('change', "input[type='file']", function () {
        readURL(this);
    });

    $('select#type').on('change', function() {
      // show access module
      if (this.value && this.value != ""){
          showAccessContainers(parseInt(this.value));
      }
      // show access module END
      if (this.value && this.value != "" && (this.value == "3" || this.value == "4" || $('select#type').val() == "5")) {
          multipleRest = true;
      }else{
        multipleRest = false;
      }
    });

    // multi-branch

    // access management checkbox checked
    $(document).on('change', "input[type='checkbox']", function() {
        var checkBoxId = $(this).attr('id');
        var split = checkBoxId.split("-");
        var entity = split[0];
        var type = split[1];
        var addCheckBox = $(this).parents('.form-group').find('#'+entity+'-view');
        if(this.checked && (type == 'update' || type == 'delete')) {
          addCheckBox.prop('checked',true);
          addCheckBox.prop('disabled',true);
        }else if(type == 'update' || type == 'delete'){
          var allChecked = false;
          if (type == 'update') {
            if ($(this).parents('.form-group').find('#'+entity+'-delete').is(":checked")){
              allChecked = true;
            }
          }else{
            if ($(this).parents('.form-group').find('#'+entity+'-update').is(":checked")){
              allChecked = true;
            }
          }
          if(!allChecked){
            addCheckBox.prop('disabled',false);
          }
        }
    });
    // access management checkbox checked ENDS

    $('select#assign_restaurants').on('change', function() {
      if (this.value && this.value != "") {
        if (!multipleRest)
          disableAssignRest();
      }else{
        disableAssignRest(false);
      }
    });
    showAccessContainers(1);
    function showAccessContainers(type) {
      hideAllAccessContainers();
      var openArr = [];
      switch(type) {
        case 1:
          openArr=['access', 'customer', 'category','item','ads', 'city','page', 'report', 'review', 'setting'];
          break;
        case 2:
          openArr=['restaurant','table','order'];
          break;
        case 3:
          openArr=['restaurant','category','subcategory','item','inventory','order'];
          break;
        case 4:
          openArr=['restaurant','access','customer','order'];
          break;
        case 5:
          openArr=['restaurant','access','customer','order'];
          break;
      }
      openArr.forEach((value, index) => {
        $("."+value+"-container").show();
      });
    }

    function hideAllAccessContainers() {
      var hideArr = ['access', 'customer', 'category','item','ads', 'city','page', 'report', 'review', 'setting'];
      hideArr.forEach((value, index) => {
        $("."+value+"-container").hide();
      });
    }

    function disableAssignRest(type= true) {
      if (type) {
        assignRest.not(':selected').each(function (index) {
          $(this).prop('disabled', true);
        });
      }else{
        assignRest.each(function (index) {
          $(this).prop('disabled', false);
        });
      }
    }

    $(document).ready(function () {
      $('#status').select2({
          dir: "rtl",
          dropdownAutoWidth: true,
          dropdownParent: $('#parent')
      });
      $('#assign_branchs').select2();
      $('#add-customer').validate({
        rules: {
            name: {
                required: true,
            },
            "assign_restaurants[]": {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            phone: {
                required: true,
                phone: true,
            },
            type: {
                required: true,
            },
            status: {
                required: true,
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                minlength : 6,
                equalTo : "#password"
            },
        },
        messages: {
            name: {
                required: "Please enter a name",
            },
            "assign_restaurants[]": {
                required: "Please select a restaurant",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a vaild email address"
            },
            phone: {
                required: "Please enter a phone ",
                phone: "Please enter a vaild phone"
            },
            type: {
                required: "Please select type of admin",
            },
            status: {
                required: "Please select status",
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            password_confirmation: {
                required: "Please confirm a password",
                minlength: "Your password must be at least 6 characters long",
                equalTo: "Password does not match"
            }
        },
        invalidHandler: function(form, validator) {
          var btn = $('#add-customer').find('button[type="submit"]');
          if (btn) {
            btn.addClass('animate__animated animate__shakeX animate__fast')
            setTimeout(function () {
                btn.removeClass('animate__animated animate__shakeX animate__fast')
            }, 500);
          }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
</script>
@endsection
