<p align="center"><a href="https://laravel.com" target="_blank"><img src="public/img/logo-small.png" width="400" alt="Laravel Logo"></a></p>
<p align="center" style="font-size: 26px;">系統使用說明</p>

## 系統安裝流程

- [連線至GIT專案](https://github.com/yitinghuangezlifetech/ezAudit).
- 點擊Code標籤後選擇HTTP 或 SSH 方式並複製其網址或SSH位置.
- 在命令提示字元或終端機的模式下至Web Service所指定的根目錄.
- 在命令列中輸入 git clone XXXXXXXX ***[將複製的網址或SSH位置貼上]*** 並按下Enter.
- Clone完成後輸入指令(忽略版本問題)：composer install --ignore-platform-reqs
- 輸入指令：php artisan key:generate
- 設定環境檔(.env).
- 輸入指令：php artisan storage:link
- 輸入指令：php artisan migrate
- 輸入指令：php artisan db:seed

## 預設帳/密/系統

- 帳號：admin@admin.com
- 密碼：admin@admin.com
- 系統：資安系統

## 資料匯入-資安

- 輸入指令：php artisan import:regulation infoSecurityRequlation.xlsx

## 新增目錄/功能

- 開啟MenuSeeder.php(database/seeders/MenuSeeder.php).
- 針對getData method 做目錄與功能的設定(請參閱即有的程式碼).
- Icon Class 查詢 [Font Awesome ](https://fontawesome.com/v5/search?o=r&m=free)
- 程式碼說明如下：

```
目錄設定
[
    'id' => 2,
    'menu_name' => '權限管理', //目錄名稱
    'name' => '權限', //簡稱
    'icon_class' => 'nav-icon fa fa-id-card', //目錄Icon 
    'sort' => 1 //排序
]

功能設定
[
    'id' => uniqid(),
    'menu_name' => '群組設定', //功能名稱
    'name' => '群組', //功能簡稱
    'slug' => 'groups', //網址
    'target' => '_self', //開啟連結方式(值_self:當前視窗, 值blank:另開視窗)
    'icon_class' => 'far fa-circle nav-icon', //icon class, 可以不用異動
    'model' => 'App\Models\Group', //指定model
    'controller'=>'App\Http\Controllers\GroupController', //指定controller, 若無客製則給NULL值
    'search_component' => 1, //是否加入條件查詢功能(值1:是, 值2:否)
    'parent_id' => 2, //所屬目錄id
    'sort' => 0 //排序值
]

```
- 輸入指令：php artisan db:seed (執行前提, 若有新的功能, 則指定的model中 getFieldProperties() 此metho必需設定完成)<br><br>

## 建立與設定Model ##

- 輸入指令：php artisan make:model [Model名稱] -m 
- ***Model名稱請統一駝峰式命名(單數) ex: GroupPermission***
- Class 必需繼承 AbstractModel, 且需設定 $table與$guarded的參數 範例如下：
```
class Role extends AbstractModel
{
    protected $table = 'roles';
    protected $guarded = [];
}
```
> 一個Model對應一張資料表, 若該Model需系統自動呈現UI畫面, 則需實作getFieldProperties() 方法, 在該方法內設定需要呈現欄位的相關資訊

- 若需自動呈現畫面, 則Model Class 必需實作 ***getFieldProperties()*** 此method, 範例如下
```
class Role extends AbstractModel
{
    protected $table = 'roles';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'systems',
                'type' => 'component',
                'show_name' => '所屬系統類別',
                'use_component' => 1,
                'component_name' => 'SystemType',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\SystemType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'relationship_method'=>'getParentSystemType',
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 4
            ],
        ];
    }
}

```
- 若Model無須呈現畫面, 則 getFieldProperties() method只需保留即可不需實作, 範例如下：
```
class Role extends AbstractModel
{
    protected $table = 'roles';
    protected $guarded = [];

    public function getFieldProperties(){}
}
```
- getFieldProperties() 內容設定說明 <br>
<span style="color:yellow">欄位名稱請參照menu_details這張資料表的欄位說明,以下說明是針對特殊用法</span>
> 欄位類型(type)：checkbox, ckeditor, color, date_time, date, email, file, hidden, image, multiple_img, multiple_input, multiple_select, number, password, radio, select, show_img, text_area, text, time, component
```
//圖片欄位資訊設定
'type' => 'image', //欄位類型-必設定
'has_js' => 1, //載入圖片套件-必設定
'attributes' => json_encode([ //圖片尺寸限定750PX*805PX - 可不設定
    ['data-max-file-size'=>'500K'],
    ['data-min-width'=>'749'],
    ['data-max-width'=>'751'],
    ['data-min-height'=>'804'],
    ['data-max-height'=>'806'],
])

//欄位與其它資料表關聯
'type' => 'select',
'has_relationship' => 1, //是否與其它表關聯
'relationship' => json_encode([
    'model' => 'App\Models\Role', //關聯的model
    'references_field' => 'id', //關聯的欄位
    'show_field' => 'name' //顥示的欄位
])

//欄位驗證, 如果該欄位在update的時候不檢驗, 則update_rule可以全部移除
'create_rule' => json_e*ncode([
    'name'=>'required | unique:frontend_menu_types'
]),
'update_rule' => json_encode([
    'name'=>'required'
]),
'error_msg' => json_encode([
    'name.required'=>'選單類型名稱請勿空白',
    'unique.required'=>'選單類型名稱重覆',
]),

//radio固定值設定, 'default'=>1 表示預設會checked
'type' => 'radio',
'options' => json_encode([
    ['text'=>'是', 'value'=>'1', 'default'=>2],
    ['text'=>'否', 'value'=>'2', 'default'=>1],
])

//select 選項固定值, 'default'=>1 表示預設會selected
'type' => 'select',
'options' => json_encode([
    ['text'=>'公告', 'value'=>'1', 'default'=>1],
    ['text'=>'方案內容', 'value'=>'2', 'default'=>2],
])

//排版專用設定
'field' => 'empty',
'create' => 1,
'edit' => 1,
'sort' => 12,

//模組元件設定
'type' => 'component',
'component_name' => 'SystemType',
'has_relationship' => 1,
'relationship' => json_encode([
    'model' => 'App\Models\SystemType',
    'references_field' => 'id',
    'show_field' => 'name'
]),
'relationship_method'=>'systemTypes', //對應的關聯方法
```
- 條件查詢的部份, 原則上每個model都會繼承來自父類的查詢方法***getListByFilters():依搜尋條件取得分頁資料*** 與 ***getAllDataByFilters():依搜尋條件取得所有資料***, 若因特殊情況則可覆寫此二個方法
<br><br>

## 建立與設定Controller ##
>01.除非需要客製的功能, 否則無需建立Controller, 系統會針對沒有指定controller的功能自動代入BasicController<br>02.若有客製或特殊的功能,則在 ***database\seeders\MenuSeeder.php*** 裡指定Controller(須含namespace與Class name)
- 輸入指令：php artisan make:controller Controller名稱 ***(須含Controller字樣)***
- Controller 需繼承BasicController, 範例如下：
```
class RoleController extends BasicController
```
- 針對需要處理的method進行覆寫即可, 例如BasicController己有index, create, store, edit, update, delete等功能, 而客製功能需要由store, update來處理額外的參數, 則該Controller 只需要覆寫由父類所繼承下來的store與update即可, 其它則由父類來處理即可.
>參考範例：請參閱 ***app/Http/Controllers/GroupController***

<br>

## 客製UI ##
- 請參考後台樣版[AdminLTE 3](https://adminlte.io/themes/v3/)