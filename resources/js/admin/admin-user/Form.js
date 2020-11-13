import AppForm from '../app-components/Form/AppForm';

Vue.component('c', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                email:  '' ,
                name:  '' ,
                last_name:  '' ,
                password:  '' ,
                identity_number:  ''
            }
        }
    }
});