import axios, { AxiosRequestConfig } from 'axios';
import { Alpine } from '../livewire';

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
    onFailMessage?: string;
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
                if (props.onSuccessMessage) {
                    this.$dispatch('toast', {
                        type: 'success',
                        message: props.onSuccessMessage,
                    });
                }

                this.$dispatch(props.submitEventName ?? 'submitted', {
                    formId: props.formId,
                    target: event.target,
                });
            } catch (e) {
                if (axios.isAxiosError(e) && e?.response?.data) {
                    this.$dispatch('toast', {
                        type: 'error',
                        message: props.onFailMessage ?? 'Error',
                    });
                }
            } finally {
                this.saving = false;
            }
        },
    };
});
