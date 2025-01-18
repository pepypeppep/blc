<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Badges\database\seeders\BadgeSeeder;
use Modules\Currency\database\seeders\CurrencySeeder;
use Modules\Language\database\seeders\LanguageSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\Menubuilder\database\seeders\MenubuilderSeeder;
use Modules\Frontend\database\seeders\HomePagesSectionSeeder;
use Modules\BasicPayment\database\seeders\PaymentGatewaySeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\Installer\database\seeders\InstallerDatabaseSeeder;
use Modules\BasicPayment\database\seeders\BasicPaymentInfoSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\GlobalSetting\database\seeders\MarketingSettingSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\PageBuilder\database\seeders\PageBuilderDatabaseSeeder;
use Modules\Frontend\database\seeders\FeaturedInstructorSectionSeeder;
use Modules\InstructorRequest\database\seeders\InstructorRequestSeeder;
use Modules\CertificateBuilder\database\seeders\CertificateBuilderSeeder;
use Modules\CertificateBuilder\database\seeders\CertificateBuilderItemSeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancySeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyDetailSeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyDetailUserAttachmentSeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // InstallerDatabaseSeeder::class,
            LanguageSeeder::class,
            // CurrencySeeder::class,
            GlobalSettingInfoSeeder::class,
            MarketingSettingSeeder::class,
            // BasicPaymentInfoSeeder::class,
            // PaymentGatewaySeeder::class,
            CustomPaginationSeeder::class,
            EmailTemplateSeeder::class,
            SeoInfoSeeder::class,
            HomePagesSectionSeeder::class,
            SectionTranslationSeeder::class,
            RolePermissionSeeder::class,
            AdminInfoSeeder::class,
            UserSeeder::class,
            // PageBuilderDatabaseSeeder::class,
            CertificateBuilderSeeder::class,
            CertificateBuilderItemSeeder::class,
            FeaturedInstructorSectionSeeder::class,
            MenubuilderSeeder::class,
            MenuItemSeeder::class,
            SocialLinkSeeder::class,
            InstructorRequestSeeder::class,
            BadgeSeeder::class,
            // CourseSeeder::class,
            FaqSeeder::class,
            CustomPageSeeder::class,
            FooterSettingSeeder::class,
            CurrencySeeder::class,

            VacancySeeder::class,
            VacancyDetailSeeder::class,
            VacancyUserSeeder::class,
            VacancyDetailUserAttachmentSeeder::class,
        ]);
    }
}
