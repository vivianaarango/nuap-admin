@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Agregar Ubicación</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcFJ6KrZPpEM93HrS1fUhF2CxD7UTkWdw&libraries=places&callback=initMap"></script>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" action="{{ url('admin/users/store-location') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <form class="form-horizontal form-create">
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" id="role" name="role" value="{{ $user->role }}">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Agregar Ubicación
                    </div>

                    <div class="card-body">
                        @include('admin.users.components.form-add-location-elements')
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-download"></i>
                            &nbsp; Guardar
                        </button>
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
<script>
    function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: {lat: 4.710988599999999, lng: -74.072092 },
        });
        let marker = new google.maps.Marker({
            position: {lat: 4.710988599999999, lng: -74.072092 },
            map: map
        });

        google.maps.event.addListener(map,'click',(event) => {
            marker.setPosition(event.latLng);
            document.querySelector("input[id=latitude]").value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();

            let geocoder = new google.maps.Geocoder();
            let yourLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());

            geocoder.geocode({ 'latLng': yourLocation },function(results, status) {
                document.getElementById('address').value = results[0].formatted_address;
            });
        });

        let autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('address')),
            { types: ['geocode'],
                componentRestrictions:{'country': 'co'} });

        google.maps.event.addListener(autocomplete, 'place_changed', () => {
            var place = autocomplete.getPlace();
            marker.setPosition(new google.maps.LatLng( place.geometry.location.lat(), place.geometry.location.lng()));
            map.panTo(new google.maps.LatLng( place.geometry.location.lat(), place.geometry.location.lng()));

            document.querySelector("input[id=latitude]").value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        });
    }
</script>