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
                identity_number:  '',
                latitude: '',
                longitude: '',
                address: '',
                is_closed: ''
            }
        }
    },
    methods: {
        onSuccess: function onSuccess(data) {
            if (data.notify) {
                this.$notify({
                    type: data.notify.type,
                    title: data.notify.title,
                    text: data.notify.message
                });
            } else if (data.redirect) {
                window.location.replace(data.redirect);
            }
        }
    }
});