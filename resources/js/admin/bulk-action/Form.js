import AppForm from '../app-components/Form/AppForm';

Vue.component('bulk-action-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                commission:  '' ,
                perex:  '' ,
                published_at:  '' ,
                enabled:  false ,
            }
        }
    }

});