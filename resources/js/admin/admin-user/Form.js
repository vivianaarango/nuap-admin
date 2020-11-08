import AppForm from '../app-components/Form/AppForm';

Vue.component('admin-user-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                lastname:  '' ,
                email:  '' ,
                discount:  0.0 ,
                commission:  0.0 ,
                password:  '' ,
                activated:  false ,
                forbidden:  false ,
                language:  '' ,
                
            }
        }
    },
    methods: {
        onChange(event) {
            let role = event.target.value
            let commission = document.getElementById('commission-block');
            let discount = document.getElementById('discount-block');

            if (role === 'Mayorista') {
                commission.style.display = 'flex';
                discount.style.display = 'flex';
            } else {
                commission.style.display = 'none';
                discount.style.display = 'none';
            }
        }
    }
});