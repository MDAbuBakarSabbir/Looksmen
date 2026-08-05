@extends('layouts.Backend.master')
@section('title')
    AI ASSISTANT TRAINING & SETTINGS
@endsection
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .ai-settings-container {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Page Hero */
    .ai-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.25);
    }
    .ai-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .ai-hero-icon {
        width: 52px; height: 52px;
        background: rgba(255,255,255,0.18);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        margin-bottom: 14px;
    }
    .ai-hero h1 { font-size: 22px; font-weight: 800; margin: 0 0 6px; color: #fff; }
    .ai-hero p  { color: rgba(255,255,255,0.85); font-size: 13.5px; margin: 0; }

    /* Card styling */
    .ais-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        padding: 28px;
        margin-bottom: 24px;
    }
    .ais-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ais-card-subtitle {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 20px;
    }

    .form-label-custom {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }
    .form-control-custom {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        font-size: 13.5px !important;
        color: #0f172a !important;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        background: #ffffff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12) !important;
        outline: none;
    }

    .btn-save-ai {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 12px 28px !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.35) !important;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(99, 102, 241, 0.45) !important;
    }

    .tip-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 12.5px;
        color: #1e40af;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
</style>

<div class="ai-settings-container">
    {{-- Header / Hero --}}
    <div class="ai-hero">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="ai-hero-icon">🤖</div>
                <h1>Gemini AI Assistant Training & Knowledge Base</h1>
                <p>Train your AI Customer Support with your shop's custom rules, delivery policies, return guidelines, FAQs, and human transfer keywords.</p>
            </div>
            <div>
                <a href="{{ route('admin.aiSupport.index') }}" class="btn btn-light rounded-pill px-3 py-2 font-weight-bold" style="font-size: 12px; color:#4f46e5;">
                    <i class="fa-solid fa-comments mr-1"></i> Back to Live Chat
                </a>
            </div>
        </div>
    </div>

    <form id="aiTrainingForm" action="{{ route('admin.aiSupport.updateSettings') }}" method="POST">
        @csrf

        {{-- Card 0: Google Gemini API Key --}}
        <div class="ais-card">
            <div class="ais-card-title">
                <i class="fa-solid fa-key text-warning" style="font-size: 18px;"></i>
                Google Gemini API Key Configuration (গুগল এআই কী)
            </div>
            <div class="ais-card-subtitle">
                Enter your Google Gemini API Key to enable real-time generative AI answers. You can generate a free API Key from <a href="https://aistudio.google.com/app/apikey" target="_blank" class="font-weight-bold">Google AI Studio</a>.
            </div>

            <div class="form-group mb-0">
                <label class="form-label-custom">Gemini API Key</label>
                <input type="text" name="ai_gemini_api_key" class="form-control form-control-custom"
                    value="{{ $settings['ai_gemini_api_key'] ?? '' }}"
                    placeholder="e.g. AIzaSy...">
            </div>
        </div>

        {{-- Card 1: Custom Training Knowledge Base --}}
        <div class="ais-card">
            <div class="ais-card-title">
                <i class="fa-solid fa-brain text-indigo" style="font-size: 18px;"></i>
                Store Knowledge Base & Instructions (এআই ট্রেনিং ডেটাবেস)
            </div>
            <div class="ais-card-subtitle">
                Enter your custom shop information, delivery rates, return rules, store locations, FAQs, or special discounts. Gemini AI will train itself with this knowledge base to answer customer questions.
            </div>

            <div class="tip-box">
                <i class="fa-solid fa-lightbulb mt-1 text-primary" style="font-size: 16px;"></i>
                <div>
                    <strong>Training Tip:</strong> Type rules clearly in points or bullet format in English, Bengali, or Banglish. For example:<br>
                    • <em>Delivery Charge: ৳60 inside Dhaka (24-48 hrs), ৳120 outside Dhaka (2-4 days). Free shipping over ৳2000!</em><br>
                    • <em>Showroom: Level-4, Block-B, Jamuna Future Park, Dhaka. Contact: 01568482005.</em><br>
                    • <em>Discounts: Use coupon 'WELCOME10' for 10% off on your first order.</em>
                </div>
            </div>

            <div class="form-group mb-0">
                <label class="form-label-custom">Knowledge Base & Instructions</label>
                <textarea name="ai_training_knowledge_base" rows="10" class="form-control form-control-custom"
                    placeholder="[Store Policies & FAQs]&#10;• Delivery Charge: ৳60 Inside Dhaka, ৳120 Outside Dhaka. Free shipping on orders over ৳2000!&#10;• Shop Location: Level-4, Block-B, Jamuna Future Park, Dhaka.&#10;• Return Policy: Easy exchange within 7 days if unwashed with tag intact.&#10;• Special Coupon: Use code 'WELCOME10' for 10% off!">{{ $settings['ai_training_knowledge_base'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- Card 2: AI Personality & Tone --}}
        <div class="row">
            <div class="col-md-6">
                <div class="ais-card">
                    <div class="ais-card-title">
                        <i class="fa-solid fa-wand-magic-sparkles text-indigo"></i>
                        AI Tone & Language (এআই এর কথা বলার ভাষা)
                    </div>
                    <div class="ais-card-subtitle">Define how friendly and polite your AI assistant should sound when talking to customers.</div>

                    <div class="form-group mb-0">
                        <label class="form-label-custom">Tone & Style Instruction</label>
                        <input type="text" name="ai_assistant_tone" class="form-control form-control-custom"
                            value="{{ $settings['ai_assistant_tone'] ?? 'Polite, friendly and helpful tone in Bengali and English (Banglish)' }}"
                            placeholder="e.g. Polite, friendly and helpful tone in Bengali and English">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ais-card">
                    <div class="ais-card-title">
                        <i class="fa-solid fa-text-height text-indigo"></i>
                        Response Length Limit (উত্তর কত বড় হবে)
                    </div>
                    <div class="ais-card-subtitle">Keep AI replies concise so customers get quick and direct answers without reading long paragraphs.</div>

                    <div class="form-group mb-0">
                        <label class="form-label-custom">Maximum Sentences per Reply</label>
                        <input type="text" name="ai_max_sentences" class="form-control form-control-custom"
                            value="{{ $settings['ai_max_sentences'] ?? '2-3' }}"
                            placeholder="e.g. 2-3">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Live Agent Transfer Keywords --}}
        <div class="ais-card">
            <div class="ais-card-title">
                <i class="fa-solid fa-headset text-danger"></i>
                Live Admin Agent Transfer Keywords (হিউম্যান এজেন্টে হস্তান্তরের শব্দসমূহ)
            </div>
            <div class="ais-card-subtitle">Comma-separated list of keywords that trigger immediate transfer to a live admin support representative in your admin dashboard.</div>

            <div class="form-group mb-0">
                <label class="form-label-custom">Transfer Keywords (Comma Separated)</label>
                <input type="text" name="ai_transfer_keywords" class="form-control form-control-custom"
                    value="{{ $settings['ai_transfer_keywords'] ?? 'talk to agent, admin, live support, human, representative, agent, operator' }}"
                    placeholder="talk to agent, admin, live support, human, representative, agent, operator">
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="d-flex align-items-center justify-content-between mb-5">
            <button type="submit" class="btn btn-save-ai" id="btnSaveAiSettings">
                <i class="fa-solid fa-floppy-disk"></i> Save AI Training Settings
            </button>
            <span class="text-muted small">All changes take effect immediately on live customer AI chat!</span>
        </div>
    </form>
</div>

@section('script')
<script>
    $(document).ready(function() {
        $('#aiTrainingForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const btn = $('#btnSaveAiSettings');

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving Training Data...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save AI Training Settings');
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save AI Training Settings');
                    Swal.fire('Error!', 'Failed to save AI training settings.', 'error');
                }
            });
        });
    });
</script>
@endsection

@endsection
