<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/fontawesome-all.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/flaticon-skillgro.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/default-icons.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/odometer.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/plyr.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/spacing.css') }}">
@if ($setting?->cursor_dot_status == 'active')
    <link rel="stylesheet" href="{{ asset('frontend/css/tg-cursor.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('global/nice-select/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/main.min.css') }}?v={{ $setting?->version }}">
<link rel="stylesheet" href="{{ asset('frontend/css/frontend.min.css') }}?v={{ $setting?->version }}">

@if (Session::has('text_direction') && Session::get('text_direction') == 'rtl')
    <!-- RTL CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}?v={{ $setting?->version }}">
@endif

{{-- Dynamic root colors --}}
<style>
    :root {
        --tg-theme-primary: {{ $setting->primary_color }};
        --tg-theme-secondary: {{ $setting->secondary_color }};
        --tg-common-color-blue: {{ $setting->common_color_one }};
        --tg-common-color-blue-2: {{ $setting->common_color_two }};
        --tg-common-color-dark: {{ $setting->common_color_three }};
        --tg-common-color-black: {{ $setting->common_color_four }};
        --tg-common-color-dark-2: {{ $setting->common_color_five }};
    }

    /* Notification Dropdown Styles */
    .notification-dropdown .dropdown-header {
        padding: 12px 15px;
        font-weight: 600;
        color: #2c3e50;
        /* Darker, more professional color */
        border-bottom: 1px solid #f0f0f0;
        background-color: #f8f9fa;
        /* Light background for the header */
        border-radius: 8px 8px 0 0;
        /* Rounded corners only at the top */
        font-size: 24px;
        /* Slightly larger font */
        letter-spacing: 0.3px;
        /* Slight letter spacing for better readability */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notification-dropdown .dropdown-header span {
        position: relative;
        padding-left: 8px;
        /* Add some padding for the pseudo-element */
    }

    .notification-dropdown .dropdown-header span::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 20px;
        width: 3px;
        background-color: #5751e1;
        /* Accent color bar */
        border-radius: 3px;
    }

    .notification-icon {
        position: relative;
    }

    .notification-dropdown {
        width: 320px;
        padding: 0;
        border-radius: 8px;
        border: 1px solid #e5e5e5;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        right: 0;
        left: auto;
    }

    .notification-dropdown .dropdown-header {
        padding: 10px 15px;
        font-weight: 600;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
    }

    .notification-dropdown .divider {
        height: 1px;
        margin: 5px 0;
        overflow: hidden;
        background-color: #f5f5f5;
    }

    .notification-item {
        display: flex;
        padding: 10px 15px;
        transition: background-color 0.3s;
    }

    .notification-item:hover {
        background-color: #f9f9f9;
    }

    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .notification-icon i {
        color: white;
        font-size: 14px;
    }

    .notification-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .notification-title {
        font-weight: bold;
        color: #333;
        margin-bottom: 3px;
    }

    .notification-time {
        font-size: 12px;
        color: #999;
    }

    .view-all-notifications {
        display: block;
        padding: 10px 15px;
        color: #666;
        font-weight: 500;
        text-align: center;
    }

    .view-all-notifications:hover {
        color: #333;
        background-color: #f9f9f9;
    }

    .success-button {
        background: #47c363;
        padding: 5px 15px 5px 15px;
        color: white;
        border-radius: 10px;
        font-size: 14px;
    }

    .success-button i {
        color: white;
    }
</style>
