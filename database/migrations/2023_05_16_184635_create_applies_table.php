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
        Schema::create('applies', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('apply_no', 120)->nullable()->comment('申請編號');
            $table->string('contract_no', 120)->nullable()->comment('合約編號');
            $table->string('contract_id', 36)->nullable()->comment('適用合約');
            $table->string('project_id', 36)->nullable()->comment('適用專案');
            $table->string('contract_name', 120)->nullable()->comment('合約名稱');
            $table->string('identity_id', 36)->nullable()->comment('客戶身份');
            $table->string('organization_id', 36)->nullable()->comment('客戶所屬組織');
            $table->string('user_id', 36)->nullable()->comment('用戶帳號');
            $table->string('plan_type_id', 36)->nullable()->comment('申請書類別');
            $table->date('apply_day')->nullable()->comment('申請日期');
            $table->string('apply_type_id', 36)->nullable()->comment('申請類別');
            $table->string('close_period_id', 36)->nullable()->comment('結算區間');
            $table->date('start_day')->nullable()->comment('合約生效日');
            $table->date('end_day')->nullable()->comment('合約終止日');
            $table->integer('month_pay_total')->nullable()->default(0)->comment('月繳合計');
            $table->integer('deposit_total')->nullable()->default(0)->comment('保證金合計');
            $table->string('sender_id', 36)->nullable()->comment('送件人');
            $table->string('sender_sign', 120)->nullable()->comment('送件人簽名');
            $table->string('recipient_id', 36)->nullable()->comment('收件人');
            $table->string('recipient_sign', 120)->nullable()->comment('收件人簽名');
            $table->string('system_no', 120)->nullable()->comment('系統編號');
            $table->string('technician_id', 36)->nullable()->comment('技術員');
            $table->string('technician_sign', 120)->nullable()->comment('技術員簽名');
            $table->string('tel', 20)->nullable()->comment('聯絡電話');
            $table->string('auditor_id', 36)->nullable()->comment('稽核員');
            $table->string('auditor_sign', 120)->nullable()->comment('稽核員簽名');
            $table->string('customer', 120)->nullable()->comment('客戶簽名');
            $table->string('company_seal', 120)->nullable()->comment('公司大章');
            $table->string('company_stamp', 120)->nullable()->comment('公司小章');
            $table->string('fail_response')->nullable()->comment('審核不過的理由');
            $table->tinyInteger('status')->default(4)->nullable()->comment('狀態(值1:通過, 值2:不過通, 值3:待審核, 值4:未送件)');
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
        Schema::dropIfExists('applies');
    }
};
