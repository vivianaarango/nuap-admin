@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Crear Distribuidor</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
    <script defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcFJ6KrZPpEM93HrS1fUhF2CxD7UTkWdw&callback=initMap">
    </script>
</head>

@section('body')

    <div class="container-xl">
        <div class="card">
            <admin-user-form
                :action="'{{ url('admin/distributor-store') }}'"
                :activation="!!'{{ $activation }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="this.action">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>&nbsp; Nuevo Distribuidor
                    </div>

                    <div class="card-body">
                        @include('admin.distributors.components.form-elements')
                    </div>

                    <div class="card-footer">
	                    <button type="submit" class="btn btn-primary" :disabled="submiting">
		                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            &nbsp; Guardar
	                    </button>
                    </div>
                </form>

            </admin-user-form>
        </div>
    </div>

@endsection
<script>
    function isNumberKey(evt){
        let charCode = (evt.which) ? evt.which : evt.keyCode
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }
    function initMap(){
        const myLatlng = { lat: 4.6533326, lng: -74.083652 }
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 10,
            center: myLatlng,
        });

        let marker = new google.maps.Marker({
            map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: myLatlng,
        });
        marker.addListener("click", toggleBounce);

        map.addListener("click", (mapsMouseEvent) => {
            marker.setMap(null)
            marker = new google.maps.Marker({
                position: mapsMouseEvent.latLng,
                map: map,
            });
        });
    }
    function toggleBounce() {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
</script>
