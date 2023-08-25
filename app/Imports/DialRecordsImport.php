<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DialRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DialRecordsImport implements ToCollection
{
    protected $type;
    protected $rateType;

    public function  __construct($type, $rateType)
    {
        $this->type = $type;
        $this->rateType = $rateType;
    }

    /**00
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $batchNo = date('YmdHis');

        switch ($this->type->type_code) {
            case 'FET_E1_PRI-市話':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[5]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[6]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[7])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[7])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'call_type' => $this->removeBlankSpace($row[1]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[2])),
                            'accept_location' => $this->removeBlankSpace($row[3]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[4])),
                            'record_day' => $this->removeBlankSpace($row[5]),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[8]),
                            'fee' => $this->removeBlankSpace($row[9]),
                            'note' => $this->removeBlankSpace($row[10]),
                        ]);
                    }
                }
                break;
            case 'FET_E1_PRI-長途-行動':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[2]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[8]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'tel_number' => $this->removeBlankSpace(str_replace("'", "", $row[1])),
                            'record_day' => $this->removeBlankSpace($row[2]),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'call_type' => $this->removeBlankSpace($row[3]),
                            'dial_location' => $this->removeBlankSpace($row[4]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[5])),
                            'accept_location' => $this->removeBlankSpace($row[6]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[9])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[10]),
                            'fee' => $this->removeBlankSpace($row[11]),
                            'note' => $this->removeBlankSpace($row[12]),
                        ]);
                    }
                }
                break;
            case 'FET_IMS-市話':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[5]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[6]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[7])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[7])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'call_type' => $this->removeBlankSpace($row[1]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[2])),
                            'accept_location' => $this->removeBlankSpace($row[3]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[4])),
                            'record_day' => $this->removeBlankSpace($row[5]),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[8]),
                            'fee' => $this->removeBlankSpace($row[9]),
                            'note' => $this->removeBlankSpace($row[10]),
                        ]);
                    }
                }
                break;
            case 'FET_IMS-長途-行動':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[2]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[8]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'tel_number' => $this->removeBlankSpace(str_replace("'", "", $row[1])),
                            'record_day' => $this->removeBlankSpace($row[2]),
                            'call_type' => $this->removeBlankSpace($row[3]),
                            'dial_location' => $this->removeBlankSpace($row[4]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[5])),
                            'accept_location' => $this->removeBlankSpace($row[6]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[9])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[10]),
                            'fee' => $this->removeBlankSpace($row[11]),
                            'note' => $this->removeBlankSpace($row[12]),
                        ]);
                    }
                }
                break;
            case 'FET_IMS_09_0701':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[2]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[8]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'tel_number' => $this->removeBlankSpace(str_replace("'", "", $row[1])),
                            'record_day' => $this->removeBlankSpace($row[2]),
                            'call_type' => $this->removeBlankSpace($row[3]),
                            'dial_location' => $this->removeBlankSpace($row[4]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[5])),
                            'accept_location' => $this->removeBlankSpace($row[6]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[9])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[10]),
                            'fee' => $this->removeBlankSpace($row[11]),
                            'note' => $this->removeBlankSpace($row[12]),
                        ]);
                    }
                }
                break;
            case 'FET_IMS_080_0701':
                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('account', $this->removeBlankSpace(str_replace("'", "", $row[0])))->first();

                        $recordDay = explode('.', $this->removeBlankSpace($row[2]));
                        $startTime = $this->removeBlankSpace(str_replace("'", "", $row[8]));
                        $tmp = explode(':', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $addHours = $tmp[0];
                        $addMinutes = $tmp[1];
                        $addSeconds = $tmp[2];

                        $carbonObj = Carbon::createFromFormat('H:i:s', $startTime);
                        $carbonObj->addHours($addHours);
                        $carbonObj->addMinutes($addMinutes);
                        $carbonObj->addSeconds($addSeconds);

                        $endTime = $carbonObj->format('H:i:s');

                        $carbonObj = Carbon::createFromFormat('H:i:s', $this->removeBlankSpace(str_replace("'", "", $row[9])));
                        $seconds = $carbonObj->diffInSeconds(Carbon::createFromTime(0, 0, 0));

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'rate_type_id' => $this->rateType,
                            'organization_id' => ($user) ? $user->organization_id : NULL,
                            'dail_record_type_id' => $this->type->id,
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[0])),
                            'tel_number' => $this->removeBlankSpace(str_replace("'", "", $row[1])),
                            'record_day' => $this->removeBlankSpace($row[2]),
                            'call_type' => $this->removeBlankSpace($row[3]),
                            'dial_location' => $this->removeBlankSpace($row[4]),
                            'dial_number' => $this->removeBlankSpace(str_replace("'", "", $row[5])),
                            'accept_location' => $this->removeBlankSpace($row[6]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[7])),
                            'record_day_ad' => $this->convertToAD($recordDay[0]) . '-' . sprintf('%02d', $recordDay[1]) . '-' . sprintf('%02d', $recordDay[2]),
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'talking_time' => $this->removeBlankSpace(str_replace("'", "", $row[9])),
                            'sec' => $seconds,
                            'period' => $this->removeBlankSpace($row[10]),
                            'fee' => $this->removeBlankSpace($row[11]),
                            'note' => $this->removeBlankSpace($row[12]),
                        ]);
                    }
                }
                break;
            case 'Jarrow 通聯記錄':
                //資料欄位格式
                //系統商,用戶代號,附掛號碼,來源IP,目的端號碼,目的端IP,開始時間,結束時間,前置碼,通話秒數,費用

                foreach ($rows as $k => $row) {
                    if ($k > 0) {
                        $user = app(User::class)->where('telecom_number', $this->removeBlankSpace(str_replace("'", "", $row[1])))->first();

                        $start = explode(' ', $row[6]);
                        $end = explode(' ', $row[7]);

                        $start_1 = Carbon::createFromFormat('H:i:s', $start[1]);
                        $end_1 = Carbon::createFromFormat('H:i:s', $end[1]);

                        $durationInSeconds = $end_1->diffInSeconds($start_1);
                        $formattedDuration = gmdate('H:i:s', $durationInSeconds);

                        $recordDay = $start[1];

                        app(DialRecord::class)->create([
                            'id' => uniqid(),
                            'batch_no' => $batchNo,
                            'dail_record_type_id' => $this->type->id,
                            'company_code' => $this->removeBlankSpace($row[0]),
                            'user_id' => $user->id ?? NULL,
                            'telecom_account' => $this->removeBlankSpace(str_replace("'", "", $row[1])),
                            'tel_number' => $this->removeBlankSpace(str_replace("'", "", $row[2])),
                            'record_day' => $recordDay,
                            'source_ip' => $this->removeBlankSpace($row[3]),
                            'accept_number' => $this->removeBlankSpace(str_replace("'", "", $row[4])),
                            'accept_IP' => $this->removeBlankSpace(str_replace("'", "", $row[5])),
                            'record_day_ad' => $recordDay,
                            'start_time' => $start[1],
                            'end_time' => $end[1],
                            'talking_time' => $formattedDuration,
                            'sec' => $durationInSeconds,
                            'frontent_code' => $this->removeBlankSpace($row[8]),
                            'fee' => $this->removeBlankSpace($row[10]),
                        ]);
                    }
                }
                break;
        }
    }

    private function removeBlankSpace($str)
    {
        //logger( mb_detect_encoding($str).' : '.$str);
        return preg_replace('/\s(?=)/', '', $str);
    }

    private function convertToAD($year)
    {
        $year += 1911; // 将民国年加上1911年即为西元年
        return $year;
    }
}
