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
        Schema::create('dial_records', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('batch_no', 20)->nullable()->comment('匯入批號');
            $table->string('rate_type_id', 36)->nullable()->comment('所屬費率類別');
            $table->string('organization_id', 36)->nullable()->comment('所屬組織');
            $table->string('company_code', 20)->nullable()->comment('系統商代碼');
            $table->string('dail_record_type_id', 36)->nullable()->comment('所屬通聯資料類型');
            $table->string('user_id', 36)->nullable()->comment('所屬用戶');
            $table->string('telecom_account', 15)->nullable()->comment('用戶帳號/代號');
            $table->string('attach_number', 15)->nullable()->comment('附掛號碼,');
            $table->string('tel_number', 30)->nullable()->comment('電話號碼');
            $table->string('source_ip', 30)->nullable()->comment('來源IP');
            $table->string('call_type', 120)->nullable()->comment('撥打類型');
            $table->string('dial_location', 30)->nullable()->comment('發話地');
            $table->string('dial_number', 30)->nullable()->comment('發話號碼');
            $table->string('accept_location', 30)->nullable()->comment('受話地');
            $table->string('accept_number', 30)->nullable()->comment('受話號碼');
            $table->string('accept_IP', 30)->nullable()->comment('受話IP');
            $table->string('record_day', 15)->nullable()->comment('通話日期-民國日期');
            $table->string('record_day_ad', 15)->nullable()->comment('通話日期-西元日期');
            $table->time('start_time')->nullable()->comment('發話時間');
            $table->time('end_time')->nullable()->comment('結束時間');
            $table->time('talking_time')->nullable()->comment('通話時間');
            $table->integer('sec')->default(0)->nullable()->comment('總通話秒數');
            $table->string('frontent_code', 15)->nullable()->comment('前置碼');
            $table->string('period', 20)->nullable()->comment('時段');
            $table->string('fee', 30)->nullable()->comment('通話費');
            $table->string('charge_fee', 30)->nullable()->comment('應收費用-未稅');
            $table->string('tax', 30)->nullable()->comment('稅金');
            $table->string('charge_fee_tax', 30)->nullable()->comment('應收費用-含稅');
            $table->text('note')->nullable()->comment('備註');
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
        Schema::dropIfExists('dial_records');
    }
};
