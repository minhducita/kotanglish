<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'kotanglish');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'root');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', '');

/** MySQL のホスト名 */
define('DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'vCjuN8FGs,|,$Brkk>H=zyDs2|~2DE-;QD*w_o>b->&<Fw&E~m`p.YyO a>jXBW+');
define('SECURE_AUTH_KEY',  'gnpv-u-u#7E,ZnUgB.J8UWim1h-WLK=p:3w|Zi&]`Dtv8<0-VGBcRUisZb@6P[sI');
define('LOGGED_IN_KEY',    'K$uh K!Vx1n.u@Z)x5d-.,I=nCL_a(Wu|g(&eb|yk>zA8r&xVmPt`4+rt#;`V1@&');
define('NONCE_KEY',        'W627zA%l`+oESP{[1VT[il /mda8&.SqP|v!Uv*e,>|,(U:l1`)]R@|-6#2KJ,jj');
define('AUTH_SALT',        '4xosGJ<{JcXbd J|,q9Q+bAFP*i)A}=-$JaS75t~aF:&|>h-2%&K|kMv@i0$zQBS');
define('SECURE_AUTH_SALT', '@KhAIoPDv~{i,VMUjy{_10;#[#F/]fT~V0!:3IFEZ~ox]Hg0zZQi4ww]>W^nc#j`');
define('LOGGED_IN_SALT',   '$uF>r@+784>:`4,I/YOz:}H(o$qhACqWpuDO1#^doDc7{:;#}_{_~GIz-ny| (WN');
define('NONCE_SALT',       'C]?iVa{6w!pgQ>[g=i 9GMgtJ_G !>?YTmx~X.O#YXzyt2Zu D7z}PJ-|}oj8-Dp');
/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'kg_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
