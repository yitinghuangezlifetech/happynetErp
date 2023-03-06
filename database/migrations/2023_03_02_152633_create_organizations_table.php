<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignUuid('identity_id')
                ->references('id')
                ->on('identities')
                ->onDelete('cascade')
                ->comment('所屬身份');
            $table->foreignUuid('organization_type_id')
                ->references('id')
                ->on('organization_types')
                ->onDelete('cascade')
                ->comment('所屬組織類型');
            $table->string('group_id', 36)->nullable()->comment('所屬群組');
            $table->string('fee_rate_id', 36)->nullable()->comment('所屬費率id');
            $table->string('system_no', 100)->nullable()->comment('系統編號');
            $table->string('name', 120)->nullable()->comment('用戶名稱/公司名稱');
            $table->string('id_no', 15)->nullable()->comment('用戶統編/身份證號');
            $table->string('manager', 100)->nullable()->comment('負責人');
            $table->string('manager_id_no', 15)->nullable()->comment('負責人身份字號');
            $table->string('mobile', 20)->nullable()->comment('行動電話');
            $table->string('tel', 20)->nullable()->comment('聯絡電話');
            $table->string('tel_1', 10)->nullable()->comment('分機');
            $table->string('fax', 20)->nullable()->comment('傳真電話');
            $table->string('line', 60)->nullable()->comment('Line帳號');
            $table->string('county', 60)->nullable()->comment('戶籍-縣市');
            $table->string('district', 60)->nullable()->comment('戶籍-鄉鎮市區');
            $table->string('zipcode', 5)->nullable()->comment('戶籍-郵遞區號');
            $table->text('address')->nullable()->comment('戶籍-地址');
            $table->string('bill_county', 60)->nullable()->comment('帳單-縣市');
            $table->string('bill_district', 60)->nullable()->comment('帳單-鄉鎮市區');
            $table->string('bill_zipcode', 5)->nullable()->comment('帳單-郵遞區號');
            $table->text('bill_address')->nullable()->comment('帳單-地址');
            $table->string('business_county', 60)->nullable()->comment('營業-縣市');
            $table->string('business_district', 60)->nullable()->comment('營業-鄉鎮市區');
            $table->string('business_zipcode', 5)->nullable()->comment('營業-郵遞區號');
            $table->text('business_address')->nullable()->comment('營業-地址');
            $table->string('email', 120)->nullable()->comment('郵件信箱');
            $table->string('official_website', 120)->nullable()->comment('官方網站');
            $table->date('expiry_day')->nullable()->comment('有效期限');
            $table->string('bill_day', 2)->nullable()->comment('每月結算日');
            $table->string('attach_number_limit', 20)->default(1000000)->nullable()->comment('附掛號碼限制');
            $table->string('phone_call_limit', 20)->default(1000)->nullable()->comment('同時最大通話總數限制');
            $table->string('bill_contact', 120)->nullable()->comment('帳務聯絡人');
            $table->string('bill_contact_mobile', 20)->nullable()->comment('帳務聯絡人-手機');
            $table->string('bill_contact_tel', 20)->nullable()->comment('帳務聯絡人-電話');
            $table->string('bill_contact_tel_1', 20)->nullable()->comment('帳務聯絡人-分機');
            $table->string('bill_contact_line', 120)->nullable()->comment('帳務聯絡人-line');
            $table->string('bill_contact_mail', 120)->nullable()->comment('帳務聯絡人-mail');
            $table->string('bill_contact_mail_1', 120)->nullable()->comment('帳務聯絡人-mail-1');
            $table->string('setup_contact', 120)->nullable()->comment('裝機聯絡人');
            $table->string('setup_contact_mobile', 20)->nullable()->comment('裝機聯絡人-手機');
            $table->string('setup_contact_tel', 20)->nullable()->comment('裝機聯絡人-電話');
            $table->string('setup_contact_tel_1', 20)->nullable()->comment('裝機聯絡人-分機');
            $table->string('setup_contact_line', 120)->nullable()->comment('裝機聯絡人-line');
            $table->string('setup_contact_mail', 120)->nullable()->comment('裝機聯絡人-mail');
            $table->string('setup_contact_mail_1', 120)->nullable()->comment('裝機聯絡人-mail-1');
            $table->text('note')->nullable()->comment('備註說明');
            $table->tinyInteger('status')->default(2)->nullable()->comment('狀態(值1:啟用, 值2:未啟用)');
            $table->string('create_user_id', 36)->nullable()->comment('建立人員');
            $table->string('update_user_id', 36)->nullable()->comment('修改人員');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
};
