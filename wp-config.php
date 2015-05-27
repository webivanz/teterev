<?php
/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи и ABSPATH. Дополнительную информацию можно найти на странице
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется скриптом для создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения вручную.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'wptest');

/** Имя пользователя MySQL */
define('DB_USER', 'wptest');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'wptest');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'lL$<Z~RH6S=A~Cnr-Y^kt%7|/w<00;(|Kq$Hx|lP`){MK)up,Z>VpI-n]PjMb[Fk');
define('SECURE_AUTH_KEY',  '-lc^[7ty8|`4-pF0o@_x[pS(qvJT7-.tuPR/TzeXeuvS@vkw8T-0UkcmS8~E: 2J');
define('LOGGED_IN_KEY',    '0cb/FqcCSf*-a&WWy{XL@[7|?;LTWZAo[IT26,:`JJo+DAGf|W:q&LO&gJpY-rtF');
define('NONCE_KEY',        '2OV_)%{>S|m_#Wtcr#G?<%NANzuorE]]qC3%mE%]exQe|p]IG]cI+xvwX3ZJNZZD');
define('AUTH_SALT',        'LeLh~2O4_D1:bsq-ZSwyFUuN|Pp:01-SF/l&AT83KoZm*3A:`(A|a(30Eg.IyWM)');
define('SECURE_AUTH_SALT', 'lf}@}3rj@w{|&&X_@=@XjWzdR6I0G;TRdsMpgKiMqL52D/;IfO*|GZ_y+2M[F13$');
define('LOGGED_IN_SALT',   '^C=mhId]g dTgeSs^!TNmfsF<]q7O^Uk&dN|f5$Kkj}(-,;&PD{Y,W9iw 0heE3T');
define('NONCE_SALT',       'm82$z9g|G{rVCLW&6O=t6`Ie+NX8[_Do?QQ%-J4t-lv8-q%+_}.1%oTf([*vs14u');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
