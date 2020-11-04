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
    }
});