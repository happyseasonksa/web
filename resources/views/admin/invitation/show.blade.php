@extends('layouts.app')

@section('content')
{{-- get labors nationalities END --}}
<div class="container-fluid">
    <div class="row mt-3">
        <!-- left column -->
        <div class="col-md-12" style="text-align: right;">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title" style="float: right;">{{($invitation->restaurant)?ucfirst($invitation->restaurant->name):''}} {{__('Details')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped show-table">
                  <tbody>
                    <tr>
                        <th>{{__('User')}} :</th>
                        <td colspan="2">
                           {{$invitation->user?$invitation->user->name:null}}
                        </td>
                        <th>{{__('Invitation Date')}} :</th>
                        <td colspan="2">
                            {{$invitation->invitation_date}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Place')}} :</th>
                        <td colspan="2">
                           {{$invitation->item_name}}
                        </td>

                        <th>{{__('Address')}} :</th>
                        <td colspan="2">
                           {{$invitation->address}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Message')}} :</th>
                        <td colspan="5">
                            {{$invitation->message}}
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Signature')}} :</th>
                        <td colspan="5">
                           {{$invitation->signature}}
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
        </div>
    </div>
            <label style="float: right;">{{__('Location')}}</label>
            <div class="map-container">
                <div id="map" style="width:100%;height:300px;"></div>
            </div>
    {{--    <div class="row mt-3">--}}
{{--      <div class="col-sm-12">--}}
{{--          <div class="card card-primary">--}}
{{--              <div class="card-header">--}}
{{--                  <h6 class="card-title">{{__('Work')}}</h6>--}}
{{--              </div>--}}
{{--              <div class="card-body p-0">--}}
{{--              <table class="table table-stripe">--}}
{{--                  <thead>--}}
{{--                      <tr>--}}
{{--                          <th>{{__('Day')}}</th>--}}
{{--                          <th>{{__('From Time')}}</th>--}}
{{--                          <th>{{__('To Time')}}</th>--}}
{{--                          <th>{{__('Status')}}</th>--}}
{{--                      </tr>--}}
{{--                  </thead>--}}
{{--                  <tbody>--}}
{{--                      @foreach(getAllDays() as $day)--}}
{{--                      @php--}}
{{--                      $invitationTime = $invitation->times()->where('day',$day)->first();--}}
{{--                      @endphp--}}
{{--                      <tr>--}}
{{--                          <td colspan="2">{{ucfirst($day)}}</td>--}}
{{--                          <td colspan="2">--}}
{{--                            {{isset($invitationTime['from'])?$invitationTime['from']:'00:00'}}--}}
{{--                          </td>--}}
{{--                          <td colspan="2">--}}
{{--                            {{isset($invitationTime['to'])?$invitationTime['to']:'00:00'}}--}}
{{--                          </td>--}}
{{--                          <td colspan="2">--}}
{{--                            @if(isset($invitationTime['open']) && $invitationTime['open'] == '1')--}}
{{--                               <span class="badge badge-success">{{__('Open')}}</span>--}}
{{--                            @else--}}
{{--                               <span class="badge badge-danger">{{__('Off')}}</span>--}}
{{--                            @endif--}}
{{--                          </td>--}}
{{--                      </tr>--}}
{{--                      @endforeach--}}
{{--                  </tbody>--}}
{{--              </table>--}}
{{--              </div>--}}
{{--          </div>--}}
{{--      </div>--}}
{{--  </div>--}}
</div><!-- /.container-fluid -->
@endsection
@section('js-body')
<script>
    @if ($invitation->lat && $invitation->lng)
        var myLatLng = {lat: parseFloat("{{$invitation->lat}}"), lng: parseFloat("{{$invitation->lng}}")};
    @else
        var myLatLng = {lat: -33.8688, lng: 151.2195};
    @endif
    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 14,
          mapTypeId: 'roadmap'
        });

        var icon = {
          url: "https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png",
          size: new google.maps.Size(200, 200),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(25, 50),
          scaledSize: new google.maps.Size(50, 50)
        };

        @if ($invitation->lat && $invitation->lng)

            marker = new google.maps.Marker({
              map: map,
              icon: icon,
              title: "{{$invitation->address}}",
              position: myLatLng,
            });
        @endif
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{\Config::get('constant.google_map_key')}}&libraries=places&callback=initAutocomplete" async defer></script>
@endsection

