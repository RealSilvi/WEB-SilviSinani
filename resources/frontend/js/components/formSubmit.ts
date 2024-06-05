import axios, { AxiosRequestConfig } from 'axios';
import { Alpine } from '../livewire';
import { apiErrorMessage, apiValidationErrors } from '../utils';

type AxiosBaseConfigAction = Pick<AxiosRequestConfig, 'url' | 'method' | 'data'>;

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
    onSuccessMessage?: string;
    onErrorMessage?: string;
    submitEventName?: string;
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
                const config: AxiosBaseConfigAction = {
                    data: new FormData(event.target),
                    url: props.url ?? event.target.action,
                    method: props.method ?? event.target.method,
                };

                if (config.method == 'PATCH' || config.method == 'patch') {
                    config.method = 'POST';
                    config.data.append('_method', 'PATCH');
                }

                await axios.request(config);

                event.target.reset();

                if (props.onSuccessRedirectUrl) {
                    window.location.replace(props.onSuccessRedirectUrl);
                }

                this.$dispatch('toast', {
                    type: 'success',
                    message: props.onSuccessMessage ?? 'Success',
                });

                this.$dispatch(props.submitEventName ?? 'submitted', {
                    formId: props.formId,
                    target: event.target,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.errors = apiValidationErrors(e?.response?.data);

                    this.$dispatch('toast', {
                        type: 'error',
                        message: apiErrorMessage(e?.response?.data, props.onErrorMessage ?? 'Error'),
                    });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
