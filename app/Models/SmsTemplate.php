<?php namespace App\Models;

use App;
use App\Events\SmsSentEvent;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use URL;
use Config;
use App\DynamicMail\Facades\DynamicMail;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Armenium\LaraTwilioMulti\Facades\LaraTwilioMulti;
use App\Models\SentSms;
use Flash;
use Event;

/**
 * Class SmsTemplate
 * @package App\Models
 */

class SmsTemplate extends Model{
	use SoftDeletes;

	public $table = 'sms_templates';

	protected $dates = ['deleted_at'];

	/**
	 * The attributes that could be used in mass assignment.
	 * @var array
	 */
	protected $fillable = [
		'company_id',
		'language_id',
		'name',
		'content',
		'is_public',
	];

	/**
	 * The attributes that should be casted to native types.
	 * @var array
	 */
	protected $casts = [
		'company_id'  => 'integer',
		'language_id' => 'integer',
		'name'        => 'string',
		'content'     => 'string',
		'is_public'   => 'integer',
		//'link_name' => 'string',
	];

	/**
	 * Validation rules
	 * @var array
	 */
	public static $rules = [
		'name'    => 'required',
		'content' => 'required',
	];

    const WORKING_HOURS = [
        'from' => '09:00',
        'to' => '17:00'
    ];

