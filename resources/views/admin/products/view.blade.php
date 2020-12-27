@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ver Producto</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <bulk-action-form
                v-cloak
                inline-template>

            <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="this.action" novalidate>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-pencil"></i> Producto
                            </div>
                            <div class="card-body">
                                @include('admin.products.components.form-view-elements')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                        @include('admin.products.components.form-view-right-elements')
                    </div>
                </div>
            </form>
        </bulk-action-form>
    </div>
@endsection
