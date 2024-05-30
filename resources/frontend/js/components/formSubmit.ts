import axios from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors } from '../utils';

interface FormSubmitProps {
    formId?: string;
    url?: string;
    method?:
        | 'get'
        | 'GET'
        | 'delete'
        | 'DELETE'
        | 'head'
        | 'HEAD'
        | 'post'
        | 'POST'
        | 'put'
        | 'PUT'
        | 'patch'
        | 'PATCH';
    onSuccessRedirectUrl?: string;
    messageSuccess?: string;
    messageError?: string;
}

Alpine.data('formSubmit', (props: FormSubmitProps = {}) => {
    return {
        errors: {},
        saving: false,

        async submit(event: SubmitEvent) {
            if (!(event.target instanceof HTMLFormElement)) {
                return;
            }

            if (!props.url && !event.target.action) {
                console.error('[formSubmit] url is required');
                return;
            }
            if (!props.method && !event.target.method) {
                console.error('[formSubmit] method is required');
                return;
            }

            if (this.saving) {
                return;
            }

            this.saving = true;
            this.errors = {};

            try {
                const url = props.url ?? event.target.action;
                const data = new FormData(event.target);
                let method = props.method ?? event.target.method;
                if (method == 'PATCH' || method == 'patch') {
                    method = 'POST';
                    data.append('_method', 'PATCH');
                }

                const response = await axios.request({ url: url, data: data, method: method });

                this.$dispatch('toast', {
                    type: 'success',
                    message: response.data.message,
                });

                event.target.reset();
                if (props.onSuccessRedirectUrl) {
                    window.location.replace(props.onSuccessRedirectUrl);
                } else {
                    window.location.reload();
                }

                this.$dispatch('submitted', {
                    formId: props.formId,
                    target: event.target,
                    data,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);

                    this.$dispatch('toast', {
                        type: 'error',
                        message: apiErrorMessage(
                            e?.response?.data,
                            props.messageError ?? 'messages.contact_form_error', //window.polyglot.t('messages.contact_form_error')
                        ),
                    });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
