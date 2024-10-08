<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Shared\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();

        $countries = [
            ['id' => 1, 'iso2' => 'AF', 'name' => 'Afghanistan', 'phonecode' => 93],
            ['id' => 2, 'iso2' => 'AL', 'name' => 'Albania', 'phonecode' => 355],
            ['id' => 3, 'iso2' => 'DZ', 'name' => 'Algeria', 'phonecode' => 213],
            ['id' => 4, 'iso2' => 'AS', 'name' => 'American Samoa', 'phonecode' => 1684],
            ['id' => 5, 'iso2' => 'AD', 'name' => 'Andorra', 'phonecode' => 376],
            ['id' => 6, 'iso2' => 'AO', 'name' => 'Angola', 'phonecode' => 244],
            ['id' => 7, 'iso2' => 'AI', 'name' => 'Anguilla', 'phonecode' => 1264],
            ['id' => 8, 'iso2' => 'AQ', 'name' => 'Antarctica', 'phonecode' => 0],
            ['id' => 9, 'iso2' => 'AG', 'name' => 'Antigua And Barbuda', 'phonecode' => 1268],
            ['id' => 10, 'iso2' => 'AR', 'name' => 'Argentina', 'phonecode' => 54],
            ['id' => 11, 'iso2' => 'AM', 'name' => 'Armenia', 'phonecode' => 374],
            ['id' => 12, 'iso2' => 'AW', 'name' => 'Aruba', 'phonecode' => 297],
            ['id' => 13, 'iso2' => 'AU', 'name' => 'Australia', 'phonecode' => 61],
            ['id' => 14, 'iso2' => 'AT', 'name' => 'Austria', 'phonecode' => 43],
            ['id' => 15, 'iso2' => 'AZ', 'name' => 'Azerbaijan', 'phonecode' => 994],
            ['id' => 16, 'iso2' => 'BS', 'name' => 'Bahamas The', 'phonecode' => 1242],
            ['id' => 17, 'iso2' => 'BH', 'name' => 'Bahrain', 'phonecode' => 973],
            ['id' => 18, 'iso2' => 'BD', 'name' => 'Bangladesh', 'phonecode' => 880],
            ['id' => 19, 'iso2' => 'BB', 'name' => 'Barbados', 'phonecode' => 1246],
            ['id' => 20, 'iso2' => 'BY', 'name' => 'Belarus', 'phonecode' => 375],
            ['id' => 21, 'iso2' => 'BE', 'name' => 'Belgium', 'phonecode' => 32],
            ['id' => 22, 'iso2' => 'BZ', 'name' => 'Belize', 'phonecode' => 501],
            ['id' => 23, 'iso2' => 'BJ', 'name' => 'Benin', 'phonecode' => 229],
            ['id' => 24, 'iso2' => 'BM', 'name' => 'Bermuda', 'phonecode' => 1441],
            ['id' => 25, 'iso2' => 'BT', 'name' => 'Bhutan', 'phonecode' => 975],
            ['id' => 26, 'iso2' => 'BO', 'name' => 'Bolivia', 'phonecode' => 591],
            ['id' => 27, 'iso2' => 'BA', 'name' => 'Bosnia and Herzegovina', 'phonecode' => 387],
            ['id' => 28, 'iso2' => 'BW', 'name' => 'Botswana', 'phonecode' => 267],
            ['id' => 29, 'iso2' => 'BV', 'name' => 'Bouvet Island', 'phonecode' => 0],
            ['id' => 30, 'iso2' => 'BR', 'name' => 'Brazil', 'phonecode' => 55],
            ['id' => 31, 'iso2' => 'IO', 'name' => 'British Indian Ocean Territory', 'phonecode' => 246],
            ['id' => 32, 'iso2' => 'BN', 'name' => 'Brunei', 'phonecode' => 673],
            ['id' => 33, 'iso2' => 'BG', 'name' => 'Bulgaria', 'phonecode' => 359],
            ['id' => 34, 'iso2' => 'BF', 'name' => 'Burkina Faso', 'phonecode' => 226],
            ['id' => 35, 'iso2' => 'BI', 'name' => 'Burundi', 'phonecode' => 257],
            ['id' => 36, 'iso2' => 'KH', 'name' => 'Cambodia', 'phonecode' => 855],
            ['id' => 37, 'iso2' => 'CM', 'name' => 'Cameroon', 'phonecode' => 237],
            ['id' => 38, 'iso2' => 'CA', 'name' => 'Canada', 'phonecode' => 1],
            ['id' => 39, 'iso2' => 'CV', 'name' => 'Cape Verde', 'phonecode' => 238],
            ['id' => 40, 'iso2' => 'KY', 'name' => 'Cayman Islands', 'phonecode' => 1345],
            ['id' => 41, 'iso2' => 'CF', 'name' => 'Central African Republic', 'phonecode' => 236],
            ['id' => 42, 'iso2' => 'TD', 'name' => 'Chad', 'phonecode' => 235],
            ['id' => 43, 'iso2' => 'CL', 'name' => 'Chile', 'phonecode' => 56],
            ['id' => 44, 'iso2' => 'CN', 'name' => 'China', 'phonecode' => 86],
            ['id' => 45, 'iso2' => 'CX', 'name' => 'Christmas Island', 'phonecode' => 61],
            ['id' => 46, 'iso2' => 'CC', 'name' => 'Cocos (Keeling) Islands', 'phonecode' => 672],
            ['id' => 47, 'iso2' => 'CO', 'name' => 'Colombia', 'phonecode' => 57],
            ['id' => 48, 'iso2' => 'KM', 'name' => 'Comoros', 'phonecode' => 269],
            ['id' => 49, 'iso2' => 'CG', 'name' => 'Congo', 'phonecode' => 242],
            ['id' => 50, 'iso2' => 'CD', 'name' => 'Congo The Democratic Republic Of The', 'phonecode' => 242],
            ['id' => 51, 'iso2' => 'CK', 'name' => 'Cook Islands', 'phonecode' => 682],
            ['id' => 52, 'iso2' => 'CR', 'name' => 'Costa Rica', 'phonecode' => 506],
            ['id' => 53, 'iso2' => 'CI', 'name' => 'Cote D Ivoire (Ivory Coast)', 'phonecode' => 225],
            ['id' => 54, 'iso2' => 'HR', 'name' => 'Croatia (Hrvatska)', 'phonecode' => 385],
            ['id' => 55, 'iso2' => 'CU', 'name' => 'Cuba', 'phonecode' => 53],
            ['id' => 56, 'iso2' => 'CY', 'name' => 'Cyprus', 'phonecode' => 357],
            ['id' => 57, 'iso2' => 'CZ', 'name' => 'Czech Republic', 'phonecode' => 420],
            ['id' => 58, 'iso2' => 'DK', 'name' => 'Denmark', 'phonecode' => 45],
            ['id' => 59, 'iso2' => 'DJ', 'name' => 'Djibouti', 'phonecode' => 253],
            ['id' => 60, 'iso2' => 'DM', 'name' => 'Dominica', 'phonecode' => 1767],
            ['id' => 61, 'iso2' => 'DO', 'name' => 'Dominican Republic', 'phonecode' => 1809],
            ['id' => 62, 'iso2' => 'TP', 'name' => 'East Timor', 'phonecode' => 670],
            ['id' => 63, 'iso2' => 'EC', 'name' => 'Ecuador', 'phonecode' => 593],
            ['id' => 64, 'iso2' => 'EG', 'name' => 'Egypt', 'phonecode' => 20],
            ['id' => 65, 'iso2' => 'SV', 'name' => 'El Salvador', 'phonecode' => 503],
            ['id' => 66, 'iso2' => 'GQ', 'name' => 'Equatorial Guinea', 'phonecode' => 240],
            ['id' => 67, 'iso2' => 'ER', 'name' => 'Eritrea', 'phonecode' => 291],
            ['id' => 68, 'iso2' => 'EE', 'name' => 'Estonia', 'phonecode' => 372],
            ['id' => 69, 'iso2' => 'ET', 'name' => 'Ethiopia', 'phonecode' => 251],
            ['id' => 70, 'iso2' => 'XA', 'name' => 'External Territories of Australia', 'phonecode' => 61],
            ['id' => 71, 'iso2' => 'FK', 'name' => 'Falkland Islands', 'phonecode' => 500],
            ['id' => 72, 'iso2' => 'FO', 'name' => 'Faroe Islands', 'phonecode' => 298],
            ['id' => 73, 'iso2' => 'FJ', 'name' => 'Fiji Islands', 'phonecode' => 679],
            ['id' => 74, 'iso2' => 'FI', 'name' => 'Finland', 'phonecode' => 358],
            ['id' => 75, 'iso2' => 'FR', 'name' => 'France', 'phonecode' => 33],
            ['id' => 76, 'iso2' => 'GF', 'name' => 'French Guiana', 'phonecode' => 594],
            ['id' => 77, 'iso2' => 'PF', 'name' => 'French Polynesia', 'phonecode' => 689],
            ['id' => 78, 'iso2' => 'TF', 'name' => 'French Southern Territories', 'phonecode' => 0],
            ['id' => 79, 'iso2' => 'GA', 'name' => 'Gabon', 'phonecode' => 241],
            ['id' => 80, 'iso2' => 'GM', 'name' => 'Gambia The', 'phonecode' => 220],
            ['id' => 81, 'iso2' => 'GE', 'name' => 'Georgia', 'phonecode' => 995],
            ['id' => 82, 'iso2' => 'DE', 'name' => 'Germany', 'phonecode' => 49],
            ['id' => 83, 'iso2' => 'GH', 'name' => 'Ghana', 'phonecode' => 233],
            ['id' => 84, 'iso2' => 'GI', 'name' => 'Gibraltar', 'phonecode' => 350],
            ['id' => 85, 'iso2' => 'GR', 'name' => 'Greece', 'phonecode' => 30],
            ['id' => 86, 'iso2' => 'GL', 'name' => 'Greenland', 'phonecode' => 299],
            ['id' => 87, 'iso2' => 'GD', 'name' => 'Grenada', 'phonecode' => 1473],
            ['id' => 88, 'iso2' => 'GP', 'name' => 'Guadeloupe', 'phonecode' => 590],
            ['id' => 89, 'iso2' => 'GU', 'name' => 'Guam', 'phonecode' => 1671],
            ['id' => 90, 'iso2' => 'GT', 'name' => 'Guatemala', 'phonecode' => 502],
            ['id' => 91, 'iso2' => 'XU', 'name' => 'Guernsey and Alderney', 'phonecode' => 44],
            ['id' => 92, 'iso2' => 'GN', 'name' => 'Guinea', 'phonecode' => 224],
            ['id' => 93, 'iso2' => 'GW', 'name' => 'Guinea-Bissau', 'phonecode' => 245],
            ['id' => 94, 'iso2' => 'GY', 'name' => 'Guyana', 'phonecode' => 592],
            ['id' => 95, 'iso2' => 'HT', 'name' => 'Haiti', 'phonecode' => 509],
            ['id' => 96, 'iso2' => 'HM', 'name' => 'Heard and McDonald Islands', 'phonecode' => 0],
            ['id' => 97, 'iso2' => 'HN', 'name' => 'Honduras', 'phonecode' => 504],
            ['id' => 98, 'iso2' => 'HK', 'name' => 'Hong Kong S.A.R.', 'phonecode' => 852],
            ['id' => 99, 'iso2' => 'HU', 'name' => 'Magyarország', 'phonecode' => 36],
            ['id' => 100, 'iso2' => 'IS', 'name' => 'Iceland', 'phonecode' => 354],
            ['id' => 101, 'iso2' => 'IN', 'name' => 'India', 'phonecode' => 91],
            ['id' => 102, 'iso2' => 'ID', 'name' => 'Indonesia', 'phonecode' => 62],
            ['id' => 103, 'iso2' => 'IR', 'name' => 'Iran', 'phonecode' => 98],
            ['id' => 104, 'iso2' => 'IQ', 'name' => 'Iraq', 'phonecode' => 964],
            ['id' => 105, 'iso2' => 'IE', 'name' => 'Ireland', 'phonecode' => 353],
            ['id' => 106, 'iso2' => 'IL', 'name' => 'Israel', 'phonecode' => 972],
            ['id' => 107, 'iso2' => 'IT', 'name' => 'Italy', 'phonecode' => 39],
            ['id' => 108, 'iso2' => 'JM', 'name' => 'Jamaica', 'phonecode' => 1876],
            ['id' => 109, 'iso2' => 'JP', 'name' => 'Japan', 'phonecode' => 81],
            ['id' => 110, 'iso2' => 'XJ', 'name' => 'Jersey', 'phonecode' => 44],
            ['id' => 111, 'iso2' => 'JO', 'name' => 'Jordan', 'phonecode' => 962],
            ['id' => 112, 'iso2' => 'KZ', 'name' => 'Kazakhstan', 'phonecode' => 7],
            ['id' => 113, 'iso2' => 'KE', 'name' => 'Kenya', 'phonecode' => 254],
            ['id' => 114, 'iso2' => 'KI', 'name' => 'Kiribati', 'phonecode' => 686],
            ['id' => 115, 'iso2' => 'KP', 'name' => 'Korea North', 'phonecode' => 850],
            ['id' => 116, 'iso2' => 'KR', 'name' => 'Korea South', 'phonecode' => 82],
            ['id' => 117, 'iso2' => 'KW', 'name' => 'Kuwait', 'phonecode' => 965],
            ['id' => 118, 'iso2' => 'KG', 'name' => 'Kyrgyzstan', 'phonecode' => 996],
            ['id' => 119, 'iso2' => 'LA', 'name' => 'Laos', 'phonecode' => 856],
            ['id' => 120, 'iso2' => 'LV', 'name' => 'Latvia', 'phonecode' => 371],
            ['id' => 121, 'iso2' => 'LB', 'name' => 'Lebanon', 'phonecode' => 961],
            ['id' => 122, 'iso2' => 'LS', 'name' => 'Lesotho', 'phonecode' => 266],
            ['id' => 123, 'iso2' => 'LR', 'name' => 'Liberia', 'phonecode' => 231],
            ['id' => 124, 'iso2' => 'LY', 'name' => 'Libya', 'phonecode' => 218],
            ['id' => 125, 'iso2' => 'LI', 'name' => 'Liechtenstein', 'phonecode' => 423],
            ['id' => 126, 'iso2' => 'LT', 'name' => 'Lithuania', 'phonecode' => 370],
            ['id' => 127, 'iso2' => 'LU', 'name' => 'Luxembourg', 'phonecode' => 352],
            ['id' => 128, 'iso2' => 'MO', 'name' => 'Macau S.A.R.', 'phonecode' => 853],
            ['id' => 129, 'iso2' => 'MK', 'name' => 'Macedonia', 'phonecode' => 389],
            ['id' => 130, 'iso2' => 'MG', 'name' => 'Madagascar', 'phonecode' => 261],
            ['id' => 131, 'iso2' => 'MW', 'name' => 'Malawi', 'phonecode' => 265],
            ['id' => 132, 'iso2' => 'MY', 'name' => 'Malaysia', 'phonecode' => 60],
            ['id' => 133, 'iso2' => 'MV', 'name' => 'Maldives', 'phonecode' => 960],
            ['id' => 134, 'iso2' => 'ML', 'name' => 'Mali', 'phonecode' => 223],
            ['id' => 135, 'iso2' => 'MT', 'name' => 'Malta', 'phonecode' => 356],
            ['id' => 136, 'iso2' => 'XM', 'name' => 'Man (Isle of)', 'phonecode' => 44],
            ['id' => 137, 'iso2' => 'MH', 'name' => 'Marshall Islands', 'phonecode' => 692],
            ['id' => 138, 'iso2' => 'MQ', 'name' => 'Martinique', 'phonecode' => 596],
            ['id' => 139, 'iso2' => 'MR', 'name' => 'Mauritania', 'phonecode' => 222],
            ['id' => 140, 'iso2' => 'MU', 'name' => 'Mauritius', 'phonecode' => 230],
            ['id' => 141, 'iso2' => 'YT', 'name' => 'Mayotte', 'phonecode' => 269],
            ['id' => 142, 'iso2' => 'MX', 'name' => 'Mexico', 'phonecode' => 52],
            ['id' => 143, 'iso2' => 'FM', 'name' => 'Micronesia', 'phonecode' => 691],
            ['id' => 144, 'iso2' => 'MD', 'name' => 'Moldova', 'phonecode' => 373],
            ['id' => 145, 'iso2' => 'MC', 'name' => 'Monaco', 'phonecode' => 377],
            ['id' => 146, 'iso2' => 'MN', 'name' => 'Mongolia', 'phonecode' => 976],
            ['id' => 147, 'iso2' => 'MS', 'name' => 'Montserrat', 'phonecode' => 1664],
            ['id' => 148, 'iso2' => 'MA', 'name' => 'Morocco', 'phonecode' => 212],
            ['id' => 149, 'iso2' => 'MZ', 'name' => 'Mozambique', 'phonecode' => 258],
            ['id' => 150, 'iso2' => 'MM', 'name' => 'Myanmar', 'phonecode' => 95],
            ['id' => 151, 'iso2' => 'NA', 'name' => 'Namibia', 'phonecode' => 264],
            ['id' => 152, 'iso2' => 'NR', 'name' => 'Nauru', 'phonecode' => 674],
            ['id' => 153, 'iso2' => 'NP', 'name' => 'Nepal', 'phonecode' => 977],
            ['id' => 154, 'iso2' => 'AN', 'name' => 'Netherlands Antilles', 'phonecode' => 599],
            ['id' => 155, 'iso2' => 'NL', 'name' => 'Netherlands The', 'phonecode' => 31],
            ['id' => 156, 'iso2' => 'NC', 'name' => 'New Caledonia', 'phonecode' => 687],
            ['id' => 157, 'iso2' => 'NZ', 'name' => 'New Zealand', 'phonecode' => 64],
            ['id' => 158, 'iso2' => 'NI', 'name' => 'Nicaragua', 'phonecode' => 505],
            ['id' => 159, 'iso2' => 'NE', 'name' => 'Niger', 'phonecode' => 227],
            ['id' => 160, 'iso2' => 'NG', 'name' => 'Nigeria', 'phonecode' => 234],
            ['id' => 161, 'iso2' => 'NU', 'name' => 'Niue', 'phonecode' => 683],
            ['id' => 162, 'iso2' => 'NF', 'name' => 'Norfolk Island', 'phonecode' => 672],
            ['id' => 163, 'iso2' => 'MP', 'name' => 'Northern Mariana Islands', 'phonecode' => 1670],
            ['id' => 164, 'iso2' => 'NO', 'name' => 'Norway', 'phonecode' => 47],
            ['id' => 165, 'iso2' => 'OM', 'name' => 'Oman', 'phonecode' => 968],
            ['id' => 166, 'iso2' => 'PK', 'name' => 'Pakistan', 'phonecode' => 92],
            ['id' => 167, 'iso2' => 'PW', 'name' => 'Palau', 'phonecode' => 680],
            ['id' => 168, 'iso2' => 'PS', 'name' => 'Palestinian Territory Occupied', 'phonecode' => 970],
            ['id' => 169, 'iso2' => 'PA', 'name' => 'Panama', 'phonecode' => 507],
            ['id' => 170, 'iso2' => 'PG', 'name' => 'Papua new Guinea', 'phonecode' => 675],
            ['id' => 171, 'iso2' => 'PY', 'name' => 'Paraguay', 'phonecode' => 595],
            ['id' => 172, 'iso2' => 'PE', 'name' => 'Peru', 'phonecode' => 51],
            ['id' => 173, 'iso2' => 'PH', 'name' => 'Philippines', 'phonecode' => 63],
            ['id' => 174, 'iso2' => 'PN', 'name' => 'Pitcairn Island', 'phonecode' => 0],
            ['id' => 175, 'iso2' => 'PL', 'name' => 'Poland', 'phonecode' => 48],
            ['id' => 176, 'iso2' => 'PT', 'name' => 'Portugal', 'phonecode' => 351],
            ['id' => 177, 'iso2' => 'PR', 'name' => 'Puerto Rico', 'phonecode' => 1787],
            ['id' => 178, 'iso2' => 'QA', 'name' => 'Qatar', 'phonecode' => 974],
            ['id' => 179, 'iso2' => 'RE', 'name' => 'Reunion', 'phonecode' => 262],
            ['id' => 180, 'iso2' => 'RO', 'name' => 'Romania', 'phonecode' => 40],
            ['id' => 181, 'iso2' => 'RU', 'name' => 'Russia', 'phonecode' => 70],
            ['id' => 182, 'iso2' => 'RW', 'name' => 'Rwanda', 'phonecode' => 250],
            ['id' => 183, 'iso2' => 'SH', 'name' => 'Saint Helena', 'phonecode' => 290],
            ['id' => 184, 'iso2' => 'KN', 'name' => 'Saint Kitts And Nevis', 'phonecode' => 1869],
            ['id' => 185, 'iso2' => 'LC', 'name' => 'Saint Lucia', 'phonecode' => 1758],
            ['id' => 186, 'iso2' => 'PM', 'name' => 'Saint Pierre and Miquelon', 'phonecode' => 508],
            ['id' => 187, 'iso2' => 'VC', 'name' => 'Saint Vincent And The Grenadines', 'phonecode' => 1784],
            ['id' => 188, 'iso2' => 'WS', 'name' => 'Samoa', 'phonecode' => 684],
            ['id' => 189, 'iso2' => 'SM', 'name' => 'San Marino', 'phonecode' => 378],
            ['id' => 190, 'iso2' => 'ST', 'name' => 'Sao Tome and Principe', 'phonecode' => 239],
            ['id' => 191, 'iso2' => 'SA', 'name' => 'Saudi Arabia', 'phonecode' => 966],
            ['id' => 192, 'iso2' => 'SN', 'name' => 'Senegal', 'phonecode' => 221],
            ['id' => 193, 'iso2' => 'RS', 'name' => 'Serbia', 'phonecode' => 381],
            ['id' => 194, 'iso2' => 'SC', 'name' => 'Seychelles', 'phonecode' => 248],
            ['id' => 195, 'iso2' => 'SL', 'name' => 'Sierra Leone', 'phonecode' => 232],
            ['id' => 196, 'iso2' => 'SG', 'name' => 'Singapore', 'phonecode' => 65],
            ['id' => 197, 'iso2' => 'SK', 'name' => 'Slovakia', 'phonecode' => 421],
            ['id' => 198, 'iso2' => 'SI', 'name' => 'Slovenia', 'phonecode' => 386],
            ['id' => 199, 'iso2' => 'XG', 'name' => 'Smaller Territories of the UK', 'phonecode' => 44],
            ['id' => 200, 'iso2' => 'SB', 'name' => 'Solomon Islands', 'phonecode' => 677],
            ['id' => 201, 'iso2' => 'SO', 'name' => 'Somalia', 'phonecode' => 252],
            ['id' => 202, 'iso2' => 'ZA', 'name' => 'South Africa', 'phonecode' => 27],
            ['id' => 203, 'iso2' => 'GS', 'name' => 'South Georgia', 'phonecode' => 0],
            ['id' => 204, 'iso2' => 'SS', 'name' => 'South Sudan', 'phonecode' => 211],
            ['id' => 205, 'iso2' => 'ES', 'name' => 'Spain', 'phonecode' => 34],
            ['id' => 206, 'iso2' => 'LK', 'name' => 'Sri Lanka', 'phonecode' => 94],
            ['id' => 207, 'iso2' => 'SD', 'name' => 'Sudan', 'phonecode' => 249],
            ['id' => 208, 'iso2' => 'SR', 'name' => 'Suriname', 'phonecode' => 597],
            ['id' => 209, 'iso2' => 'SJ', 'name' => 'Svalbard And Jan Mayen Islands', 'phonecode' => 47],
            ['id' => 210, 'iso2' => 'SZ', 'name' => 'Swaziland', 'phonecode' => 268],
            ['id' => 211, 'iso2' => 'SE', 'name' => 'Sweden', 'phonecode' => 46],
            ['id' => 212, 'iso2' => 'CH', 'name' => 'Switzerland', 'phonecode' => 41],
            ['id' => 213, 'iso2' => 'SY', 'name' => 'Syria', 'phonecode' => 963],
            ['id' => 214, 'iso2' => 'TW', 'name' => 'Taiwan', 'phonecode' => 886],
            ['id' => 215, 'iso2' => 'TJ', 'name' => 'Tajikistan', 'phonecode' => 992],
            ['id' => 216, 'iso2' => 'TZ', 'name' => 'Tanzania', 'phonecode' => 255],
            ['id' => 217, 'iso2' => 'TH', 'name' => 'Thailand', 'phonecode' => 66],
            ['id' => 218, 'iso2' => 'TG', 'name' => 'Togo', 'phonecode' => 228],
            ['id' => 219, 'iso2' => 'TK', 'name' => 'Tokelau', 'phonecode' => 690],
            ['id' => 220, 'iso2' => 'TO', 'name' => 'Tonga', 'phonecode' => 676],
            ['id' => 221, 'iso2' => 'TT', 'name' => 'Trinidad And Tobago', 'phonecode' => 1868],
            ['id' => 222, 'iso2' => 'TN', 'name' => 'Tunisia', 'phonecode' => 216],
            ['id' => 223, 'iso2' => 'TR', 'name' => 'Turkey', 'phonecode' => 90],
            ['id' => 224, 'iso2' => 'TM', 'name' => 'Turkmenistan', 'phonecode' => 7370],
            ['id' => 225, 'iso2' => 'TC', 'name' => 'Turks And Caicos Islands', 'phonecode' => 1649],
            ['id' => 226, 'iso2' => 'TV', 'name' => 'Tuvalu', 'phonecode' => 688],
            ['id' => 227, 'iso2' => 'UG', 'name' => 'Uganda', 'phonecode' => 256],
            ['id' => 228, 'iso2' => 'UA', 'name' => 'Ukraine', 'phonecode' => 380],
            ['id' => 229, 'iso2' => 'AE', 'name' => 'United Arab Emirates', 'phonecode' => 971],
            ['id' => 230, 'iso2' => 'GB', 'name' => 'United Kingdom', 'phonecode' => 44],
            ['id' => 231, 'iso2' => 'US', 'name' => 'United States', 'phonecode' => 1],
            ['id' => 232, 'iso2' => 'UM', 'name' => 'United States Minor Outlying Islands', 'phonecode' => 1],
            ['id' => 233, 'iso2' => 'UY', 'name' => 'Uruguay', 'phonecode' => 598],
            ['id' => 234, 'iso2' => 'UZ', 'name' => 'Uzbekistan', 'phonecode' => 998],
            ['id' => 235, 'iso2' => 'VU', 'name' => 'Vanuatu', 'phonecode' => 678],
            ['id' => 236, 'iso2' => 'VA', 'name' => 'Vatican City State (Holy See)', 'phonecode' => 39],
            ['id' => 237, 'iso2' => 'VE', 'name' => 'Venezuela', 'phonecode' => 58],
            ['id' => 238, 'iso2' => 'VN', 'name' => 'Vietnam', 'phonecode' => 84],
            ['id' => 239, 'iso2' => 'VG', 'name' => 'Virgin Islands (British)', 'phonecode' => 1284],
            ['id' => 240, 'iso2' => 'VI', 'name' => 'Virgin Islands (US)', 'phonecode' => 1340],
            ['id' => 241, 'iso2' => 'WF', 'name' => 'Wallis And Futuna Islands', 'phonecode' => 681],
            ['id' => 242, 'iso2' => 'EH', 'name' => 'Western Sahara', 'phonecode' => 212],
            ['id' => 243, 'iso2' => 'YE', 'name' => 'Yemen', 'phonecode' => 967],
            ['id' => 244, 'iso2' => 'YU', 'name' => 'Yugoslavia', 'phonecode' => 38],
            ['id' => 245, 'iso2' => 'ZM', 'name' => 'Zambia', 'phonecode' => 260],
            ['id' => 246, 'iso2' => 'ZW', 'name' => 'Zimbabwe', 'phonecode' => 263],
        ];

        Country::insert($countries);

        Schema::enableForeignKeyConstraints();
    }
}
