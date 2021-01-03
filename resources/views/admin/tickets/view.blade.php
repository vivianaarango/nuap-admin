@extends('brackets/admin-ui::admin.layout.default')

<head>
    <title>Ticket</title>
    <link rel="icon" href="{{URL::asset('images/nuap.png')}}"/>
</head>

@section('body')
    <div class="container-xl">
        <div class="card">
            <form id="form-basic" method="post" action="{{ url('admin/ticket-send-message') }}">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <form class="form-horizontal form-create">
                    <div class="card-header">
                        <i class="fa fa-ticket"></i>&nbsp; Ticket #{{ $ticket_id }} - {{ $tittle }}
                    </div>

                    <div class="card-body">
                        <div id="cont-message" style="width: auto; height: 300px; overflow-y: scroll;">
                            @foreach ($data as $message)
                                <input type="hidden" id="ticket_id" name="ticket_id" value="{{ $message->id }}">
                                @if($message->sender_type === 'Distribuidor' || $message->sender_type === 'Comercio')
                                    <div class="received_withd_msg">
                                        <p>{{ $message->message }}</p>
                                        <span class="time_date"> {{ $message->role }} | {{ $message->sender_date }}</span>
                                    </div>
                                @else
                                    <div class="outgoing_msg">
                                        <div class="sent_msg">
                                            <p>{{ $message->message }}</p>
                                            <span class="time_date"> {{ $message->role }} | {{ $message->sender_date }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card-header">
                        <i class="fa fa-comment"></i>&nbspResponder
                    </div>
                    <div class="card-body">
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('message'), 'has-success': this.fields.message && this.fields.message.valid }">
                            <label for="message" class="col-form-label text-md-right" :class="'col-md-3'"><b></b></label>
                            <div :class="'col-md-4 col-md-9 col-xl-7'">
                                <textarea rows="4" required class="form-control" :class="{'form-control-danger': errors.has('message'), 'form-control-success': this.fields.message && this.fields.message.valid}" id="message" name="message" placeholder="Mensaje"></textarea>
                                <div v-if="errors.has('message')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('message') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="padding-top: 0" class="card-footer">
                        @if($data[0]->status !== 'Cerrado')
                            <button style="color: white" type="submit" class="btn btn-success pull-right">
                                <i class="fa fa-send"></i>
                                &nbsp; Enviar
                            </button>
                        @else
                            <button disabled style="color: white" type="submit" class="btn btn-success pull-right">
                                <i class="fa fa-send"></i>
                                &nbsp; Enviar
                            </button>
                        @endif
                    </div>
                </form>
            </form>
        </div>
    </div>
@endsection
<style>
    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }
    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }
    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }
    .received_withd_msg { width: 57%;}
    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 60%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0; color:#fff;
        padding: 5px 10px 5px 12px;
        width:100%;
    }
    .outgoing_msg{ overflow:hidden; margin:10px 0 10px;}
    .sent_msg {
        float: right;
        width: 46%;
    }
</style>
<script>
    window.addEventListener('load', setScroll, false);
    function setScroll() {
        let div = document.getElementById('cont-message')
        div.scrollTop = div.scrollHeight;
    }
</script>