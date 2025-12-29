@props([
  'services' => [
    'Web App Development',
    'UI/UX & Prototyping',
    'CMS & Website Builder',
  ],
])

@php
  $flash = session('order_success');
@endphp

{{-- Toast sukses (optional) --}}
@if($flash)
  <div class="order-toast" role="status" aria-live="polite">
    <i class="ri-check-line" aria-hidden="true"></i>
    <span>{{ $flash }}</span>
  </div>
@endif

<div class="order-modal" id="orderModal" aria-hidden="true">
  <div class="order-modal__dialog" role="dialog" aria-modal="true" aria-label="Order Form">
    <div class="order-modal__header">
      <div>
        <h3 class="order-modal__title">Make an Order</h3>
        <p class="order-modal__subtitle">Choose whether you want to order directly or consult first.</p>
      </div>

      <button type="button" class="order-modal__close" data-order-close aria-label="Close">
        <i class="ri-close-line" aria-hidden="true"></i>
      </button>
    </div>

    <form method="POST" action="{{ route('orders.store') }}" class="order-modal__form">
      @csrf

      {{-- anti-spam simple honeypot --}}
      <input type="text" name="website" value="" autocomplete="off" tabindex="-1" class="hp-field" aria-hidden="true">

      {{-- TYPE --}}
      <div class="order-modal__type">
        <label class="chip-radio">
          <input type="radio" name="type" value="order" checked>
          <span>Order</span>
        </label>

        <label class="chip-radio">
          <input type="radio" name="type" value="consultation">
          <span>Consultation</span>
        </label>
      </div>

      <div class="order-grid">
        <div class="field">
          <label class="field__label">Full Name</label>
          <input class="field__input" name="name" type="text" required maxlength="100" placeholder="Your name">
        </div>

        <div class="field">
          <label class="field__label">Email</label>
          <input class="field__input" name="email" type="email" required maxlength="150" placeholder="you@mail.com">
        </div>

        <div class="field">
          <label class="field__label">WhatsApp</label>
          <input class="field__input" name="whatsapp" type="text" maxlength="30" placeholder="+62...">
        </div>

        <div class="field">
          <label class="field__label">Service (optional)</label>
          <select class="field__input" name="service">
            <option value="">Choose service</option>
            @foreach($services as $s)
              <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
          </select>
        </div>

        <div class="field field--full">
          <label class="field__label">Project / Topic</label>
          <input class="field__input" name="topic" type="text" required maxlength="160" placeholder="Example: Landing Page for business...">
        </div>

        {{-- ORDER FIELDS --}}
        <div class="field" data-fields="order">
          <label class="field__label">Budget Range (optional)</label>
          <select class="field__input" name="budget_range">
            <option value="">Choose</option>
            <option value="< 2jt">&lt; 2jt</option>
            <option value="2–5jt">2–5jt</option>
            <option value="5–10jt">5–10jt</option>
            <option value="> 10jt">&gt; 10jt</option>
          </select>
        </div>

        <div class="field" data-fields="order">
          <label class="field__label">Target Deadline (optional)</label>
          <input class="field__input" name="deadline" type="date">
        </div>

        {{-- CONSULTATION FIELDS --}}
        <div class="field" data-fields="consultation" hidden>
          <label class="field__label">Preferred Channel</label>
          <select class="field__input" name="preferred_channel">
            <option value="WhatsApp">WhatsApp</option>
            <option value="Email">Email</option>
            <option value="Google Meet">Google Meet</option>
          </select>
        </div>

        <div class="field" data-fields="consultation" hidden>
          <label class="field__label">Preferred Time (optional)</label>
          <input class="field__input" name="preferred_time" type="text" maxlength="80" placeholder="Example: 19:00 WIB / Weekend">
        </div>

        <div class="field field--full">
          <label class="field__label">Message</label>
          <textarea class="field__input field__textarea" name="message" required maxlength="2000" rows="5"
            placeholder="Tell us your needs..."></textarea>
        </div>
      </div>

      <div class="order-modal__actions">
        <button type="button" class="btn-ghost" data-order-close>Cancel</button>
        <button type="submit" class="btn-neon">
          <i class="ri-send-plane-2-line" aria-hidden="true"></i>
          Submit
        </button>
      </div>
    </form>
  </div>
</div>

@once
  @push('scripts')
    <script>
      (function () {
        const modal = document.getElementById('orderModal');
        if (!modal) return;

        const openers = document.querySelectorAll('[data-order-open]');
        const closeBtns = modal.querySelectorAll('[data-order-close]');
        const dialog = modal.querySelector('.order-modal__dialog');

        const bodyLock = (lock) => document.body.classList.toggle('modal-open', !!lock);

        const openModal = () => {
          modal.classList.add('is-open');
          modal.setAttribute('aria-hidden', 'false');
          bodyLock(true);

          const first = modal.querySelector('input[name="name"]');
          if (first) setTimeout(() => first.focus(), 0);

          toggleFields();
        };

        const closeModal = () => {
          modal.classList.remove('is-open');
          modal.setAttribute('aria-hidden', 'true');
          bodyLock(false);
        };

        openers.forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
          });
        });

        closeBtns.forEach(btn => btn.addEventListener('click', closeModal));

        modal.addEventListener('click', (e) => {
          if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', (e) => {
          if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
        });

        // Toggle order vs consultation fields
        const typeRadios = modal.querySelectorAll('input[name="type"]');
        const orderFields = modal.querySelectorAll('[data-fields="order"]');
        const consultFields = modal.querySelectorAll('[data-fields="consultation"]');

        function toggleFields() {
          const type = modal.querySelector('input[name="type"]:checked')?.value || 'order';

          orderFields.forEach(el => el.hidden = (type !== 'order'));
          consultFields.forEach(el => el.hidden = (type !== 'consultation'));
        }

        typeRadios.forEach(r => r.addEventListener('change', toggleFields));

        // auto-hide toast if exists
        const toast = document.querySelector('.order-toast');
        if (toast) setTimeout(() => toast.classList.add('hide'), 3500);
      })();
    </script>
  @endpush
@endonce
