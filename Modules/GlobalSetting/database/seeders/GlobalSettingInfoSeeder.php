<?php

namespace Modules\GlobalSetting\database\seeders;

use App\Enums\ThemeList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\GlobalSetting\app\Models\Setting;

class GlobalSettingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Logo image
        $logoImage = public_path('frontend/img/starter/BLC.png');
        $logoFilename = 'custom-images/logo' . time() . '.' . pathinfo($logoImage, PATHINFO_EXTENSION);
        $filePath = Storage::disk('private')->put($logoFilename, file_get_contents($logoImage));
        // Favicon image
        $faviconImage = public_path('frontend/img/starter/favicon.png');
        $faviconFilename = 'custom-images/favicon' . time() . '.' . pathinfo($faviconImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($faviconFilename, file_get_contents($faviconImage));
        // Avatar image
        $avatarImage = public_path('frontend/img/starter/default-avatar.png');
        $avatarFilename = 'custom-images/avatar' . time() . '.' . pathinfo($avatarImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($avatarFilename, file_get_contents($avatarImage));
        // Breadcrumb image
        $breadcrumbImage = public_path('frontend/img/starter/breadcrumb-image.jpg');
        $breadcrumbFilename = 'custom-images/breadcrumb' . time() . '.' . pathinfo($breadcrumbImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($breadcrumbFilename, file_get_contents($breadcrumbImage));
        // Preloader image
        $preloaderImage = public_path('frontend/img/starter/BLC.png');
        $preloaderFilename = 'custom-images/preloader' . time() . '.' . pathinfo($preloaderImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($preloaderFilename, file_get_contents($preloaderImage));
        // Watermark image
        $watermarkImage = public_path('frontend/img/starter/BLC.png');
        $watermarkFilename = 'custom-images/watermark' . time() . '.' . pathinfo($watermarkImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($watermarkFilename, file_get_contents($watermarkImage));


        $setting_data = [
            'app_name' => 'LMS Kabupaten Bantul',
            'version' => '2.0.0',
            'logo' => $logoFilename,
            'timezone' => 'Asia/Jakarta',
            'favicon' => $faviconFilename,
            'cookie_status' => 'active',
            'border' => 'normal',
            'corners' => 'thin',
            'background_color' => '#184dec',
            'text_color' => '#fafafa',
            'border_color' => '#0a58d6',
            'btn_bg_color' => '#fffceb',
            'btn_text_color' => '#222758',
            'link_text' => 'Infromasi Selengkapnya',
            'link' => '/page/privacy-policy',
            'btn_text' => 'Ya',
            'message' => 'Situs web ini menggunakan cookie esensial untuk memastikan operasinya berjalan dengan baik dan cookie pelacakan untuk memahami bagaimana Anda berinteraksi dengan situs ini. Persetujuan Anda diperlukan sebelum cookie pelacakan ini dapat diaktifkan.',
            'copyright_text' => '2024 LMS Kabupaten Bantul. All rights reserved.',
            'recaptcha_site_key' => 'recaptcha_site_key',
            'recaptcha_secret_key' => 'recaptcha_secret_key',
            'recaptcha_status' => 'inactive',
            'tawk_status' => 'inactive',
            'tawk_chat_link' => 'tawk_chat_link',
            'google_tagmanager_status' => 'active',
            'google_tagmanager_id' => 'google_tagmanager_id',
            'pixel_status' => 'inactive',
            'pixel_app_id' => 'pixel_app_id',
            'facebook_login_status' => 'inactive',
            'facebook_app_id' => 'facebook_app_id',
            'facebook_app_secret' => 'facebook_app_secret',
            'facebook_redirect_url' => 'facebook_redirect_url',
            'google_login_status' => 'inactive',
            'gmail_client_id' => 'gmail_client_id',
            'gmail_secret_id' => 'gmail_secret_id',
            'gmail_redirect_url' => 'gmail_redirect_url',
            'default_avatar' => $avatarFilename,
            'breadcrumb_image' => $breadcrumbFilename,
            'mail_host' => 'mail_host',
            'mail_sender_email' => 'sender@gmail.com',
            'mail_username' => 'mail_username',
            'mail_password' => 'mail_password',
            'mail_port' => 'mail_port',
            'mail_encryption' => 'ssl',
            'mail_sender_name' => 'bantulkab',
            'contact_message_receiver_mail' => 'receiver@gmail.com',
            'pusher_app_id' => 'pusher_app_id',
            'pusher_app_key' => 'pusher_app_key',
            'pusher_app_secret' => 'pusher_app_secret',
            'pusher_app_cluster' => 'pusher_app_cluster',
            'pusher_status' => 'inactive',
            'club_point_rate' => 1,
            'club_point_status' => 'active',
            'maintenance_mode' => 0,
            'maintenance_title' => 'Website Under maintenance',
            'maintenance_description' => '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>
            <p><a title="BantulKab" href="https://bantulkab.go.id/">BantulKab</a></p>',
            'last_update_date' => date('Y-m-d H:i:s'),
            'is_queable' => 'inactive',
            'commission_rate' => 0,
            'site_address' => 'Jl. RW Monginsidi 1 Bantul Daerah Istimewa Yogyakarta 55711',
            'site_email' => 'bkpsdm@bantulkab.go.id',
            'site_theme' => ThemeList::MAIN->value,
            'preloader' => $preloaderFilename,
            'primary_color' => '#5751e1',
            'secondary_color' => '#ffc224',

            'common_color_one' => '#050071',
            'common_color_two' => '#282568',
            'common_color_three' => '#1C1A4A',
            'common_color_four' => '#06042E',
            'common_color_five' => '#4a44d1',
            'show_all_homepage' => '0',
            'google_analytic_status' => 'inactive',
            'google_analytic_id' => 'google_analytic_id',
            'preloader_status' => '1',
            'maintenance_image' => '',
            'live_mail_send' => 5,

            'wasabi_access_id' => 'wasabi_access_id',
            'wasabi_secret_key' => 'wasabi_secret_key',
            'wasabi_region' => 'us-east-1',
            'wasabi_bucket' => 'wasabi_bucket',
            'wasabi_status' => 'inactive',

            'aws_access_id' => 'aws_access_id',
            'aws_secret_key' => 'aws_secret_key',
            'aws_region' => 'us-east-1',
            'aws_bucket' => 'aws_bucket',
            'aws_status' => 'inactive',
            'header_topbar_status' => 'active',
            'cursor_dot_status' => 'active',
            'header_social_status' => 'active',
            'watermark_img' => $watermarkFilename,
            'position' => 'top_right',
            'opacity' => '0.7',
            'max_width' => '300',
            'watermark_status' => 'active',
        ];

        foreach ($setting_data as $index => $setting_item) {
            Setting::updateOrCreate(['key' => $index], ['value' => $setting_item]);
        }
    }
}
