@if (($error))
    <div class="wrapper">
        <div class="">
            <div class="center-block">
                <img src="{{URL::asset('images/Nuapcircle.png')}}" style="width:40%" class="img-responsive mx-auto d-block">
            </div>
            <h2 style="color: white">GRACIAS POR ENTRAR A NUAP</h2>
            <h3 style="color: white">No hemos encontrado tu orden por favor intenta de nuevo</h3>
        </div>
    </div>
@else
    <form>
        <div class="wrapper">
            <span class="circle circle-1"></span>
            <span class="circle circle-2"></span>
            <span class="circle circle-3"></span>
            <span class="circle circle-4"></span>
            <span class="circle circle-5"></span>
            <span class="circle circle-6"></span>
            <span class="circle circle-7"></span>
            <span class="circle circle-8"></span>
        </div>

        <script
            src="https://checkout.epayco.co/checkout.js"
            class="epayco-button"
            data-epayco-key="{{ $key }}"
            data-epayco-amount="{{ $amount }}"
            data-epayco-name="{{ $name }}"
            data-epayco-description="{{ $description }}"
            data-epayco-currency="{{ $currency }}"
            data-epayco-country="{{ $country }}"
            data-epayco-test="{{ $test }}"
            data-epayco-external="{{ $external }}"
            data-epayco-response="{{ $response }}"
            data-epayco-confirmation="{{ $confirmation }}">
        </script>
    </form>
@endif

<style>
    body{
        padding: 0;
        margin: 0;
        background-color: #2a4d9c;
        text-align: center;
        height:100vh;
        font-family: 'lato';
        font-weight: 100;;
    }
    .wrapper{
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%, -50%);
    }
    .circle{
        display: inline-block;
        width: 15px;
        height: 15px;
        background-color: #2a4d9c;
        border-radius: 50%;
        animation: loading 1.5s cubic-bezier(.8, .5, .2, 1.4) infinite;
        transform-origin: bottom center;
        position: relative;
    }
    @keyframes loading{
        0%{
            transform: translateY(0px);
            background-color: #2a4d9c;
        }
        50%{
            transform: translateY(50px);
            background-color: white;
        }
        100%{
            transform: translateY(0px);
            background-color: #2a4d9c;
        }
    }
    .circle-1{
        animation-delay: 0.1s;
    }
    .circle-2{
        animation-delay: 0.2s;
    }
    .circle-3{
        animation-delay: 0.3s;
    }
    .circle-4{
        animation-delay: 0.4s;
    }
    .circle-5{
        animation-delay: 0.5s;
    }
    .circle-6{
        animation-delay: 0.6s;
    }
    .circle-7{
        animation-delay: 0.7s;
    }
    .circle-8{
        animation-delay: 0.8s;
    }

    .welcome_area_text2 {
        margin-top:35px;
        margin-bottom:40px;
    }

    body{
        padding: 0;
        margin: 0;
        background-color: #2a4d9c;
        text-align: center;
        height:100vh;
        font-family: 'Poppins', sans-serif;
        font-weight: 100;;
    }
    .wrapper{
        position:absolute;
        top:50%;
        left:50%;
        transform:translate(-50%, -50%);
    }
</style>

<script>
    function execute() {
        document.getElementsByClassName("epayco-button-render")[0].click();
    }
    setTimeout(execute, 2000);
</script>
