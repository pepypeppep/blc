<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Article\database\seeders\ArticleDatabaseSeeder;
use Modules\Article\database\seeders\ArticleSeeder;
use Modules\Badges\database\seeders\BadgeSeeder;
use Modules\Currency\database\seeders\CurrencySeeder;
use Modules\Language\database\seeders\LanguageSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\Menubuilder\database\seeders\MenubuilderSeeder;
use Modules\Frontend\database\seeders\HomePagesSectionSeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\GlobalSetting\database\seeders\MarketingSettingSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\Frontend\database\seeders\FeaturedInstructorSectionSeeder;
use Modules\InstructorRequest\database\seeders\InstructorRequestSeeder;
use Modules\CertificateBuilder\database\seeders\CertificateBuilderSeeder;
use Modules\CertificateBuilder\database\seeders\CertificateBuilderItemSeeder;
use Modules\Order\database\seeders\EnrollmentSeeder;
use Modules\PendidikanLanjutan\database\seeders\PendidikanLanjutanDatabaseSeeder;
use Modules\CertificateRecognition\database\seeders\CertificateRecognitionDatabaseSeeder;

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
            CourseCategorySeeder::class,
            CourseSeeder::class,
            FaqSeeder::class,
            CustomPageSeeder::class,
            FooterSettingSeeder::class,
            CurrencySeeder::class,

            PendidikanLanjutanDatabaseSeeder::class,
            TagSeeder::class,
            EnrollmentSeeder::class,
            ArticleDatabaseSeeder::class,
            TosSeeder::class,
            CertificateRecognitionDatabaseSeeder::class,
        ]);
    }
}
