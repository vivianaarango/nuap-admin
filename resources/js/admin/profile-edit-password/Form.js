import AppForm from '../app-components/Form/AppForm';

Vue.component('profile-edit-password-form', {
    mixins: [AppForm],
    methods: {
        onSuccess(data) {
            if(data.notify) {
                document.getElementById("password").value = ''
                document.getElementById("password_confirmation").value = '';
                this.$notify({ type: 'data.notify.type', title: data.notify.title, text: data.notify.message});
            } else if (data.redirect) {
                window.location.replace(data.redirect);
            }
        }
    }
});