    const ALL_COUNTRIES_LIST = [
        "AF" => [
            "country_name" => "Afghanistan",
            "mobile_codes" => [
                "+93"
            ],
            "timezones" => [
                "+04:30"
            ],
            "tz_range" => 0,
            "tz_center" => 4.5
        ],
        "AL" => [
            "country_name" => "Albania",
            "mobile_codes" => [
                "+355"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "DZ" => [
            "country_name" => "Algeria",
            "mobile_codes" => [
                "+213"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "AS" => [
            "country_name" => "American Samoa",
            "mobile_codes" => [
                "+1-684"
            ],
            "timezones" => [
                "-11:00"
            ],
            "tz_range" => 0,
            "tz_center" => -11
        ],
        "AD" => [
            "country_name" => "Andorra",
            "mobile_codes" => [
                "+376"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "AO" => [
            "country_name" => "Angola",
            "mobile_codes" => [
                "+244"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "AI" => [
            "country_name" => "Anguilla",
            "mobile_codes" => [
                "+1-264"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "AQ" => [
            "country_name" => "Antarctica",
            "mobile_codes" => [
                "+672"
            ],
            "timezones" => [
                "+11:00",
                "+07:00",
                "+10:00",
                "+05:00",
                "+13:00",
                "-03:00",
                "-03:00",
                "+03:00",
                "+00:00",
                "+06:00"
            ],
            "tz_range" => 16,
            "tz_center" => 5
        ],
        "AG" => [
            "country_name" => "Antigua and Barbuda",
            "mobile_codes" => [
                "+1-268"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "AR" => [
            "country_name" => "Argentina",
            "mobile_codes" => [
                "+54"
            ],
            "timezones" => [
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00",
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "AM" => [
            "country_name" => "Armenia",
            "mobile_codes" => [
                "+374"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "AW" => [
            "country_name" => "Aruba",
            "mobile_codes" => [
                "+297"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "AU" => [
            "country_name" => "Australia",
            "mobile_codes" => [
                "+61"
            ],
            "timezones" => [
                "+11:00",
                "+10:30",
                "+10:00",
                "+10:30",
                "+09:30",
                "+08:45",
                "+11:00",
                "+10:00",
                "+11:00",
                "+11:00",
                "+08:00",
                "+11:00"
            ],
            "tz_range" => 3,
            "tz_center" => 9.5
        ],
        "AT" => [
            "country_name" => "Austria",
            "mobile_codes" => [
                "+43"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "AZ" => [
            "country_name" => "Azerbaijan",
            "mobile_codes" => [
                "+994"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "BS" => [
            "country_name" => "Bahamas",
            "mobile_codes" => [
                "+1-242"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "BH" => [
            "country_name" => "Bahrain",
            "mobile_codes" => [
                "+973"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "BD" => [
            "country_name" => "Bangladesh",
            "mobile_codes" => [
                "+880"
            ],
            "timezones" => [
                "+06:00"
            ],
            "tz_range" => 0,
            "tz_center" => 6
        ],
        "BB" => [
            "country_name" => "Barbados",
            "mobile_codes" => [
                "+1-246"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "BY" => [
            "country_name" => "Belarus",
            "mobile_codes" => [
                "+375"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "BE" => [
            "country_name" => "Belgium",
            "mobile_codes" => [
                "+32"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "BZ" => [
            "country_name" => "Belize",
            "mobile_codes" => [
                "+501"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "BJ" => [
            "country_name" => "Benin",
            "mobile_codes" => [
                "+229"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "BM" => [
            "country_name" => "Bermuda",
            "mobile_codes" => [
                "+1-441"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "BT" => [
            "country_name" => "Bhutan",
            "mobile_codes" => [
                "+975"
            ],
            "timezones" => [
                "+06:00"
            ],
            "tz_range" => 0,
            "tz_center" => 6
        ],
        "BO" => [
            "country_name" => "Bolivia",
            "mobile_codes" => [
                "+591"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "BA" => [
            "country_name" => "Bosnia and Herzegovina",
            "mobile_codes" => [
                "+387"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "BW" => [
            "country_name" => "Botswana",
            "mobile_codes" => [
                "+267"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "BR" => [
            "country_name" => "Brazil",
            "mobile_codes" => [
                "+55"
            ],
            "timezones" => [
                "-03:00",
                "-03:00",
                "-03:00",
                "-04:00",
                "-04:00",
                "-04:00",
                "-05:00",
                "-03:00",
                "-03:00",
                "-04:00",
                "-02:00",
                "-04:00",
                "-03:00",
                "-05:00",
                "-03:00",
                "-03:00"
            ],
            "tz_range" => 3,
            "tz_center" => -3.5
        ],
        "IO" => [
            "country_name" => "British Indian Ocean Territory",
            "mobile_codes" => [
                "+246"
            ],
            "timezones" => [
                "+06:00"
            ],
            "tz_range" => 0,
            "tz_center" => 6
        ],
        "VG" => [
            "country_name" => "British Virgin Islands",
            "mobile_codes" => [
                "+1-284"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "BN" => [
            "country_name" => "Brunei",
            "mobile_codes" => [
                "+673"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "BG" => [
            "country_name" => "Bulgaria",
            "mobile_codes" => [
                "+359"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "BF" => [
            "country_name" => "Burkina Faso",
            "mobile_codes" => [
                "+226"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "BI" => [
            "country_name" => "Burundi",
            "mobile_codes" => [
                "+257"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "KH" => [
            "country_name" => "Cambodia",
            "mobile_codes" => [
                "+855"
            ],
            "timezones" => [
                "+07:00"
            ],
            "tz_range" => 0,
            "tz_center" => 7
        ],
        "CM" => [
            "country_name" => "Cameroon",
            "mobile_codes" => [
                "+237"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CA" => [
            "country_name" => "Canada",
            "mobile_codes" => [
                "+1"
            ],
            "timezones" => [
                "-05:00",
                "-04:00",
                "-07:00",
                "-07:00",
                "-07:00",
                "-07:00",
                "-07:00",
                "-07:00",
                "-04:00",
                "-04:00",
                "-04:00",
                "-07:00",
                "-05:00",
                "-04:00",
                "-06:00",
                "-06:00",
                "-06:00",
                "-03:30",
                "-06:00",
                "-05:00",
                "-08:00",
                "-07:00",
                "-06:00",
                "-07:00"
            ],
            "tz_range" => 4.5,
            "tz_center" => -5.75
        ],
        "CV" => [
            "country_name" => "Cape Verde",
            "mobile_codes" => [
                "+238"
            ],
            "timezones" => [
                "-01:00"
            ],
            "tz_range" => 0,
            "tz_center" => -1
        ],
        "KY" => [
            "country_name" => "Cayman Islands",
            "mobile_codes" => [
                "+1-345"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "CF" => [
            "country_name" => "Central African Republic",
            "mobile_codes" => [
                "+236"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "TD" => [
            "country_name" => "Chad",
            "mobile_codes" => [
                "+235"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CL" => [
            "country_name" => "Chile",
            "mobile_codes" => [
                "+56"
            ],
            "timezones" => [
                "-03:00",
                "-03:00",
                "-05:00"
            ],
            "tz_range" => 2,
            "tz_center" => -4
        ],
        "CN" => [
            "country_name" => "China",
            "mobile_codes" => [
                "+86"
            ],
            "timezones" => [
                "+08:00",
                "+06:00"
            ],
            "tz_range" => 2,
            "tz_center" => 7
        ],
        "CX" => [
            "country_name" => "Christmas Island",
            "mobile_codes" => [
                "+61"
            ],
            "timezones" => [
                "+07:00"
            ],
            "tz_range" => 0,
            "tz_center" => 7
        ],
        "CC" => [
            "country_name" => "Cocos Islands",
            "mobile_codes" => [
                "+61"
            ],
            "timezones" => [
                "+06:30"
            ],
            "tz_range" => 0,
            "tz_center" => 6.5
        ],
        "CO" => [
            "country_name" => "Colombia",
            "mobile_codes" => [
                "+57"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "KM" => [
            "country_name" => "Comoros",
            "mobile_codes" => [
                "+269"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "CK" => [
            "country_name" => "Cook Islands",
            "mobile_codes" => [
                "+682"
            ],
            "timezones" => [
                "-10:00"
            ],
            "tz_range" => 0,
            "tz_center" => -10
        ],
        "CR" => [
            "country_name" => "Costa Rica",
            "mobile_codes" => [
                "+506"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "HR" => [
            "country_name" => "Croatia",
            "mobile_codes" => [
                "+385"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CU" => [
            "country_name" => "Cuba",
            "mobile_codes" => [
                "+53"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "CW" => [
            "country_name" => "Curacao",
            "mobile_codes" => [
                "+599"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "CY" => [
            "country_name" => "Cyprus",
            "mobile_codes" => [
                "+357"
            ],
            "timezones" => [
                "+02:00",
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "CZ" => [
            "country_name" => "Czech Republic",
            "mobile_codes" => [
                "+420"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CD" => [
            "country_name" => "Democratic Republic of the Congo",
            "mobile_codes" => [
                "+243"
            ],
            "timezones" => [
                "+01:00",
                "+02:00"
            ],
            "tz_range" => 1,
            "tz_center" => 1.5
        ],
        "DK" => [
            "country_name" => "Denmark",
            "mobile_codes" => [
                "+45"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "DJ" => [
            "country_name" => "Djibouti",
            "mobile_codes" => [
                "+253"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "DM" => [
            "country_name" => "Dominica",
            "mobile_codes" => [
                "+1-767"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "DO" => [
            "country_name" => "Dominican Republic",
            "mobile_codes" => [
                "+1-809",
                "+ 1-829",
                "+ 1-849"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "TL" => [
            "country_name" => "East Timor",
            "mobile_codes" => [
                "+670"
            ],
            "timezones" => [
                "+09:00"
            ],
            "tz_range" => 0,
            "tz_center" => 9
        ],
        "EC" => [
            "country_name" => "Ecuador",
            "mobile_codes" => [
                "+593"
            ],
            "timezones" => [
                "-05:00",
                "-06:00"
            ],
            "tz_range" => 1,
            "tz_center" => -5.5
        ],
        "EG" => [
            "country_name" => "Egypt",
            "mobile_codes" => [
                "+20"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "SV" => [
            "country_name" => "El Salvador",
            "mobile_codes" => [
                "+503"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "GQ" => [
            "country_name" => "Equatorial Guinea",
            "mobile_codes" => [
                "+240"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "ER" => [
            "country_name" => "Eritrea",
            "mobile_codes" => [
                "+291"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "EE" => [
            "country_name" => "Estonia",
            "mobile_codes" => [
                "+372"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "ET" => [
            "country_name" => "Ethiopia",
            "mobile_codes" => [
                "+251"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "FK" => [
            "country_name" => "Falkland Islands",
            "mobile_codes" => [
                "+500"
            ],
            "timezones" => [
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "FO" => [
            "country_name" => "Faroe Islands",
            "mobile_codes" => [
                "+298"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "FJ" => [
            "country_name" => "Fiji",
            "mobile_codes" => [
                "+679"
            ],
            "timezones" => [
                "+12:00"
            ],
            "tz_range" => 0,
            "tz_center" => 12
        ],
        "FI" => [
            "country_name" => "Finland",
            "mobile_codes" => [
                "+358"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "FR" => [
            "country_name" => "France",
            "mobile_codes" => [
                "+33"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "PF" => [
            "country_name" => "French Polynesia",
            "mobile_codes" => [
                "+689"
            ],
            "timezones" => [
                "-09:00",
                "-09:30",
                "-10:00"
            ],
            "tz_range" => 1,
            "tz_center" => -9.5
        ],
        "GA" => [
            "country_name" => "Gabon",
            "mobile_codes" => [
                "+241"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "GM" => [
            "country_name" => "Gambia",
            "mobile_codes" => [
                "+220"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "GE" => [
            "country_name" => "Georgia",
            "mobile_codes" => [
                "+995"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "DE" => [
            "country_name" => "Germany",
            "mobile_codes" => [
                "+49"
            ],
            "timezones" => [
                "+01:00",
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "GH" => [
            "country_name" => "Ghana",
            "mobile_codes" => [
                "+233"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "GI" => [
            "country_name" => "Gibraltar",
            "mobile_codes" => [
                "+350"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "GR" => [
            "country_name" => "Greece",
            "mobile_codes" => [
                "+30"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "GL" => [
            "country_name" => "Greenland",
            "mobile_codes" => [
                "+299"
            ],
            "timezones" => [
                "+00:00",
                "-03:00",
                "-01:00",
                "-04:00"
            ],
            "tz_range" => 4,
            "tz_center" => -2
        ],
        "GD" => [
            "country_name" => "Grenada",
            "mobile_codes" => [
                "+1-473"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "GU" => [
            "country_name" => "Guam",
            "mobile_codes" => [
                "+1-671"
            ],
            "timezones" => [
                "+10:00"
            ],
            "tz_range" => 0,
            "tz_center" => 10
        ],
        "GT" => [
            "country_name" => "Guatemala",
            "mobile_codes" => [
                "+502"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "GG" => [
            "country_name" => "Guernsey",
            "mobile_codes" => [
                "+44-1481"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "GN" => [
            "country_name" => "Guinea",
            "mobile_codes" => [
                "+224"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "GW" => [
            "country_name" => "Guinea-Bissau",
            "mobile_codes" => [
                "+245"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "GY" => [
            "country_name" => "Guyana",
            "mobile_codes" => [
                "+592"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "HT" => [
            "country_name" => "Haiti",
            "mobile_codes" => [
                "+509"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "HN" => [
            "country_name" => "Honduras",
            "mobile_codes" => [
                "+504"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "HK" => [
            "country_name" => "Hong Kong",
            "mobile_codes" => [
                "+852"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "HU" => [
            "country_name" => "Hungary",
            "mobile_codes" => [
                "+36"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "IS" => [
            "country_name" => "Iceland",
            "mobile_codes" => [
                "+354"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "IN" => [
            "country_name" => "India",
            "mobile_codes" => [
                "+91"
            ],
            "timezones" => [
                "+05:30"
            ],
            "tz_range" => 0,
            "tz_center" => 5.5
        ],
        "ID" => [
            "country_name" => "Indonesia",
            "mobile_codes" => [
                "+62"
            ],
            "timezones" => [
                "+07:00",
                "+09:00",
                "+08:00",
                "+07:00"
            ],
            "tz_range" => 2,
            "tz_center" => 8
        ],
        "IR" => [
            "country_name" => "Iran",
            "mobile_codes" => [
                "+98"
            ],
            "timezones" => [
                "+03:30"
            ],
            "tz_range" => 0,
            "tz_center" => 3.5
        ],
        "IQ" => [
            "country_name" => "Iraq",
            "mobile_codes" => [
                "+964"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "IE" => [
            "country_name" => "Ireland",
            "mobile_codes" => [
                "+353"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "IM" => [
            "country_name" => "Isle of Man",
            "mobile_codes" => [
                "+44-1624"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "IL" => [
            "country_name" => "Israel",
            "mobile_codes" => [
                "+972"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "IT" => [
            "country_name" => "Italy",
            "mobile_codes" => [
                "+39"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CI" => [
            "country_name" => "Ivory Coast",
            "mobile_codes" => [
                "+225"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "JM" => [
            "country_name" => "Jamaica",
            "mobile_codes" => [
                "+1-876"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "JP" => [
            "country_name" => "Japan",
            "mobile_codes" => [
                "+81"
            ],
            "timezones" => [
                "+09:00"
            ],
            "tz_range" => 0,
            "tz_center" => 9
        ],
        "JE" => [
            "country_name" => "Jersey",
            "mobile_codes" => [
                "+44-1534"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "JO" => [
            "country_name" => "Jordan",
            "mobile_codes" => [
                "+962"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "KZ" => [
            "country_name" => "Kazakhstan",
            "mobile_codes" => [
                "+7"
            ],
            "timezones" => [
                "+06:00",
                "+05:00",
                "+05:00",
                "+05:00",
                "+05:00",
                "+06:00",
                "+05:00"
            ],
            "tz_range" => 1,
            "tz_center" => 5.5
        ],
        "KE" => [
            "country_name" => "Kenya",
            "mobile_codes" => [
                "+254"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "KI" => [
            "country_name" => "Kiribati",
            "mobile_codes" => [
                "+686"
            ],
            "timezones" => [
                "+13:00",
                "+14:00",
                "+12:00"
            ],
            "tz_range" => 2,
            "tz_center" => 13
        ],
        "XK" => [
            "country_name" => "Kosovo",
            "mobile_codes" => [
                "+383"
            ],
            "timezones" => [
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "KW" => [
            "country_name" => "Kuwait",
            "mobile_codes" => [
                "+965"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "KG" => [
            "country_name" => "Kyrgyzstan",
            "mobile_codes" => [
                "+996"
            ],
            "timezones" => [
                "+06:00"
            ],
            "tz_range" => 0,
            "tz_center" => 6
        ],
        "LA" => [
            "country_name" => "Laos",
            "mobile_codes" => [
                "+856"
            ],
            "timezones" => [
                "+07:00"
            ],
            "tz_range" => 0,
            "tz_center" => 7
        ],
        "LV" => [
            "country_name" => "Latvia",
            "mobile_codes" => [
                "+371"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "LB" => [
            "country_name" => "Lebanon",
            "mobile_codes" => [
                "+961"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "LS" => [
            "country_name" => "Lesotho",
            "mobile_codes" => [
                "+266"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "LR" => [
            "country_name" => "Liberia",
            "mobile_codes" => [
                "+231"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "LY" => [
            "country_name" => "Libya",
            "mobile_codes" => [
                "+218"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "LI" => [
            "country_name" => "Liechtenstein",
            "mobile_codes" => [
                "+423"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "LT" => [
            "country_name" => "Lithuania",
            "mobile_codes" => [
                "+370"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "LU" => [
            "country_name" => "Luxembourg",
            "mobile_codes" => [
                "+352"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MO" => [
            "country_name" => "Macau",
            "mobile_codes" => [
                "+853"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "MK" => [
            "country_name" => "Macedonia",
            "mobile_codes" => [
                "+389"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MG" => [
            "country_name" => "Madagascar",
            "mobile_codes" => [
                "+261"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "MW" => [
            "country_name" => "Malawi",
            "mobile_codes" => [
                "+265"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "MY" => [
            "country_name" => "Malaysia",
            "mobile_codes" => [
                "+60"
            ],
            "timezones" => [
                "+08:00",
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "MV" => [
            "country_name" => "Maldives",
            "mobile_codes" => [
                "+960"
            ],
            "timezones" => [
                "+05:00"
            ],
            "tz_range" => 0,
            "tz_center" => 5
        ],
        "ML" => [
            "country_name" => "Mali",
            "mobile_codes" => [
                "+223"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "MT" => [
            "country_name" => "Malta",
            "mobile_codes" => [
                "+356"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MH" => [
            "country_name" => "Marshall Islands",
            "mobile_codes" => [
                "+692"
            ],
            "timezones" => [
                "+12:00",
                "+12:00"
            ],
            "tz_range" => 0,
            "tz_center" => 12
        ],
        "MR" => [
            "country_name" => "Mauritania",
            "mobile_codes" => [
                "+222"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "MU" => [
            "country_name" => "Mauritius",
            "mobile_codes" => [
                "+230"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "YT" => [
            "country_name" => "Mayotte",
            "mobile_codes" => [
                "+262"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "MX" => [
            "country_name" => "Mexico",
            "mobile_codes" => [
                "+52"
            ],
            "timezones" => [
                "-06:00",
                "-05:00",
                "-06:00",
                "-07:00",
                "-07:00",
                "-06:00",
                "-07:00",
                "-06:00",
                "-06:00",
                "-06:00",
                "-06:00",
                "-08:00"
            ],
            "tz_range" => 3,
            "tz_center" => -6.5
        ],
        "FM" => [
            "country_name" => "Micronesia",
            "mobile_codes" => [
                "+691"
            ],
            "timezones" => [
                "+10:00",
                "+11:00",
                "+11:00"
            ],
            "tz_range" => 1,
            "tz_center" => 10.5
        ],
        "MD" => [
            "country_name" => "Moldova",
            "mobile_codes" => [
                "+373"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "MC" => [
            "country_name" => "Monaco",
            "mobile_codes" => [
                "+377"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MN" => [
            "country_name" => "Mongolia",
            "mobile_codes" => [
                "+976"
            ],
            "timezones" => [
                "+08:00",
                "+07:00",
                "+08:00"
            ],
            "tz_range" => 1,
            "tz_center" => 7.5
        ],
        "ME" => [
            "country_name" => "Montenegro",
            "mobile_codes" => [
                "+382"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MS" => [
            "country_name" => "Montserrat",
            "mobile_codes" => [
                "+1-664"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "MA" => [
            "country_name" => "Morocco",
            "mobile_codes" => [
                "+212"
            ],
            "timezones" => [
                "+01:00",
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "MZ" => [
            "country_name" => "Mozambique",
            "mobile_codes" => [
                "+258"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "MM" => [
            "country_name" => "Myanmar",
            "mobile_codes" => [
                "+95"
            ],
            "timezones" => [
                "+06:30"
            ],
            "tz_range" => 0,
            "tz_center" => 6.5
        ],
        "NA" => [
            "country_name" => "Namibia",
            "mobile_codes" => [
                "+264"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "NR" => [
            "country_name" => "Nauru",
            "mobile_codes" => [
                "+674"
            ],
            "timezones" => [
                "+12:00"
            ],
            "tz_range" => 0,
            "tz_center" => 12
        ],
        "NP" => [
            "country_name" => "Nepal",
            "mobile_codes" => [
                "+977"
            ],
            "timezones" => [
                "+05:45"
            ],
            "tz_range" => 0,
            "tz_center" => 5.75
        ],
        "NL" => [
            "country_name" => "Netherlands",
            "mobile_codes" => [
                "+31"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "AN" => [
            "country_name" => "Netherlands Antilles",
            "mobile_codes" => [
                "+599"
            ],
            "timezones" => [
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "NC" => [
            "country_name" => "New Caledonia",
            "mobile_codes" => [
                "+687"
            ],
            "timezones" => [
                "+11:00"
            ],
            "tz_range" => 0,
            "tz_center" => 11
        ],
        "NZ" => [
            "country_name" => "New Zealand",
            "mobile_codes" => [
                "+64"
            ],
            "timezones" => [
                "+13:00",
                "+13:45"
            ],
            "tz_range" => 0.75,
            "tz_center" => 13.375
        ],
        "NI" => [
            "country_name" => "Nicaragua",
            "mobile_codes" => [
                "+505"
            ],
            "timezones" => [
                "-06:00"
            ],
            "tz_range" => 0,
            "tz_center" => -6
        ],
        "NE" => [
            "country_name" => "Niger",
            "mobile_codes" => [
                "+227"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "NG" => [
            "country_name" => "Nigeria",
            "mobile_codes" => [
                "+234"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "NU" => [
            "country_name" => "Niue",
            "mobile_codes" => [
                "+683"
            ],
            "timezones" => [
                "-11:00"
            ],
            "tz_range" => 0,
            "tz_center" => -11
        ],
        "KP" => [
            "country_name" => "North Korea",
            "mobile_codes" => [
                "+850"
            ],
            "timezones" => [
                "+09:00"
            ],
            "tz_range" => 0,
            "tz_center" => 9
        ],
        "MP" => [
            "country_name" => "Northern Mariana Islands",
            "mobile_codes" => [
                "+1-670"
            ],
            "timezones" => [
                "+10:00"
            ],
            "tz_range" => 0,
            "tz_center" => 10
        ],
        "NO" => [
            "country_name" => "Norway",
            "mobile_codes" => [
                "+47"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "OM" => [
            "country_name" => "Oman",
            "mobile_codes" => [
                "+968"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "PK" => [
            "country_name" => "Pakistan",
            "mobile_codes" => [
                "+92"
            ],
            "timezones" => [
                "+05:00"
            ],
            "tz_range" => 0,
            "tz_center" => 5
        ],
        "PW" => [
            "country_name" => "Palau",
            "mobile_codes" => [
                "+680"
            ],
            "timezones" => [
                "+09:00"
            ],
            "tz_range" => 0,
            "tz_center" => 9
        ],
        "PS" => [
            "country_name" => "Palestine",
            "mobile_codes" => [
                "+970"
            ],
            "timezones" => [
                "+02:00",
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "PA" => [
            "country_name" => "Panama",
            "mobile_codes" => [
                "+507"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "PG" => [
            "country_name" => "Papua New Guinea",
            "mobile_codes" => [
                "+675"
            ],
            "timezones" => [
                "+11:00",
                "+10:00"
            ],
            "tz_range" => 1,
            "tz_center" => 10.5
        ],
        "PY" => [
            "country_name" => "Paraguay",
            "mobile_codes" => [
                "+595"
            ],
            "timezones" => [
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "PE" => [
            "country_name" => "Peru",
            "mobile_codes" => [
                "+51"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "PH" => [
            "country_name" => "Philippines",
            "mobile_codes" => [
                "+63"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "PN" => [
            "country_name" => "Pitcairn",
            "mobile_codes" => [
                "+64"
            ],
            "timezones" => [
                "-08:00"
            ],
            "tz_range" => 0,
            "tz_center" => -8
        ],
        "PL" => [
            "country_name" => "Poland",
            "mobile_codes" => [
                "+48"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "PT" => [
            "country_name" => "Portugal",
            "mobile_codes" => [
                "+351"
            ],
            "timezones" => [
                "-01:00",
                "+00:00",
                "+00:00"
            ],
            "tz_range" => 1,
            "tz_center" => -0.5
        ],
        "PR" => [
            "country_name" => "Puerto Rico",
            "mobile_codes" => [
                "+1-787",
                "+ 1-939"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "QA" => [
            "country_name" => "Qatar",
            "mobile_codes" => [
                "+974"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "CG" => [
            "country_name" => "Republic of the Congo",
            "mobile_codes" => [
                "+242"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "RE" => [
            "country_name" => "Reunion",
            "mobile_codes" => [
                "+262"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "RO" => [
            "country_name" => "Romania",
            "mobile_codes" => [
                "+40"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "RU" => [
            "country_name" => "Russia",
            "mobile_codes" => [
                "+7"
            ],
            "timezones" => [
                "+12:00",
                "+07:00",
                "+09:00",
                "+08:00",
                "+12:00",
                "+09:00",
                "+07:00",
                "+11:00",
                "+07:00",
                "+07:00",
                "+06:00",
                "+11:00",
                "+11:00",
                "+07:00",
                "+10:00",
                "+10:00",
                "+09:00",
                "+05:00",
                "+04:00",
                "+02:00",
                "+03:00",
                "+03:00",
                "+04:00",
                "+04:00",
                "+04:00",
                "+03:00"
            ],
            "tz_range" => 10,
            "tz_center" => 7
        ],
        "RW" => [
            "country_name" => "Rwanda",
            "mobile_codes" => [
                "+250"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "BL" => [
            "country_name" => "Saint Barthelemy",
            "mobile_codes" => [
                "+590"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "SH" => [
            "country_name" => "Saint Helena",
            "mobile_codes" => [
                "+290"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "KN" => [
            "country_name" => "Saint Kitts and Nevis",
            "mobile_codes" => [
                "+1-869"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "LC" => [
            "country_name" => "Saint Lucia",
            "mobile_codes" => [
                "+1-758"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "MF" => [
            "country_name" => "Saint Martin",
            "mobile_codes" => [
                "+590"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "PM" => [
            "country_name" => "Saint Pierre and Miquelon",
            "mobile_codes" => [
                "+508"
            ],
            "timezones" => [
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "VC" => [
            "country_name" => "Saint Vincent and the Grenadines",
            "mobile_codes" => [
                "+1-784"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "WS" => [
            "country_name" => "Samoa",
            "mobile_codes" => [
                "+685"
            ],
            "timezones" => [
                "+13:00"
            ],
            "tz_range" => 0,
            "tz_center" => 13
        ],
        "SM" => [
            "country_name" => "San Marino",
            "mobile_codes" => [
                "+378"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "ST" => [
            "country_name" => "Sao Tome and Principe",
            "mobile_codes" => [
                "+239"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "SA" => [
            "country_name" => "Saudi Arabia",
            "mobile_codes" => [
                "+966"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "SN" => [
            "country_name" => "Senegal",
            "mobile_codes" => [
                "+221"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "RS" => [
            "country_name" => "Serbia",
            "mobile_codes" => [
                "+381"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "SC" => [
            "country_name" => "Seychelles",
            "mobile_codes" => [
                "+248"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "SL" => [
            "country_name" => "Sierra Leone",
            "mobile_codes" => [
                "+232"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "SG" => [
            "country_name" => "Singapore",
            "mobile_codes" => [
                "+65"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "SX" => [
            "country_name" => "Sint Maarten",
            "mobile_codes" => [
                "+1-721"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "SK" => [
            "country_name" => "Slovakia",
            "mobile_codes" => [
                "+421"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "SI" => [
            "country_name" => "Slovenia",
            "mobile_codes" => [
                "+386"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "SB" => [
            "country_name" => "Solomon Islands",
            "mobile_codes" => [
                "+677"
            ],
            "timezones" => [
                "+11:00"
            ],
            "tz_range" => 0,
            "tz_center" => 11
        ],
        "SO" => [
            "country_name" => "Somalia",
            "mobile_codes" => [
                "+252"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "ZA" => [
            "country_name" => "South Africa",
            "mobile_codes" => [
                "+27"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "KR" => [
            "country_name" => "South Korea",
            "mobile_codes" => [
                "+82"
            ],
            "timezones" => [
                "+09:00"
            ],
            "tz_range" => 0,
            "tz_center" => 9
        ],
        "SS" => [
            "country_name" => "South Sudan",
            "mobile_codes" => [
                "+211"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "ES" => [
            "country_name" => "Spain",
            "mobile_codes" => [
                "+34"
            ],
            "timezones" => [
                "+01:00",
                "+00:00",
                "+01:00"
            ],
            "tz_range" => 1,
            "tz_center" => 0.5
        ],
        "LK" => [
            "country_name" => "Sri Lanka",
            "mobile_codes" => [
                "+94"
            ],
            "timezones" => [
                "+05:30"
            ],
            "tz_range" => 0,
            "tz_center" => 5.5
        ],
        "SD" => [
            "country_name" => "Sudan",
            "mobile_codes" => [
                "+249"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "SR" => [
            "country_name" => "Suriname",
            "mobile_codes" => [
                "+597"
            ],
            "timezones" => [
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "SJ" => [
            "country_name" => "Svalbard and Jan Mayen",
            "mobile_codes" => [
                "+47"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "SZ" => [
            "country_name" => "Swaziland",
            "mobile_codes" => [
                "+268"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "SE" => [
            "country_name" => "Sweden",
            "mobile_codes" => [
                "+46"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "CH" => [
            "country_name" => "Switzerland",
            "mobile_codes" => [
                "+41"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "SY" => [
            "country_name" => "Syria",
            "mobile_codes" => [
                "+963"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "TW" => [
            "country_name" => "Taiwan",
            "mobile_codes" => [
                "+886"
            ],
            "timezones" => [
                "+08:00"
            ],
            "tz_range" => 0,
            "tz_center" => 8
        ],
        "TJ" => [
            "country_name" => "Tajikistan",
            "mobile_codes" => [
                "+992"
            ],
            "timezones" => [
                "+05:00"
            ],
            "tz_range" => 0,
            "tz_center" => 5
        ],
        "TZ" => [
            "country_name" => "Tanzania",
            "mobile_codes" => [
                "+255"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "TH" => [
            "country_name" => "Thailand",
            "mobile_codes" => [
                "+66"
            ],
            "timezones" => [
                "+07:00"
            ],
            "tz_range" => 0,
            "tz_center" => 7
        ],
        "TG" => [
            "country_name" => "Togo",
            "mobile_codes" => [
                "+228"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "TK" => [
            "country_name" => "Tokelau",
            "mobile_codes" => [
                "+690"
            ],
            "timezones" => [
                "+13:00"
            ],
            "tz_range" => 0,
            "tz_center" => 13
        ],
        "TO" => [
            "country_name" => "Tonga",
            "mobile_codes" => [
                "+676"
            ],
            "timezones" => [
                "+13:00"
            ],
            "tz_range" => 0,
            "tz_center" => 13
        ],
        "TT" => [
            "country_name" => "Trinidad and Tobago",
            "mobile_codes" => [
                "+1-868"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "TN" => [
            "country_name" => "Tunisia",
            "mobile_codes" => [
                "+216"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "TR" => [
            "country_name" => "Turkey",
            "mobile_codes" => [
                "+90"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "TM" => [
            "country_name" => "Turkmenistan",
            "mobile_codes" => [
                "+993"
            ],
            "timezones" => [
                "+05:00"
            ],
            "tz_range" => 0,
            "tz_center" => 5
        ],
        "TC" => [
            "country_name" => "Turks and Caicos Islands",
            "mobile_codes" => [
                "+1-649"
            ],
            "timezones" => [
                "-05:00"
            ],
            "tz_range" => 0,
            "tz_center" => -5
        ],
        "TV" => [
            "country_name" => "Tuvalu",
            "mobile_codes" => [
                "+688"
            ],
            "timezones" => [
                "+12:00"
            ],
            "tz_range" => 0,
            "tz_center" => 12
        ],
        "VI" => [
            "country_name" => "U.S. Virgin Islands",
            "mobile_codes" => [
                "+1-340"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "UG" => [
            "country_name" => "Uganda",
            "mobile_codes" => [
                "+256"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "UA" => [
            "country_name" => "Ukraine",
            "mobile_codes" => [
                "+380"
            ],
            "timezones" => [
                "+02:00",
                "+03:00"
            ],
            "tz_range" => 1,
            "tz_center" => 2.5
        ],
        "AE" => [
            "country_name" => "United Arab Emirates",
            "mobile_codes" => [
                "+971"
            ],
            "timezones" => [
                "+04:00"
            ],
            "tz_range" => 0,
            "tz_center" => 4
        ],
        "GB" => [
            "country_name" => "United Kingdom",
            "mobile_codes" => [
                "+44"
            ],
            "timezones" => [
                "+00:00"
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "US" => [
            "country_name" => "United States",
            "mobile_codes" => [
                "+1"
            ],
            "timezones" => [
                "-10:00",
                "-09:00",
                "-07:00",
                "-06:00",
                "-07:00",
                "-05:00",
                "-05:00",
                "-06:00",
                "-05:00",
                "-05:00",
                "-06:00",
                "-05:00",
                "-05:00",
                "-05:00",
                "-09:00",
                "-05:00",
                "-05:00",
                "-08:00",
                "-06:00",
                "-09:00",
                "-05:00",
                "-09:00",
                "-06:00",
                "-06:00",
                "-06:00",
                "-07:00",
                "-09:00",
                "-09:00",
                "-10:00"
            ],
            "tz_range" => 5,
            "tz_center" => -7.5
        ],
        "UY" => [
            "country_name" => "Uruguay",
            "mobile_codes" => [
                "+598"
            ],
            "timezones" => [
                "-03:00"
            ],
            "tz_range" => 0,
            "tz_center" => -3
        ],
        "UZ" => [
            "country_name" => "Uzbekistan",
            "mobile_codes" => [
                "+998"
            ],
            "timezones" => [
                "+05:00",
                "+05:00"
            ],
            "tz_range" => 0,
            "tz_center" => 5
        ],
        "VU" => [
            "country_name" => "Vanuatu",
            "mobile_codes" => [
                "+678"
            ],
            "timezones" => [
                "+11:00"
            ],
            "tz_range" => 0,
            "tz_center" => 11
        ],
        "VA" => [
            "country_name" => "Vatican",
            "mobile_codes" => [
                "+379"
            ],
            "timezones" => [
                "+01:00"
            ],
            "tz_range" => 0,
            "tz_center" => 1
        ],
        "VE" => [
            "country_name" => "Venezuela",
            "mobile_codes" => [
                "+58"
            ],
            "timezones" => [
                "-04:00"
            ],
            "tz_range" => 0,
            "tz_center" => -4
        ],
        "VN" => [
            "country_name" => "Vietnam",
            "mobile_codes" => [
                "+84"
            ],
            "timezones" => [
                "+07:00"
            ],
            "tz_range" => 0,
            "tz_center" => 7
        ],
        "WF" => [
            "country_name" => "Wallis and Futuna",
            "mobile_codes" => [
                "+681"
            ],
            "timezones" => [
                "+12:00"
            ],
            "tz_range" => 0,
            "tz_center" => 12
        ],
        "EH" => [
            "country_name" => "Western Sahara",
            "mobile_codes" => [
                "+212"
            ],
            "timezones" => [
            ],
            "tz_range" => 0,
            "tz_center" => 0
        ],
        "YE" => [
            "country_name" => "Yemen",
            "mobile_codes" => [
                "+967"
            ],
            "timezones" => [
                "+03:00"
            ],
            "tz_range" => 0,
            "tz_center" => 3
        ],
        "ZM" => [
            "country_name" => "Zambia",
            "mobile_codes" => [
                "+260"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ],
        "ZW" => [
            "country_name" => "Zimbabwe",
            "mobile_codes" => [
                "+263"
            ],
            "timezones" => [
                "+02:00"
            ],
            "tz_range" => 0,
            "tz_center" => 2
        ]
    ];

    public $selectedDomainUrl = null;

	public function image(){
		return $this->morphOne('App\Models\Image', 'imageable')->latest();
	}

	public function company(){
		return $this->belongsTo('App\Models\Company');
	}

	public function campaigns(){
		return $this->hasMany('App\Models\Campaign');
	}

	public function language(){
		return $this->belongsTo('App\Models\Language');
	}

	public static function boot(){
		self::creating(function($model){
			if(!$model->language_id){
				$model->language_id = 1;
			}
		});

		self::updating(function($model){
			if(!$model->language_id){
				$model->language_id = 1;
			}
		});
	}

    /**
     * Send email based on current template. Including data required for tracking
     *
     * @param Recipient $recipient
     * @param null $campaign
     * @param bool $test
     *
     * @return bool
     */
    public function send(Recipient $recipient, $campaign = null){
        Log::stack(['custom'])->debug(__METHOD__);
        $sendSms = false;
        $this->selectedDomainUrl = 'https://'.$campaign->schedule->domain->domain;
        $content_data = $this->buildContent($recipient, $campaign);
        $content = $content_data['content'];
        $tracker = $content_data['tracker'];

//        process this for testing to log only countries where is working hours at the moment
//        foreach (self::ALL_COUNTRIES_LIST as $item) {
//            if ($this->checkIfBusinessHoursForRecipient($item['mobile_codes'][0] . '111111111')) {
//                dump($item['country_name'] . ' ' . $item['mobile_codes'][0] . ' 111111111' . ' Sent ' . $item['timezones'][0]);
//            }
//        }

        if (!empty($recipient->mobile)) {
            $sendSms = LaraTwilioMulti::notify($recipient->mobile, $content);
            dd($sendSms);
            try {
                if ($this->checkIfBusinessHoursForRecipient($recipient->mobile)) {
                    $sendSms = LaraTwilioMulti::smsFrom(1231313131)->notify($recipient->mobile, $content);
                    Log::stack(['custom'])->debug($recipient->mobile . ' Sent');
                } else {
                    Log::stack(['custom'])->debug($recipient->mobile . ' Outside working hours');
                }

            } catch (\Exception $exception) {
                Log::stack(['custom'])->debug($exception->getMessage());
                Flash::error($exception->getMessage());
            }
        }

		if($sendSms){
			Event::dispatch(new SmsSentEvent($tracker));
        }
        return $sendSms;
    }

    /**
     * @param $mobileNumber
     * @return bool
     */
    public function checkIfBusinessHoursForRecipient($mobileNumber)
    {
        $countryOfRecipient = null;

        foreach (self::ALL_COUNTRIES_LIST as $item){
            foreach ($item['mobile_codes'] as $mobile_code) {
                if (substr($mobileNumber, 0, strlen($mobile_code)) === $mobile_code) {
                    $countryOfRecipient = $item;
                    break;
                }
            }
        }

        if (!$countryOfRecipient){
            return false;
        }

        $rangeDiff = $countryOfRecipient['tz_center'];
        $offset = $countryOfRecipient['tz_range'] / 2;
        $gmt0 = time();
        $pst = $gmt0 + ($rangeDiff * 3600);
        $rangeStart = $pst - ($offset * 3600);
        $rangeEnd = $pst + ($offset * 3600);

        $workingStart = strtotime(self::WORKING_HOURS['from']);
        $workingEnd = strtotime(self::WORKING_HOURS['to']);

        if ($rangeStart >= $workingStart && $rangeEnd <= $workingEnd) {
            return true;
        }

        return false;
    }

    /**
     * @param $timezones
     * @return float|int
     * @throws \Exception
     */
    public function getTimeZoneRange($timezones)
    {
        $min = null;
        $max = null;
        foreach ($timezones as $timezone) {
            $dt = new DateTime("now", new DateTimeZone($timezone));
            $offset = $dt->getOffset();
            if ($min === null || $offset < $min) {
                $min = $offset;
            }
            if ($max === null || $offset > $max) {
                $max = $offset;
            }
        }

        return ($max - $min) / 3600;
    }

	public function buildContent(Recipient $recipient, $campaign = null){

		$data = $this->makeTrackingUrl($recipient, $campaign);

		$with = [
			'.FirstName'  => $recipient->first_name,
			'.LastName'   => $recipient->last_name,
			#'.Sms'      => $recipient->mobile,
			#'.From'       => env('TWILIO_SMS_FROM'),
			#'.Position'   => $recipient->position,
			#'.Department' => $recipient->department,
			'.YEAR'       => date('Y'),
			'.MONTH'      => date('m'),
			'.DAY'        => date('d'),
			'.URL'        => $data['url'],
		];

		$login_variables = $this->getLoginVariables();
		foreach($login_variables as $login_variable){
			$with['.'.$login_variable['variable']] = $this->makeFakeLoginPageUrl($recipient, $campaign, $login_variable['path']);
		}

		$content = $this->content;
		foreach($with as $k => $v){
			$content = str_replace('{{'.$k.'}}', $v, $content);
		}

		return [
			'tracker' => $data['tracker'],
			'content' => $content,
		];
	}

	public function makeTrackingUrl(Recipient $recipient, $campaign = null){
		//$tracking_url = '';
		$campaignWithPivot = null;

		#Log::stack(['custom'])->debug('campaign = '.$campaign->id);
		#Log::stack(['custom'])->debug('recipient = '.$recipient->campaigns());
		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
		}
		#Log::stack(['custom'])->debug('campaignWithPivot = '.$campaignWithPivot);

		if(!$campaignWithPivot){
			throw new \Exception('Recipient got no Campaign');
		}else{
			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
		}
		$tracking_url = $this->selectedDomainUrl ? "$this->selectedDomainUrl/landingpage3" : config('app.url').'/landingpage3';
		if(strpos($tracking_url, 'http://') !== 0 && strpos($tracking_url, 'https://') !== 0){
			$tracking_url = 'http://'.$tracking_url;
		}

		$send_to_landing = $campaignWithPivot->schedule->send_to_landing;
		$redirect_url    = $campaignWithPivot->schedule->redirect_url;

		if(intval($send_to_landing) == 0 && !empty($redirect_url)){
			$tracking_url = $redirect_url;
		}

        $tracker = $this->hashUrlAndCreateSentSms($tracking_url, 'landingpage3', $recipient, $campaign);

		return [
			'tracker' => $tracker,
			'url' => $this->generateShortUrl($tracking_url, true)
		];
	}

    public function hashUrlAndCreateSentSms($url, $searchReplace, $recipient, $campaign)
    {
        do{
            $hash = Str::random(32);
            $used = SentSms::where('hash', $hash)->count();
        }while($used > 0);

        $tracking_url = str_replace('&amp;', '&', $url);
        $url = str_replace("/", "$", base64_encode($tracking_url));
        $url .= '/'.$hash;
        $tracking_url = str_replace($searchReplace, 'sms/l/', $tracking_url).$url;

        return SentSms::create([
            'hash' => $hash,
            'url' => $tracking_url,
            'recipient' => $recipient->first_name,
            'phone' => $recipient->mobile,
            'campaign_id' => $campaign->id,
        ]);
	}

	public function generateShortUrl($src_url, $logging = false){

		if($logging){
            Log::stack(['custom'])->debug($src_url);
        }

		$code = ShortLink::generate($src_url);
		$dst_url = $this->selectedDomainUrl . '/short/' . $code->code;

		if($logging)
			Log::stack(['custom'])->debug($dst_url);

		return $dst_url;
	}

	public function makeFakeLoginPageUrl(Recipient $recipient, $campaign = null, $login_page = ''){
		//$tracking_url = '';
		$campaignWithPivot = null;

		if($campaign){
			$campaignWithPivot = $recipient->campaigns()->find($campaign->id);
		}

		/*if($campaignWithPivot){
			$href = $campaignWithPivot->schedule->domain->url.'?rid='.$campaignWithPivot->pivot->code;

			if(!$campaignWithPivot->schedule){
				throw new \Exception('Campaign got no schedule');
			}
			if(!$campaignWithPivot->schedule->domain){
				throw new \Exception('Campaign got no domain');
			}
		}*/

		$params = $recipient->email.','.$campaign->id;
		$params = base64_encode($params);
		$uri = 'account/'.$login_page.'?id='.$params;

		$loginUrl = env('PHISHING_MANAGER_LOGIN_PAGE_URL', config('app.url') . '/' . $uri);

		if(strpos($loginUrl, 'http://') !== 0 && strpos($loginUrl, 'https://') !== 0){
            $loginUrl = 'http://'.$loginUrl;
		}

		$send_to_landing = $campaignWithPivot->schedule->send_to_landing;
		$redirect_url    = $campaignWithPivot->schedule->redirect_url;

		if(intval($send_to_landing) == 0 && !empty($redirect_url)){
            $loginUrl = $redirect_url;
		}

        $tracker =  $this->hashUrlAndCreateSentSms($loginUrl, $uri, $recipient, $campaign);
        return $this->generateShortUrl($tracker->url, true);
	}

	public function getLoginVariables(){
		$root        = dirname(__DIR__, 2);
		$dir         = $root.'/public/account/';
		$results     = [];
		$login_pages = $this->getSubFolders($dir);
		foreach($login_pages as $login_page){
			$login_page_folders = $this->getSubFolders($dir.$login_page);
			$login_page_files   = $this->getFolderFiles($dir.$login_page);
			if(in_array('assets', $login_page_folders) && in_array('index.html', $login_page_files)){
				$results[] = [
					'variable'    => 'login-'.$login_page,
					'path'        => $login_page,
					'description' => 'URL to fake '.strtoupper($login_page).' login page '
				];
			}
		}

		return $results;
	}

	public function getFolderFiles($dir){
		$items = scandir($dir);
		$files = [];
		foreach($items as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)){
				$files[] = $value;
			}
		}

		return $files;
	}

	public function getSubFolders($dir){
		$files   = scandir($dir);
		$folders = [];
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($value != "." && $value != ".." && is_dir($path)){
				$folders[] = $value;
			}
		}

		return $folders;
	}
}
