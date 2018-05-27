<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'hobby');

/** Имя пользователя MySQL */
define('DB_USER', 'hobby');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '8w9Vx1Q2miVTVo9w');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'TiH<;-}VM-4;fG0M2RT&hptW}lh0>$T.;w]@1@{E<vhCx91Qn$GBEf]z~{BB+e]Y');
define('SECURE_AUTH_KEY',  '2v(FmgO%hY<;a=ElTay9s1QY(=&3j{t[ F9o|~3T;y%2@Ub}]0zVL{X6@p]mJ)U:');
define('LOGGED_IN_KEY',    '-5pE66D >vhINKWm9L,4JDr}&D.Pru{EVXTFU_S@zfLvpT=S`p}&l2ZHM+hTBKi+');
define('NONCE_KEY',        'gS+5KwO8:>e955r~cW/O06.T@4Uk!VMb,q@t{9H[yxJcHD9U4oNCuss6KHW|M>Ps');
define('AUTH_SALT',        'cHNd$BbtYH$C?m1#e}War(SOivgvzwNq|5t^ts](cC:)F2yzZw<QxBjgQ6[Vk8YO');
define('SECURE_AUTH_SALT', 'G@DB%F7YoMA2coo1To&]L!s;{hWkeLaEhj}L2@!xe>F^~ki5W]?E6RaRnec4`m!b');
define('LOGGED_IN_SALT',   'R <:6]!y`gqaGph2(F*z`{o_,nuw&M5Ce%@!e6s]^ChEx%p2n;nupsJW=g.Zx8)6');
define('NONCE_SALT',       'XMRU<B*V9smiaNEWJt3 viDRuT8vvm3&ePaH@{*Ierx(p1vS.W0sp}qHwLDA UX1');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'tsh_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
