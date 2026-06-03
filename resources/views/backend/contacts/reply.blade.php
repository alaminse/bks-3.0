@extends('layouts.backend')
@section('title')
    Reply to Contact Message
@endsection
@section('content')
    <!-- Header -->
    @include('includes.header', ['pageTitle' => 'Reply to Contact Message'])

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Original Message -->
        <div class="col-lg-5">
            <div class="card shadow border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-envelope-open me-2"></i>Original Message
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">From</label>
                        <div class="fw-bold">{{ $message->name }}</div>
                        <small class="text-muted">{{ $message->email }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Received</label>
                        <div>{{ $message->created_at->format('d M Y, h:i A') }}</div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="small text-muted mb-2">Message</label>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Make sure to address all the points mentioned in the message.
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-reply-fill me-2"></i>Compose Reply
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.contacts.send-reply', $message->id) }}" method="POST">
                        @csrf

                        <!-- Recipient Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">To</label>
                                <div class="p-2 bg-light rounded">
                                    <i class="bi bi-person me-1"></i>{{ $message->name }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Email</label>
                                <div class="p-2 bg-light rounded">
                                    <i class="bi bi-envelope me-1"></i>{{ $message->email }}
                                </div>
                            </div>
                        </div>

                        <!-- Email Subject -->
                        <div class="mb-3">
                            <label class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject', 'Re: Your Contact Message - ' . config('app.name')) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This will be the email subject line</small>
                        </div>

                        <!-- Reply Message -->
                        <div class="mb-3">
                            <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                            <textarea name="reply_message" class="form-control @error('reply_message') is-invalid @enderror" rows="12" required
                                placeholder="Dear {{ $message->name }},

Thank you for contacting us.

[Your reply here]

Best regards,
{{ config('app.name') }} Team">{{ old('reply_message') }}</textarea>
                            @error('reply_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quick Templates -->
                        <div class="mb-4">
                            <label class="form-label small">Quick Templates</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="insertTemplate('greeting')">
                                    <i class="bi bi-file-text"></i> Greeting
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="insertTemplate('thanks')">
                                    <i class="bi bi-hand-thumbs-up"></i> Thanks
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="insertTemplate('closing')">
                                    <i class="bi bi-envelope-check"></i> Closing
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="send_email" id="sendEmail"
                                        checked>
                                    <label class="form-check-label" for="sendEmail">
                                        <i class="bi bi-envelope-fill me-1"></i>
                                        <strong>Send email notification to {{ $message->email }}</strong>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="mark_resolved" id="markResolved"
                                        checked>
                                    <label class="form-check-label" for="markResolved">
                                        Mark this message as resolved after sending reply
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('backend.contacts.show', $message->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="previewReply()">
                                    <i class="bi bi-eye"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send-fill"></i> Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Card (Hidden by default) -->
            <div class="card shadow border-0 mt-4 d-none" id="previewCard">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Email Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="email-preview p-3 border rounded bg-white">
                        <div class="mb-2">
                            <strong>To:</strong> <span id="previewTo"></span>
                        </div>
                        <div class="mb-2">
                            <strong>Subject:</strong> <span id="previewSubject"></span>
                        </div>
                        <hr>
                        <div id="previewMessage" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Template texts
        const templates = {
            greeting: `Dear {{ $message->name }},
                        Thank you for reaching out to us. `,
                                    thanks: `We appreciate you taking the time to contact us and bringing this to our attention.`,
                                    closing: `
                        If you have any further questions or concerns, please don't hesitate to reach out.
                        Best regards,
                        {{ config('app.name') }} Team`
                    };

        // Insert template at cursor position
        function insertTemplate(type) {
            const textarea = document.querySelector('textarea[name="reply_message"]');
            const template = templates[type];
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;

            textarea.value = text.substring(0, start) + template + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + template.length;
        }

        // Preview reply
        function previewReply() {
            const subject = document.querySelector('input[name="subject"]').value;
            const message = document.querySelector('textarea[name="reply_message"]').value;

            document.getElementById('previewTo').textContent = '{{ $message->name }} ({{ $message->email }})';
            document.getElementById('previewSubject').textContent = subject;
            document.getElementById('previewMessage').textContent = message;

            document.getElementById('previewCard').classList.remove('d-none');
            document.getElementById('previewCard').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Auto-save draft to localStorage
        let autoSaveTimer;
        const textarea = document.querySelector('textarea[name="reply_message"]');
        const subjectInput = document.querySelector('input[name="subject"]');

        function autoSave() {
            const draft = {
                subject: subjectInput.value,
                message: textarea.value,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem('reply_draft_{{ $message->id }}', JSON.stringify(draft));
            console.log('Draft saved');
        }

        // Load saved draft
        function loadDraft() {
            const saved = localStorage.getItem('reply_draft_{{ $message->id }}');
            if (saved) {
                const draft = JSON.parse(saved);
                if (confirm('A draft was found from ' + new Date(draft.timestamp).toLocaleString() +
                        '. Do you want to load it?')) {
                    subjectInput.value = draft.subject;
                    textarea.value = draft.message;
                }
            }
        }

        // Auto-save every 30 seconds
        textarea.addEventListener('input', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSave, 30000);
        });

        subjectInput.addEventListener('input', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSave, 30000);
        });

        // Load draft on page load
        window.addEventListener('load', loadDraft);

        // Clear draft on successful submit
        document.querySelector('form').addEventListener('submit', () => {
            localStorage.removeItem('reply_draft_{{ $message->id }}');
        });
    </script>
@endpush

@push('styles')
    <style>
        .sticky-top {
            position: -webkit-sticky;
            position: sticky;
        }

        .email-preview {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
    </style>
@endpush
