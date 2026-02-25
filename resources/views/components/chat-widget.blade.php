<div
    x-data="chatWidget({
        startUrl: '{{ route('chat.start') }}',
        messageBase: '{{ url('/chat') }}',
        statusUrl: '{{ route('chat.status') }}',
        isOpen: {{ json_encode($isOpen) }},
        summary: '{{ $summary }}',
        contactUrl: '{{ route('contact') }}',
    })"
    x-cloak
    class="print-hidden fixed bottom-6 right-6 z-50 w-80 rounded-2xl border border-slate-200 bg-white shadow-xl"
>
    <div class="flex items-center justify-between bg-slate-900 px-4 py-3 rounded-t-2xl text-sm font-semibold uppercase tracking-wide text-white">
        <span>{{ __('common.labels.chat_support') }}</span>
        <button type="button" @click="open = !open" class="text-xs text-white">
            <span x-text="open ? '{{ __('common.actions.cancel') }}' : '{{ __('common.actions.open') }}'"></span>
        </button>
    </div>
    <div x-show="open" x-transition class="px-4 py-4">
        <div class="text-xs text-slate-500">
            {{ __('common.messages.chat_hour_summary', ['hours' => $summary]) }}
        </div>
        <div class="text-xs text-rose-500" id="chat-status" x-text="statusMessage"></div>
        <template x-if="!isOpen">
            <div class="mt-4 text-sm text-slate-700">
                {{ __('common.messages.chat_closed_on_hours', ['hours' => $summary]) }}
                <div class="mt-2 text-sm">
                    <a :href="contactUrl" class="text-slate-900 font-semibold">{{ __('common.actions.contact') }}</a>
                </div>
            </div>
        </template>
        <template x-if="isOpen">
            <div class="mt-4 space-y-3">
                <template x-if="!session">
                    <form @submit.prevent="start" class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500" for="visitor_name">{{ __('common.labels.full_name') }}</label>
                            <input id="visitor_name" x-model="form.name" type="text" class="form-input w-full" placeholder="{{ __('common.labels.full_name') }}">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500" for="visitor_phone">{{ __('common.labels.phone') }}</label>
                            <input id="visitor_phone" x-model="form.phone" type="text" class="form-input w-full" placeholder="{{ __('common.labels.phone') }}">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500" for="visitor_email">{{ __('common.labels.email') }}</label>
                            <input id="visitor_email" x-model="form.email" type="email" class="form-input w-full" placeholder="{{ __('common.labels.email') }}">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500" for="visitor_message">{{ __('common.labels.message') }}</label>
                            <textarea id="visitor_message" x-model="form.message" rows="3" class="form-textarea w-full" placeholder="{{ __('common.labels.message') }}"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn-primary" :disabled="loading">
                                <span x-text="loading ? '{{ __('common.actions.saving') }}' : '{{ __('common.actions.start') }}'"></span>
                            </button>
                        </div>
                    </form>
                </template>
                <template x-if="session">
                    <div class="space-y-3">
                        <div class="space-y-2 overflow-y-auto max-h-48 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm">
                            <template x-for="message in messages" :key="message.id">
                                <div>
                                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                        <span x-text="message.sender_type === 'agent' ? '{{ __('common.labels.agent') }}' : '{{ __('common.labels.visitor') }}'"></span>
                                        <span x-text="new Date(message.sent_at).toLocaleTimeString()"></span>
                                    </div>
                                    <p class="text-slate-700" x-text="message.message"></p>
                                </div>
                            </template>
                        </div>
                        <form @submit.prevent="sendMessage" class="space-y-2">
                            <textarea x-model="newMessage" rows="3" class="form-textarea w-full" placeholder="{{ __('common.labels.message') }}"></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary" :disabled="!newMessage.trim() || sending">
                                    <span x-text="sending ? '{{ __('common.actions.saving') }}' : '{{ __('common.actions.send') }}'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatWidget', (options) => ({
                open: false,
                session: null,
                messages: [],
                form: { name: '', phone: '', email: '', message: '' },
                newMessage: '',
                loading: false,
                sending: false,
                statusMessage: '',
                isOpen: options.isOpen,
                summary: options.summary,
                contactUrl: options.contactUrl,
                startUrl: options.startUrl,
                messageBase: options.messageBase,
                init() {
                    if (! this.isOpen) {
                        this.statusMessage = '{{ __('common.messages.chat_closed') }}'.replace(':hours', this.summary);
                    }
                },
                listen() {
                    if (! window.Echo || ! this.session) {
                        return;
                    }

                    window.Echo.channel(`chat.session.${this.session.id}`)
                        .listen('chat.message', (payload) => {
                            this.messages.push(payload);
                        });
                },
                start() {
                    if (! this.isOpen) return;

                    this.loading = true;
                    this.statusMessage = '';

                    axios.post(this.startUrl, this.form)
                        .then((response) => {
                            this.session = response.data.session;
                            if (response.data.message) {
                                this.messages.push(response.data.message);
                            }
                            this.listen();
                        })
                        .catch((error) => {
                            this.statusMessage = error.response?.data?.message ?? '{{ __('common.messages.unexpected_error') }}';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
                sendMessage() {
                    if (! this.session || ! this.newMessage.trim()) {
                        return;
                    }

                    this.sending = true;

                    axios.post(`${this.messageBase}/${this.session.id}/message`, {
                        message: this.newMessage,
                    })
                        .then((response) => {
                            this.messages.push(response.data.message);
                            this.newMessage = '';
                        })
                        .catch((error) => {
                            this.statusMessage = error.response?.data?.message ?? '{{ __('common.messages.unexpected_error') }}';
                        })
                        .finally(() => {
                            this.sending = false;
                        });
                },
            }));
        });
    </script>
@endonce
